<?php

namespace App\Http\Requests\Claim;

use Illuminate\Foundation\Http\FormRequest;

class ModifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'date_time' => 'required|date|before:tomorrow',
            'accident_type' => 'required|exists:accident_types,id',
            'description' => 'required|min:5',
            'involved_third_party' => 'sometimes|nullable|bool'
        ];

        if ($this->get('involved_third_party', false)) {
            return array_merge($rules, [
                'third_party' => 'required|array',
                'third_party.full_name' => 'required|min:2',
                'third_party.mobile' => 'required',
                'third_party.company' => 'required',
                'third_party.policy_number' => 'required'
            ]);
        }

        return $rules;
    }
}
