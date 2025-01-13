<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class SignatureCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->signatureValidate($request)) {
            return redirect('403');
        }
        return $next($request);
    }

    private function signatureValidate(Request $request): bool
    {
        $sig = $request->input('signature');
        $time = $request->input('t');

        if (empty($sig) || empty($time)) {
            return false;
        }

        try {
            $decrypt = Crypt::decryptString(base64_decode($sig));
        } catch (DecryptException $e) {
           return false;
        }

        $decrypt = explode("|", $decrypt);

        $publicKey = config('app.api_key');
        if ( str_contains($decrypt[0], $publicKey) === false) {
            return false;
        }

        $carbonTime = Carbon::createFromTimestamp($time);

        if ($carbonTime->diffInSeconds(Carbon::now()) > 300) {
            return false;
        }


        return true;
    }
}
