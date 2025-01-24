<?php

namespace App\Http\Requests\Seminar;

use Illuminate\Foundation\Http\FormRequest;

class SeminarRequest extends FormRequest
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
            'seminar_detail_id' => 'required|integer|exists:seminar_details,id',
            'course_id' => 'required|integer|exists:courses,id',
            'type' => 'required|integer|in:1,2',
            'location_id' => 'required_if:type,1|integer|nullable',
            'link' => 'required_if:type,2|string|nullable|max:255',
            'platform' => 'required_if:type,2|string|nullable|max:255',
            'datetime' => 'required',
            'seminar_type' => 'required|integer|in:1,2',
            'is_active' => 'required|boolean',
            'contents' => 'required|array|min:2',
            'contents.*.name' => 'required|string|max:255',
            'contents.*.language_id' => 'required|integer',
        ];
    }
}
