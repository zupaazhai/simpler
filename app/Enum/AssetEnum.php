<?php

namespace App\Enum;

class AssetEnum
{
    public static $types = array(
        'js' => 'JS',
        'css' => 'CSS/Font'
    );

    public static $positions = array(
        'top' => 'In header',
        'bottom' => 'End of body'
    );

    public static $sources = array(
        'file' => 'file',
        'cdn' => 'cdn'
    );
}