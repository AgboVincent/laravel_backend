<?php

namespace App\Http\Requests\Profile;

use App\Rules\ValidOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', new ValidOldPassword()],
            'new_password' => 'required|confirmed|min:6'
        ];
    }
}
