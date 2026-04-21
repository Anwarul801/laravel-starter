<?php

/**
 * @ Author: Minhazul Abedin(Innova IT)
 * @ Create Time: 2025-05-27 15:04:19
 * @ Modified time: 2025-08-28 11:42:55
 * @ Description: All rights reserved to Innova IT
 */

namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class CourseEnrollRequest extends FormRequest
{
    use HandlesFailedValidation;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id'       => 'required|exists:courses,id',
            // 'payment_method'  => 'required|in:Cash,Online,BKash,Bank,SSL',
            // 'payment_for'     => 'required|string|in:Online,Online Without Material,Offline',
            // 'amount'          => 'required|numeric|min:0',
            'branch_id'       => 'nullable|exists:branches,id',
            'group_id'        => 'nullable|exists:groups,id',
            'schedule_id'     => 'nullable|exists:schedules,id',
            'admission_date'  => 'nullable|date',
            'offer_id'        => 'nullable|exists:offers,id',
            'curier_address'  => 'nullable|string|max:255',
            'payment_for'  => 'nullable|string|max:255',
            'payment_method'  => 'nullable|string|max:255',
        ];
    }
}
