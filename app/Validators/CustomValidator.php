<?php

namespace App\Validators;

use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{

    protected $myCustomMessages = [
        'password_match' => "Your :attribute is incorrect.",
        'lowercase_only' => "The :attribute field must be in lowercase only.",
    ];

    public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->setCustomMessages($this->myCustomMessages);
    }

    protected function validatePasswordMatch($attribute, $value, $parameters)
    {
        return app('hash')->check($value, current($parameters));
    }

    protected function validateLowercaseonly($attribute, $value, $parameters)
    {
        $lowercase = strtolower($value);

        return $value === $lowercase;
    }
}
