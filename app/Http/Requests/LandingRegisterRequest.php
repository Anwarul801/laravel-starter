<?php

namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class LandingRegisterRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'otp' => 'nullable|string|size:4',
        ];
    }

    public function messages(): array
    {
        return [
            'contact.required' => 'Phone number or email is required',
            'otp.required' => 'OTP is required',
        ];
    }
}
