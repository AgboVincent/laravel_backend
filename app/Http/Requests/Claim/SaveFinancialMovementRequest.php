<?php

namespace App\Http\Requests\Claim;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveFinancialMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|numeric',
            'nature' => 'required|string',
            'issuer' => 'nullable|string',
            'recipient' => 'nullable|string',
            'guarantees' => 'nullable|array',
            'amount' => 'required|numeric',
            'payment_method' => 'nullable|string',
        ];
    }
}
