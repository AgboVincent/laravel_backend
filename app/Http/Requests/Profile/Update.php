<?php

namespace App\Http\Requests\Profile;

use App\Helpers\Auth;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'nullable',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id
        ];
    }
}
