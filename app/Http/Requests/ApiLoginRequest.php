<?php

namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ApiLoginRequest extends FormRequest
{
    use HandlesFailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'contact' => [
                'required',
                function ($attribute, $value, $fail) {

                    $isPhone = preg_match('/^0\d{10}$/', $value);
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);

                    if (!$isPhone && !$isEmail) {
                        $fail('সঠিক ফোন নাম্বার অথবা ইমেইল দিন।');
                    }
                }
            ],

            'password' => 'required|string|min:6',

            'device_token' => 'nullable|string|max:255',
            'device_name'  => 'nullable|string|max:255',
            'device_type'  => 'nullable|string|in:web,android,ios',
            'platform'     => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'পাসওয়ার্ড দিতে হবে',
            'password.min'      => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে',

            'device_token.required' => 'Device token is required',
            'device_type.required'  => 'Device type is required',
            'device_type.in'        => 'Device type must be one of: web, android, ios',
        ];
    }
}