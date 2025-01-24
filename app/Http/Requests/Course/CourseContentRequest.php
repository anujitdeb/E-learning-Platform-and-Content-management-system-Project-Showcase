<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseContentRequest extends FormRequest
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
        $rules = [
            'contents' => 'required|array',
            'contents.*.language_id' => 'required|integer|exists:languages,id',
            'contents.*.id' => 'required|integer|exists:course_contents,id',
            'contents.*.course_duration' => 'required|string|max:128',
            'contents.*.lectures_qty' => 'required|string|min:1|max:128',
            'contents.*.project_qty' => 'required|string|min:1|max:128',
            'contents.*.description' => 'required|string|max:1024',

            /* Course main fields */
            'slug' => 'nullable|string|max:256|unique:courses,slug,' . optional($this->course)->id,
            'offline_price' => 'required|numeric|min:0',
            'online_price' => 'required|numeric|min:0',
            'status' => 'required|integer|in:1,2',
            'bg_color' => 'required|string',
            'btn_color' => 'required|string',
        ];

        foreach ($this->input('contents', []) as $key => $content) {
            $rules['contents.' . $key . '.name'] = [
                'required',
                'string',
                'max:256',
                Rule::unique('course_contents', 'name')
                    ->ignore($content['id'])
            ];
        }

        return $rules;
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contents.required' => 'The contents field is required.',
            'contents.array' => 'The contents field must be an array.',
            'contents.*.language_id.required' => 'The language ID is required.',
            'contents.*.language_id.integer' => 'The language ID must be an integer.',
            'contents.*.language_id.exists' => 'The selected language ID is invalid.',
            'contents.*.name.required' => 'The name field is required.',
            'contents.*.name.string' => 'The name field must be a string.',
            'contents.*.name.max' => 'The name field must not exceed 256 characters.',
            'contents.*.name.unique' => 'The name field must be unique.',
            'contents.*.course_duration.required' => 'The course duration field is required.',
            'contents.*.course_duration.string' => 'The course duration must be a string.',
            'contents.*.course_duration.max' => 'The course duration must not exceed 128 characters.',
            'contents.*.lectures_qty.required' => 'The number of lectures is required.',
            'contents.*.lectures_qty.integer' => 'The number of lectures must be an integer.',
            'contents.*.lectures_qty.min' => 'The number of lectures must be at least 0.',
            'contents.*.project_qty.required' => 'The number of projects is required.',
            'contents.*.project_qty.integer' => 'The number of projects must be an integer.',
            'contents.*.project_qty.min' => 'The number of projects must be at least 0.',
            'contents.*.description.required' => 'The description field is required.',
            'contents.*.description.string' => 'The description field must be a string.',
            'contents.*.description.max' => 'The description field must not exceed 1024 characters.'
        ];
    }
}
