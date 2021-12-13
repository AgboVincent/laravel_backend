<?php

namespace App\Http\Requests\Integrations\Baloon;

use App\Helpers\JWT;
use App\Helpers\Integrations\Baloon;
use Illuminate\Foundation\Http\FormRequest;

class ClaimURLRequest extends FormRequest
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
            'baloonSsoInfo.token' => 'required|string',
            'dossierContact.contactId' => 'required|numeric',
            'dossierContact.email' => 'sometimes|nullable|email',
            'dossierContact.telephone' => 'sometimes|nullable|string',
            'dossierContact.nomContact' => 'required|string',
            'dossierContact.risques' => 'required|array',
            'dossierContact.risques.*.identifiant' => 'required|string',
            'dossierContact.risques.*.designation' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Baloon::hasValidToken(JWT::decodePayload($this->input('baloonSsoInfo.token')))) {
                $validator->errors()->add('ssoInfoToken', 'The token in the SSO contains invalid user keys or data.');
            }
        });
    }
}
