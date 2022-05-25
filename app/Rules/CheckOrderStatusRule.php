<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckOrderStatusRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $types = ['N','C','P','NR1','NR2','NR3','CA'];

        return in_array($value,$types);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Merci de vérifier vos informations.';
    }
}
