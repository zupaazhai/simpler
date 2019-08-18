<?php

namespace App\Exception;

class AssetFileExistException extends \Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct('Asset file already exists');
    }
}