<?php

namespace App\Http\Requests\Seminar;
use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class SeminarDetailRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:seminar_details,title,'. optional($this->detail)->id,
            'course_id' => 'required|integer',
            'video_id' => 'nullable|integer',
            'thumbnail' => [($this->route('detail') ? 'nullable' : 'required'), new Base64ImageValidation],
            'status' => 'required|integer|in:1,2',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'Title already exists', 
            'status.in' => 'Status must be in 1 or 2',
        ];
    }
}
