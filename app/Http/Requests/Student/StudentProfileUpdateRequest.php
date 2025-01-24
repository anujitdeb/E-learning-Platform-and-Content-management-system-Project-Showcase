<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentProfileUpdateRequest extends FormRequest
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
            'gender' => 'required|in:Male,Female,Other',
            'alternative_number' => 'required|string|max:15|min:9',
            'guardian_phone' => 'required|string|max:15|min:9',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'nid' => 'nullable|string',
            'br_id' => 'nullable|string',
            'dob' => 'required|string',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'religion' => 'required|in:Islam,Hindu,Christian,Buddhist',
            'extracurricular_activities' => 'nullable|string',
            'occupation' => 'nullable|string',
            'is_tribal' => 'required|integer|in:1,2',
            'tribal_area' => 'required_if:is_tribal,1|nullable|string',
            'is_disability' => 'required|integer|in:1,2',
            'interest_club' => 'nullable|integer',
            'nationality' => 'nullable|string',
            'present_address' => 'required|string',
            'permanent_address' => 'required|string',
            'present_division_id' => 'required_if:country_status,1|nullable|integer',
            'present_district_id' => 'required_if:country_status,1|nullable|integer',
            'present_upazila_id' => 'nullable|integer',
            'permanent_division_id' => 'required|integer',
            'permanent_district_id' => 'required|integer',
            'permanent_upazila_id' => 'nullable|integer',
            'country_status' => 'nullable|integer|in:1,2',
            'know_about_us' => 'required|integer',
            'is_update' => 'boolean',
            'education_degree_id' => 'nullable|integer',
            'education_board_id' => ($this->education_degree_id ? 'required' : 'nullable') . '|integer',
            'institute' => ($this->education_degree_id ? 'required' : 'nullable') . '|string',
            'group' => ($this->education_degree_id ? 'required' : 'nullable') . '|string',
            'gpa' => 'required_if:education_status,2|nullable',
            'year_of_passing' => ($this->education_degree_id ? 'required' : 'nullable') . '|integer',
            'education_status' => ($this->education_degree_id ? 'required' : 'nullable') . '|integer',
        ];
    }
}
