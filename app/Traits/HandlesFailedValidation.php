<?php

/**
 * @ Author: Minhazul Abedin(Innova IT)
 * @ Create Time: 2025-05-14 13:05:30
 * @ Modified time: 2025-05-14 13:25:35
 * @ Description: All rights reserved to Innova IT
 */

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait HandlesFailedValidation
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' =>  $validator->errors()->first(),
            ], 422)
        );
    }
}
