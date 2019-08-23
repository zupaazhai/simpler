<?php

namespace App\Exception;

class MediaFileNotExistsException extends \Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct('Media file or folder already exists');
    }
}