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

            $filePath =  $this->basePath . (empty($path) || $path == 'root' ? '' : ($path . DS)) . $file;
            $isDir = is_dir($filePath);
            $result[] = array(
                'name' => $file,
                'type' => $isDir ? MediaEnum::DIR : MediaEnum::FILE,
                'is_active' => false,
                'is_image' => is_image($filePath),
                'size' => $isDir ? 0 : size_readable(filesize($filePath)),
                'url' => config('MEDIA_URL') . (empty($path) || $path == 'root' ? '' : ($path . '/')) . rawurlencode($file)
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
     * Count all
     *
     * @param string $path
     * 
     * @return int
     */
    public function count($path = '')
    {
        $total = 0;
        $dir = empty($path) ? $this->basePath : $path;
        $files = scandir($dir);

        foreach ($files as $file) {

            if (in_array($file, array('.', '..', 'index.php'))) {
                continue;
            }

            if (is_dir($dir . $file)) {
                $total += $this->count($dir . $file);
                continue;
            }
            
            $total++;
        }

        return $total;
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

    /**
     * Upload file
     *
     * @param string $dir
     * @param array $file
     * 
     * @return bool
     */
    public function uploadFile($dir, $file)
    {
        $dir = empty($dir) ? DS : ($dir . DS);
        $dir = $this->basePath . $dir;
        $filename = $dir . $file['name'];

        return move_uploaded_file($file['tmp_name'], $filename);
    }

    /**
     * Delete file
     *
     * @param string $dir
     * @param string $file
     * 
     * @return boolean
     */
    public function deleteFile($dir, $file)
    {
        $filePath = $this->basePath . (empty($dir) ? '' : ($dir . DS)) . $file;

        if (!file_exists($filePath)) {
            throw new MediaFileNotExistsException();
        }

        unlink($filePath);

        return true;
    }
}