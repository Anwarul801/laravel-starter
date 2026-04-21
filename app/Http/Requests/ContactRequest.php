<?php

/**
 * @ Author: Minhazul Abedin(Innova IT)
 * @ Create Time: 2025-05-14 10:56:31
 * @ Modified time: 2025-05-14 13:09:17
 * @ Description: All rights reserved to Innova IT
 */

namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    use HandlesFailedValidation;


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ];
    }
}
