<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRepairRequestRequest extends FormRequest
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
            'client_name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+?([0-9]{1,3})?[\s.-]?[0-9]{6,14}$/|max:20',
            'address' => 'required|string|max:500',
            'problem_text' => 'required|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_name.required' => 'Client name is required.',
            'client_name.string' => 'Client name must be a string.',
            'client_name.max' => 'Client name cannot exceed 255 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number format is invalid.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'problem_text.required' => 'Problem description is required.',
            'problem_text.string' => 'Problem description must be a string.',
            'problem_text.max' => 'Problem description cannot exceed 2000 characters.',
        ];
    }
}
