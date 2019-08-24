<?php

namespace App\Enum;

class MediaEnum
{
    const DIR = 'dir';

    const FILE = 'file';
    
    const ROOT = 'root';

    /**
     * Allowed mimes
     *
     * @return array
     */
    public static function allowedMimes()
    {
        return array(
            'image/jpeg' => 'jpg',
            'image/pjpeg' => 'jpeg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'video/mp4' => 'mp4',
            'audio/mpeg' => 'mp3',
            'application/zip' => 'zip',
            'application/x-compressed' => 'zip',
            'application/x-gzip' => 'zip'
        );
    }

    /**
     * Get allowed mimes
     *
     * @return array
     */
    public static function getAllowedMimes()
    {
        $mimes = self::allowedMimes();

        return array_keys($mimes);
    }

    /**
     * Get allowed file extension
     *
     * @return array
     */
    public static function getFileExtension()
    {
        $mimes = self::allowedMimes();

        return array_unique(array_values($mimes));
    }
}