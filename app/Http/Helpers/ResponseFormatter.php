<?php
namespace App\Http\Helpers;
use Illuminate\Support\Facades\Log;

class ResponseFormatter
{
    /** Returns our specific api response formatting to guarantee formatting and logs the error in question
     * @param string $message
     * @param int $code
     * @param array $additionalData
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(string $message, int $code, array $additionalData = [])
    {
        Log::error('Api Error: ' . $message . '. with additional data: ' . json_encode($additionalData));

        return response()->json(['Status' => 'Error', 'Message' => $message], $code);
    }

    public static function success(string $message, int $code, array $additionalData = []){
        return response()->json(['Status' => 'Success', 'Message' => $message], $code);
    }
}
