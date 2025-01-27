<?php

namespace App\Http\Requests\Settings;

use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:languages,name,' . optional($this->language)->id,
            'icon' => ['nullable', new Base64ImageValidation],
            'code' => 'required',
            'is_active' => 'required|boolean'
        ];
    }
}
