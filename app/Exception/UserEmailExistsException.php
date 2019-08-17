<?php

namespace App\Exception;

class UserEmailExistsException extends \Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct('Email already exists');
    }
}