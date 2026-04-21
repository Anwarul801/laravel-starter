<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-19 11:20:42
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-25 17:03:45
 * @Description: Innova IT
 */



namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StudentProfileRequest extends FormRequest
{
    use HandlesFailedValidation;


    public function authorize()
    {
        return true;
    }

public function rules()
{
    $user = Auth::guard('api')->user();

    return [
        'name' => ['nullable', 'string', 'max:100'],

        'email' => [
            'nullable',
            'email:rfc,dns',
            Rule::unique('users', 'email')->ignore($user->id),
        ],

        'current_password' => ['nullable', 'required_with:new_password'],
        'new_password' => ['nullable', 'min:6'], 

        'profession' => ['nullable', 'string'],
        'address' => ['nullable', 'string'],
        'dob' => ['nullable', 'string', 'max:100'],
        'gender' => ['nullable', 'string', 'max:100'],

        'profile_image' => [
            'nullable',
            'image',
            'mimes:jpeg,png,jpg,webp',
            'max:2048',
        ],
    ];
}

}
