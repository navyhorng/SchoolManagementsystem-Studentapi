<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $studentId = $this->route('id');
        $currentUserId = $studentId ? Student::query()->whereKey($studentId)->value('user_id') : null;

        return [
            'student_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'student_code')->ignore($studentId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($currentUserId),
            ],
            'user_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('students', 'user_id')->ignore($studentId),
            ],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'dob' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
