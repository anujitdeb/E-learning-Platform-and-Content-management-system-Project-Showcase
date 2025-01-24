<?php

namespace App\Http\Requests\Apps;

use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'whats_app_link' => 'required|string',
            'messenger_link' => 'required|string',
            'hotline_number' => 'required|string|min:11',
            'icon' => ['nullable', new Base64ImageValidation],
            'contents' => 'required|array|min:2',
            'contents.*.language_id' => 'required|integer',
            'contents.*.title' => 'required|string|max:255',
            'contents.*.description' => 'required|string',
            'contents.*.btn_name' => 'required|string|max:255'
        ];
    }
    public function messages(): array
    {
        return [
            'contents.*.language_id.required' => 'The language ID is required.',
            'contents.*.language_id.integer' => 'The language ID must be a valid integer.',
            'contents.*.title.required' => 'The title is required.',
            'contents.*.title.string' => 'The title must be a string.',
            'contents.*.title.max' => 'The title may not be greater than 255 characters.',
            'contents.*.description.required' => 'The description is required.',
            'contents.*.description.string' => 'The description must be a string.',
            'contents.*.description.max' => 'The description may not be greater than 2048 characters.',
            'contents.*.btn_name.required' => 'The button name is required.',
            'contents.*.btn_name.string' => 'The button name must be a string.',
            'contents.*.btn_name.max' => 'The button name may not be greater than 255 characters.',
        ];
    }
}
