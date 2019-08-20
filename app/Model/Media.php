<?php

namespace App\Model;

use App\Enum\MediaEnum;

class Media
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = config('MEDIA_DIR');
    }

    /**
     * Find all
     * 
     * @param string $path
     *
     * @return array
     */
    public function findAll($path = 'root')
    {
        $result = array();
        $dir = $this->basePath . ($path == MediaEnum::ROOT ? '' : $path);
        $files = scandir($dir);

        foreach ($files as $file) {

            if (in_array($file, array('.', '..', 'index.php'))) {
                continue;
            }

            $result[] = array(
                'name' => $file,
                'type' => is_dir($this->basePath . $file) ? MediaEnum::DIR : MediaEnum::FILE
            );
        }

        return $result;
    }

    /**
     * Find file recursive
     *
     * @param string $path
     * 
     * @return array
     */
    public function findAllRecursive($path = '')
    {
        $result = array(
            '/' => array(
                'name' => '/',
                'type' => 'dir',
                'is_active' => true,
                'children' => $this->findFileList()
            )
        );
        $dir = empty($path) ? ($this->basePath . $path) : $path;

        if (!is_dir($dir)) {
            return $result;
        } 

        $files = glob($dir . '*', GLOB_MARK);
            
        foreach($files as $file) {
            $filename = basename($file);

            if (in_array($filename, array('index.php', '.htaccess'))) {
                continue;
            }

            if (is_dir($file)) {
                $result[$filename] = array(
                    'name' => $filename,
                    'type' => 'dir',
                    'is_active' => false,
                    'children' => $this->findAllRecursive( $file )
                );

                continue;
            }

            $result[$filename] = array(
                'name' => $filename,
                'type' => 'file',
                'is_active' => false,
                'children' => false
            );
        }

        return $result;
    }  

    /**
     * List only dir
     *
     * @return array
     */
    public function findDirList()
    {
        $files = $this->findAll();

        return array_filter($files, function ($file) {
            return $file['type']  == MediaEnum::DIR;
        });
    }

    /**
     * Find file list
     *
     * @param string $path
     * 
     * @return array
     */
    public function findFileList($path = 'root')
    {
        $files = $this->findAll($path);

        return array_filter($files, function ($file) {
            return $file['type']  == MediaEnum::FILE;
        });
    }
}