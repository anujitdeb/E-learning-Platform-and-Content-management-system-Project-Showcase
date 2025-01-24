<?php

namespace App\Http\Requests\ContentManagement;

use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
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
            'image' => [
                $this->about && $this->about->id ? 'nullable' : 'required',
                'string',
                new Base64ImageValidation()
            ],
            'is_active' => 'boolean|required',
            'contents' => 'required|array',
            'contents.*.language_id' => 'required|integer',
            'contents.*.title' => 'required|string|max:255',
            'contents.*.description' => 'required|string|max:2048'
        ];
    }
}
