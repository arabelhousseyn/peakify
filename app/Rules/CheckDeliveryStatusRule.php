<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckDeliveryStatusRule implements Rule
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
        $types = ['N','T','S','P','SH','R','D','CA','NR','TR','OUT','UN','REP','REF','BA'];

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
