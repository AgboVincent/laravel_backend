<?php

namespace App\Http\Requests\Claim\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|min:1',
            'comment' => 'nullable|min:2'
        ];
    }
}
