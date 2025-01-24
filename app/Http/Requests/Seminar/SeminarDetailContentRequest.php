<?php

namespace App\Http\Requests\Seminar;

use Illuminate\Foundation\Http\FormRequest;

class SeminarDetailContentRequest extends FormRequest
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
            'seminar_detail_contents' => 'required|array|min:2',
            'seminar_detail_contents.*.language_id' => 'required|integer',
            'seminar_detail_contents.*.contents' => 'required|array',
            'seminar_detail_contents.*.contents.*' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'seminar_detail_contents.*.language_id.required' => 'The language ID is required.',
            'seminar_detail_contents.*.language_id.integer' => 'The language ID must be an integer.',
            'seminar_detail_contents.*.contents.required' => 'The contents field is required.',
            'seminar_detail_contents.*.contents.array' => 'The contents field is must be an array.',
            'seminar_detail_contents.*.contents.*' => 'The content is required.',
        ];
    }
}
