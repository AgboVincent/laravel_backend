<?php

namespace App\Http\Requests\Claim;

use App\Rules\UserVehicleExists;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // todo: accident type - what are they?
        $rules = [
            'vehicle_number' => ['required', new UserVehicleExists()],
            'date_time' => 'required|date|before:tomorrow',
            'accident_type' => 'required|exists:accident_types,id',
            'description' => 'required|min:5',
            'involved_third_party' => 'sometimes|nullable|bool',
            'documents' => 'required|array',
            'documents.pictures' => 'required|array',
            'documents.pictures.close_up' => 'required|exists:uploads,id',
            'documents.pictures.wide_angle' => 'required|exists:uploads,id',
            'documents.pictures.front' => 'required|exists:uploads,id',
            'documents.pictures.rear' => 'required|exists:uploads,id',
            'documents.pictures.video' => 'required|exists:uploads,id',
            'quotes' => 'required|array|min:1',
            'quotes.*.name' => 'required|min:2',
            'quotes.*.quantity' => 'required|numeric',
            'quotes.*.amount' => 'required|numeric',
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
