<?php

namespace App\Enum;

class AssetEnum
{
    public static $types = array(
        'js' => 'JS',
        'css' => 'CSS'
    );

    public static $positions = array(
        'bottom' => 'End of body',
        'top' => 'In header'
    );

    public static $sources = array(
        'file' => 'file',
        'cdn' => 'cdn'
    );
}