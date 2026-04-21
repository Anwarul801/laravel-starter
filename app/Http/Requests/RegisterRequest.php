<?php
/**
 * @Author: Anwarul
 * @Date: 2025-09-30 13:19:11
 * @LastEditors: Anwarul
 * @LastEditTime: 2025-10-11 10:22:58
 * @Description: Innova IT
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'nullable|email:rfc,dns|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ];
    }
}
