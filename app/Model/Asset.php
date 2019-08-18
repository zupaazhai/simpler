<?php

namespace App\Model;

use App\Exception\AssetFileExistException;
use App\Library\DB;

class Asset
{
    protected $fillable = array(
        'name',
        'type',
        'position',
        'created_at',
        'updated_at'
    );

    protected $db;

    protected $assetDir;

    public function __construct()
    {
        $db = new DB;
        $this->db = $db->setName('assets');
        $this->assetDir = config('ASSET_DIR');
    }

    /**
     * Create asset
     *
     * @param array $asset
     * 
     * @return array
     */
    public function create($asset)
    {
        $asset['created_at'] = time();
        $asset['updated_at'] = time();

        $saveAsset = array_only($asset, $this->fillable);

        if ($this->isFileExist($asset['name'])) {
            throw new AssetFileExistException();
        }

        $user = User::auth();
        $saveAsset['created_user_id'] = $user['id'];
        $saveAsset['updated_user_id'] = $user['id'];

        $this->db->create($saveAsset);

        $this->createFile($asset['name'], $asset['content']);

        return $asset;
    }

    /**
     * Find all user
     *
     * @return array
     */
    public function findAll()
    {
        $result = array();
        $assets = $this->db->all();

        foreach ($assets as $asset) {
            $file = $this->assetDir . $asset['name'];
            $content = file_exists($file) ? file_get_contents($file) : ''; 
            $asset['content'] = $content;
            $result[] = $asset;
        }

        return $result;
    }

    /**
     * Check is file exists
     *
     * @param string $name
     * 
     * @return boolean
     */
    public function isFileExist($name)
    {
        return file_exists($this->assetDir . $name);
    }

    /**
     * Create file
     *
     * @param string $name
     * @param string $content
     * 
     * @return void
     */
    public function createFile($name, $content)
    {
        if (!is_dir($this->assetDir)) {
            mkdir($this->assetDir, 0775, true);
        }

        $filename = $this->assetDir . $name;

        file_put_contents($filename, $content);
    }

    /**
     * Delele asset file all
     *
     * @return void
     */
    public function deleteAll()
    {
        $this->db->deleteAll();
        $files = scandir($this->assetDir);

        foreach ($files as $file) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }

            unlink($this->assetDir . $file);
        }
    }
}