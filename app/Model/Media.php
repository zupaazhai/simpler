<?php

namespace App\Model;

use App\Enum\MediaEnum;
use App\Exception\MediaFileExistException;
use App\Exception\MediaFileNotExistsException;

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
                'type' => is_dir($this->basePath . $file) ? MediaEnum::DIR : MediaEnum::FILE,
                'is_active' => false
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
        $result = array(
            array(
                'name' => '/',
                'type' => MediaEnum::DIR,
                'is_active' => true
            )
        );

        foreach ($files as $file) {
            if ($file['type']  !== MediaEnum::DIR) {
                continue;
            }

            $result[] = $file;
        }

        return $result;
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
        $result = array();

        foreach ($files as $file) {
            if ($file['type']  !== MediaEnum::FILE) {
                continue;
            }

            $result[] = $file;
        }

        return $result;
    }

    /**
     * Create new directory
     *
     * @param string $name
     * 
     * @return string
     */
    public function createDir($name)
    {
        $name = slugify($name);
        $path = $this->basePath . $name;

        if (is_dir($path) || file_exists($path)) {
            throw new MediaFileExistException();
        }

        mkdir($path);

        return $name;
    }
    
    /**
     * Delete dir
     *
     * @param string $name
     * 
     * @return void
     */
    public function deleteDir($name)
    {
        $dir = $this->basePath . $name;

        if (!is_dir($dir) || in_array($name, array('/', '\\'))) {
            throw new MediaFileNotExistsException();
            return;
        } 
        
        $files = scandir($dir);

        foreach ($files as $file) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }

            $file = $dir . DS . $file;
            unlink($file);
        }

        rmdir($dir);
    }

}