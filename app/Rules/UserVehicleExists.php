<?php

namespace App\Rules;

use App\Helpers\Auth;
use Illuminate\Contracts\Validation\Rule;

class UserVehicleExists implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return Auth::user()->vehicles()->where('registration_number', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Provided vehicle registration number does not exist.';
    }
}
