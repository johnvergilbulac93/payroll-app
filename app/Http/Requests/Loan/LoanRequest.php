<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Employee;
use Illuminate\Validation\Rule;

class LoanRequest extends FormRequest
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
            'EmpNbr' => ['required'],
            'LoanTypeID' => ['required'],
            'OrigBal' => ['required', 'numeric'],
            'DedAmt' => ['required', 'numeric'],
            'BalanceAmt' => ['required', 'numeric'],
            'StartDate' => ['required'],
            'Frequency' => ['nullable', 'string'],
            'BalanceasofDate' => ['nullable'],
        ];
    }
    public function messages()
    {
        return [
            'EmpNbr.required' => 'Employee name is required.',
            'LoanTypeID.required' => 'Loan type is required.',
            'OrigBal.required' => 'Original balance is required.',
            'DedAmt.required' => 'Deduction amount is required.',
            'BalanceAmt.required' => 'Balance amount is required.',
            'StartDate.required' => 'Start deduction date is required.',
        ];
    }
}
