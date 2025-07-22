<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strlen($value) >= 8
            && preg_match('/[a-z]/', $value) // pelo menos uma letra minúscula
            && preg_match('/[A-Z]/', $value) // pelo menos uma letra maiúscula
            && preg_match('/[0-9]/', $value) // pelo menos um número
            && preg_match('/[@$!%*?&]/', $value); // pelo menos um caractere especial
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A senha deve ter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos (@$!%*?&).';
    }
}
