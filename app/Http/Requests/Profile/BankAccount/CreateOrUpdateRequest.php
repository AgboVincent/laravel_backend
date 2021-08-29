<?php

namespace App\Http\Requests\Profile\BankAccount;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank' => 'required',
            'number' => 'required',
            'name' => 'required'
        ];
    }
}
