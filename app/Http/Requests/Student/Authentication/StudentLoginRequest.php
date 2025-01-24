<?php

namespace App\Http\Requests\Student\Authentication;

use App\Rules\EmailOrNumberValidation;
use Illuminate\Foundation\Http\FormRequest;

class StudentLoginRequest extends FormRequest
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
            "email_or_number" => ['required', 'string', new EmailOrNumberValidation()],
            'password' => 'required|string'
        ];
    }
}
