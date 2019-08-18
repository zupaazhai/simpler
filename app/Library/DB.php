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
        $uid = $this->uid();
        $data['id'] = $uid;
        $saveData = json_encode($data);
        $filepath = $this->dbPath . DS . $uid; 
        $path = file_put_contents($filepath, $saveData);

        return $data;
    }

    /**
     * Get all
     *
     * @return array
     */
    public function all()
    {
        $files = scandir($this->dbPath);
        $result = array();

        foreach ($files as $file) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }

            $content = file_get_contents($this->dbPath . DS . $file);
            $content = json_decode($content, true);
            $result[] = $content;
        }

        return $result;
    }

    /**
     * Find by id
     *
     * @param string $id
     * 
     * @return mixed
     */
    public function findById($id)
    {
        $file = $this->dbPath . DS . $id;

        if (!file_exists($file)) {
            return false;
        }

        $content = file_get_contents($file);
        $content = json_decode($content, true);

        return $content;
    }

    /**
     * Delete by id
     *
     * @param string $id
     * 
     * @return void
     */
    public function delete($id)
    {
        $path = $this->dbPath . DS . $id;

        if (file_exists($path)) {
            unlink($path);
        }
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