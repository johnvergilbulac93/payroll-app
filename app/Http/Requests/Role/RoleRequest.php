<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use App\Models\Role;

use App\Models\Employee;
class RoleRequest extends FormRequest
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
            'Description' => ['required', Rule::unique(Role::class, 'Description')->ignore($this->public_id, 'public_id')],
            'isActive' => ['nullable', 'boolean']
        ];
    } 
}