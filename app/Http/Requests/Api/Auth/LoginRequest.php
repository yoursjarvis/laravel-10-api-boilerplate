<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'exists:users,username', 'max:16'],
            'password' => ['required', 'string', 'max:16'],
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'failed',
            'errorMsg' => 'Validation Errors.',
            'errors'  => $validator->errors()->all(),
        ]), 422);
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username field is required.',
            'password.required' => 'Password field is required.',
        ];
    }
}
