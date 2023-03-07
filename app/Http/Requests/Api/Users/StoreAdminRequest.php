<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'                      => ['required', 'string', 'max:255'],
            'middle_name'                     => ['nullable', 'string', 'max:255'],
            'last_name'                       => ['required', 'string', 'max:255'],
            'username'                        => ['required', 'string', 'unique:admins', 'unique:users', 'max:16'],
            'personal_email'                  => ['required', 'unique:admins', 'unique:users', 'email:rfc,dns', 'max:100'],
            'company_email'                   => ['nullable', 'unique:admins', 'unique:users', 'email:rfc,dns', 'max:100'],
            'password'                        => ['required', 'string', 'min:8', 'max:16', 'confirmed'],
            'country_calling_code'            => ['required', 'string', 'regex:/^\+\d{1,5}$/'],
            'mobile_number'                   => ['required', 'numeric', 'digits:10', 'unique:admins', 'starts_with:6,7,8,9', 'regex:/^[0-9]{10}$/'],
            'alternate_country_calling_code'  => ['required_with:alternate_mobile_number', 'string', 'regex:/^\+\d{1,5}$/'],
            'alternate_mobile_number'         => ['required_with:alternate_country_code', 'numeric', 'digits:10', 'unique:admins', 'starts_with:6,7,8,9', 'regex:/^[0-9]{10}$/'],
            'is_active'                       => ['required', 'boolean'],
            'gender'                          => ['nullable', 'string'],
            'date_of_birth'                   => ['nullable', 'date', 'date_format:Y-m-d', 'after:2000-01-01'],
            'avatar'                          => ['nullable', 'mimes:jpg,jpeg,png', 'max:3072'],
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'success'  => false,
            'errorMsg' => 'Validation Errors.',
            'messages' => $validator->errors()->all(),
        ]), 422);
    }

    public function messages(): array
    {
        return [
            'alternate_country_code.required_with'  => 'The Alternate Country code is required when Alternate Mobile Number is present.',
            'alternate_mobile_number.required_with' => 'The Alternate Mobile Number is required when Alternate Country code is present.',
            'alternate_mobile_number.unique'        => 'Sorry, this Alternate Mobile Number is already used by another User.',
            'company_email.email'                   => 'Company Email must be valid Email Address',
            'company_email.unique'                  => 'Sorry, this Company Email Address is already assigned to another User.',
            'mobile_number.unique'                  => 'Sorry, this Mobile Number is already used by another User.',
            'personal_email.email'                  => 'Personal Email must be valid Email Address',
            'personal_email.unique'                 => 'Sorry, this Personal Email Address is already used by another User.',
            'username.unique'                       => 'Sorry, this Username Is already used by another user.',
        ];
    }
}
