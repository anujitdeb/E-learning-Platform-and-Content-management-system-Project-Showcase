<?php

namespace App\Http\Requests\Promotion;

use App\Rules\Base64ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class OfferNoticeRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => [
                $this->notice && $this->notice->id ? 'nullable' : 'required',
                'string',
                new Base64ImageValidation()
            ],
            'thumbnail' => [
                'nullable',
                'string',
                new Base64ImageValidation()
            ],
            'status' => 'required|integer|in:1,2,3',
            'end_date' => 'required|date',
        ];
    }
}
