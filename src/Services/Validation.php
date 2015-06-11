<?php

namespace jorenvanhocht\Blogify\Services;

use Hash;
use Auth;
use Illuminate\Validation\Validator;

class Validation extends Validator
{

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateAuthUserPass($attribute, $value, $parameters)
    {
        $passCheck = Hash::check(
            $value,
            Auth::user()->getAuthPassword()
        );

        if (!$passCheck) {
            return false;
        }

        return true;
    }

}