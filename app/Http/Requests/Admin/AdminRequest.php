<?php

namespace App\Http\Requests\Admin;

use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'role_id' => 'required|integer',
            'employee_id' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => ['required', 'unique:admins' . ($this->admin ? ',email,' . $this->admin['id'] : '')],
            'number' => 'required|string',
            'image' => ['nullable', new Base64ImageValidation],
            'password' => [($this->admin ? 'nullable' : 'required'), 'min:8', 'same:password_confirmation'],
            'password_confirmation' => [($this->admin ? 'nullable' : 'required'), 'min:8'],
            'is_active' => 'nullable|boolean',
        ];
    }
}  