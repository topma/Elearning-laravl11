<?php

namespace App\Http\Requests\Backend\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'course_id' => 'required|integer',
            // 'instructor_id' => 'required|integer',
            'code' => 'required|string',
            'discount' => 'required|integer',
            'valid_from' => 'required',
            'valid_until' => 'required',
        ];
    }
}
