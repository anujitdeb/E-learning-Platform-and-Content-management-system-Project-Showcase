<?php

namespace App\Http\Requests\Mentor;

use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class MentorRequest extends FormRequest
{
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
            'course_category_id' => 'required|integer',
            'image' => [($this->route('mentor') ? 'nullable' : 'required'), new Base64ImageValidation],
            'is_active' => 'required|boolean',
            'is_head' => 'required|boolean',
            'contents' => 'required|array|min:2',
            'contents.*.language_id' => 'required|integer',
            'contents.*.name' => 'required|string|max:255',
            'contents.*.designation' => 'required|string|max:255',
            'contents.*.experience' => 'required|string|max:255',
            'contents.*.student_qty' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'contents.*.language_id.required' => 'The language ID is required.',
            'contents.*.language_id.integer' => 'The language ID must be an integer.',
            'contents.*.name.required' => 'The name field is required.',
            'contents.*.name.string' => 'The name field must be a string.',
            'contents.*.name.max' => 'The name field must not exceed 256 characters for each content entry.',
            'contents.*.designation.required' => 'The designation field is required.',
            'contents.*.designation.max' => 'The designation field must not exceed 256 characters for each content entry.',
            'contents.*.designation.string' => 'The designation field must be a string.',
            'contents.*.experience.required' => 'The experience field is required.',
            'contents.*.experience.max' => 'The experience field must not exceed 256 characters for each content entry.',
            'contents.*.experience.string' => 'The experience field must be a string.',
            'contents.*.student_qty.required' => 'The student_qty field is required.',
            'contents.*.student_qty.max' => 'The student_qty field must not exceed 256 characters for each content entry.',
            'contents.*.student_qty.string' => 'The student_qty field must be a string.',

        ];
    }
}
