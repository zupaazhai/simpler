<?php

namespace App\Exception;

class UserExistsException extends \Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct('Username already exists');
    }
}