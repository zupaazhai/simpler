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
        $data['created_at'] = time();
        $data['updated_at'] = time();

        $saveData = json_encode($data);
        $filepath = $this->dbPath . DS . $uid;

        file_put_contents($filepath, $saveData);

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
     * Update to db
     *
     * @param string $id
     * @param array $data
     * 
     * @return array
     */
    public function update($id, $data = array())
    {
        $file = $this->dbPath . DS . $id;

        if (!file_exists($file)) {
            return false;
        }

        $savedData = $this->findById($id);
        $savedData = array_merge($savedData, $data);
        $savedData['updated_at'] = time();

        $jsonData = json_encode($savedData);
        
        file_put_contents($file, $jsonData);

        return $this->findById($id);
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