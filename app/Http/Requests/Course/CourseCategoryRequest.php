<?php

namespace App\Http\Requests\Course;

use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class CourseCategoryRequest extends FormRequest
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
            'icon' => $this->route('category') ? 'nullable' : ['required', new Base64ImageValidation],
            'is_active' => 'boolean|required',
            'contents' => 'required|array',
            'contents.*.language_id' => 'required|integer',
            'contents.*.name' => 'required|string|max:255',
        ];
    }
}
