<?php

namespace App\Exceptions;

use Exception;

class AuthPermissionDeniedException extends Exception
{
    /**
     * @param  string  $message
     * 
     * @return void
     */
    public function __construct($message = 'Permission Denied')
    {
        parent::__construct($message);
    }
}
