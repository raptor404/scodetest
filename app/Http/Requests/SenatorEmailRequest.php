<?php

namespace App\Http\Requests;

use App\Http\Helpers\ResponseFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class SenatorEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //signature check middleware is good enough
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'senator_id' => 'required|int|exists:senator,id',
            'email' => 'required|email|max:512',
            'last_name' => 'required|string|max:255',
            'message' => 'required|string|max:2048'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ResponseFormatter::error(
                'Validation Error',
                422,
                [
                    'errors' => $validator->errors(),
                ],
                422)
        );
    }
}
