<?php
/**
 * @ Author: Minhazul Abedin(Innova IT)
 * @ Create Time: 2025-05-27 15:04:19
 * @ Modified time: 2025-05-27 16:19:04
 * @ Description: All rights reserved to Innova IT
 */

namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class FreeCourseEnrollRequest extends FormRequest
{
    use HandlesFailedValidation;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => 'required|exists:courses,id',
        ];
    }
}
