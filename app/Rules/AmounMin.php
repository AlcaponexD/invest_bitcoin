<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;

/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 27/11/2020
 * Time: 00:04
 */
class AmounMin implements Rule
{
    public function passes($attribute, $value)
    {
        return $value > 10 ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute have an minimum value more than 10.';
    }

}