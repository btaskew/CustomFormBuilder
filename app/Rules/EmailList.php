<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailList implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach (explode(";", $value) as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Emails must be a list of valid emails separated by semi-colons with no spaces';
    }
}
