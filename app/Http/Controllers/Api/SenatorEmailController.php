<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SenatorEmailRequest;
use App\Models\Senator;
use App\Services\EmailService;
use App\Http\Helpers\ResponseFormatter;

class SenatorEmailController
{
    /**
     * @param SenatorEmailRequest $request
     * @param Senator $senatorModel
     * @param EmailService $emailService
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(SenatorEmailRequest $request, Senator $senatorModel, EmailService $emailService)
    {
        try {
            //have valid data from the request
            $senatorID = (int)$request->safe()->input('senator_id');

            $senator = $senatorModel->where('id', '=',$senatorID)
                ->select('email')
                ->first();

            //destructuring for easy debug
            $input = $request->safe()->only(['last_name', 'email', 'message']);

            //This method uses the exception model to redirect flow, if return is preferred, note in code review
            $emailService->send($senator, $input['last_name'], $input['email'], $input['message']);

            return ResponseFormatter::success('Message sent', 200);
        } catch (\Exception $exception) {
            //TODO get this message copy from translation file and for the voicing appropriate to usage here
            return ResponseFormatter::error(
                'An error occurred sending the message, our IT staff will be notified',
                500,
                ['error' => $exception->getMessage() . ' ' . $exception->getFile() . ' ' . $exception->getLine()]);
        }
    }
}
