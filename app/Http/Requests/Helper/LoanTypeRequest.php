<?php

namespace App\Http\Requests\Helper;

use Illuminate\Foundation\Http\FormRequest;

class LoanTypeRequest extends FormRequest
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
            'LoanType' => 'required|unique:loan_types,LoanType',
            'route' => 'optional|string',
        ];
    }

    public function messages(): array
    {
        return [
            'LoanType.required' => 'Loan Type is required.',
            'LoanType.unique' => 'This Loan Type already exists.',
        ];
    }
}
