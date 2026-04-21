<?php

namespace App\Http\Requests;

use App\Traits\HandlesFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'course_id' => 'nullable|integer|exists:courses,id',
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'status' => 'nullable|in:Private,Public',
            'reply_to_message_id' => 'nullable|integer|exists:messages,id',
            'responded_teacher_id' => 'nullable|integer|exists:teachers,id',
            'from_id' => 'nullable|integer|',
            'body' => 'nullable|string',
            'file' => 'nullable|', 
            'type' => 'nullable|string|in:text,file,audio',
        ];
    }
}
