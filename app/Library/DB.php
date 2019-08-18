<?php

namespace App\Library;

class DB
{
    protected $dbPath;

    protected $db;

    /**
     * Set name of database
     *
     * @param string $name
     * 
     * @return void
     */
    public function setName($name)
    {
        $dir = config('DATA_DIR') . $name;

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $this->dbPath = $dir;
    
        return $this;
    }

    /**
     * Create new
     *
     * @param array $data
     * 
     * @return array
     */
    public function create($data = array())
    {
        $saveData = json_encode($data);
        $uid = $this->uid();
        $filepath = $this->dbPath . DS . $uid; 
        $path = file_put_contents($filepath, $saveData);

        $data['id'] = $path;

        return $data;
    }

    public function all()
    {
        $files = scandir($this->dbPath);
        $result = array();

        foreach ($files as $file) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }

            $content = file_get_contents($this->dbPath . DS . $file);
            $result[] = json_decode($content, true);
        }

        return $result;
    }

    /**
     * Delete all
     *
     * @return void
     */
    public function deleteAll()
    {
        $files = scandir($this->dbPath);

        foreach ($files as $file) {

            if (in_array($file, array('.', '..'))) {
                continue;
            }

            $path = $this->dbPath . DS . $file;
            unlink($path);
        }
    }

    /**
     * Generate uid
     *
     * @return string
     */
    private function uid()
    {
        return time();
    }
}