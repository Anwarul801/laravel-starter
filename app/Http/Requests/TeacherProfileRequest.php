<?php

/**
 * @ Author: Minhazul Abedin(Innova IT)
 * @ Create Time: 2025-05-14 10:56:31
 * @ Modified time: 2025-07-15 13:48:46
 * @ Description: All rights reserved to Innova IT
 */

namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherProfileRequest extends FormRequest
{
    use HandlesFailedValidation;


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['string'],
            'name' => ['string', 'max:100'],
            'bn_name' => ['string', 'nullable', 'max:100'],

            'profession_id' => ['nullable', Rule::exists('professions', 'id')],

            'father_name' => ['string', 'nullable', 'max:100'],
            'father_phone' => ['string', 'nullable', 'max:100'],
            'father_profession_id' => ['nullable', Rule::exists('professions', 'id')],

            'mother_name' => ['string', 'nullable', 'max:100'],
            'mother_phone' => ['string', 'nullable', 'max:100'],
            'mother_profession_id' => ['nullable', Rule::exists('professions', 'id')],

            'date_of_birth' => ['nullable', 'date'],

            'gender' => ['string', 'nullable', 'in:male,female,other'],
            'blood_id' => ['nullable', Rule::exists('bloods', 'id')],

            'present_address' => ['string', 'nullable', 'max:255'],
            'thana_id' => ['nullable', Rule::exists('thanas', 'id')],
            'district_id' => ['nullable', Rule::exists('districts', 'id')],

            'permanent_address' => ['string', 'nullable', 'max:255'],
            'per_thana_id' => ['nullable', Rule::exists('thanas', 'id')],
            'per_district_id' => ['nullable', Rule::exists('districts', 'id')],

            'payment_method' => ['nullable', 'string', 'in:bkash,nogod,rocket,bank'],
            'payment_phone' => ['nullable', 'string', 'max:50'],

            'account_holder_name' => ['nullable', 'string', 'max:100'],
            'account_no' => ['nullable', 'string', 'max:100'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],

            'experiences' => ['nullable', 'array'],
            'experiences.*.institution' => ['required', 'string', 'max:255'],
            'experiences.*.department' => ['nullable', 'string', 'max:255'],
            'experiences.*.joining_date' => ['required', 'date'],
            'experiences.*.ending_date' => ['required', 'date'],
            'experiences.*.designation' => ['required', 'string', 'max:255'],
            'experiences.*.experience_year' => ['required', 'string', 'max:10'],
            'experiences.*.subject' => ['required', 'string', 'max:255'],
            'experiences.*.type' => ['required', 'in:Training,Regular'],
            'academic_exam_id' => ['nullable', 'array'],
            'academic_exam_id.*' => ['nullable', 'integer'],

            'board_id' => ['nullable', 'array'],
            'board_id.*' => ['nullable', 'integer'],

            'passing_year' => ['nullable', 'array'],
            'roll_number' => ['nullable', 'array'],
            'registration_number' => ['nullable', 'array'],
            'gpa' => ['nullable', 'array'],

            'institution_id' => ['nullable', 'array'],
            'institution_names' => ['nullable', 'array'],
            'institution_district_id' => ['nullable', 'array'],
            'institution_thana_id' => ['nullable', 'array'],
            // 'profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],


        ];
    }
}
