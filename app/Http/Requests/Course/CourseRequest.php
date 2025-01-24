<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'course_category_id' => 'integer|exists:course_categories,id|' . ($this->status == 1 ? 'required' : 'nullable'),
            'course_id' => 'nullable|integer',
            'slug' => 'nullable|string|unique:courses,slug',
            'bg_color' => 'required|string',
            'btn_color' => 'required|string',
            'status' => 'required|integer',
            'contents' => 'required|array',
            'contents.*.language_id' => 'required|integer',
            'contents.*.name' => 'required|string|max:256|unique:course_contents,name'
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contents.*.language_id.required' => 'The language ID is required.',
            'contents.*.language_id.integer' => 'The language ID must be an integer.',
            'contents.*.name.required' => 'The name field is required.',
            'contents.*.name.string' => 'The name field must be a string.',
            'contents.*.name.max' => 'The name field must not exceed 256 characters for each content entry.',
            'contents.*.name.unique' => 'The name field must be unique.',
        ];
    }
}
