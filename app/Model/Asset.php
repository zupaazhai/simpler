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
        'content',
        'created_at',
        'updated_at'
    );

    protected $db;

    protected $assetDir;

    protected $user;

    public function __construct()
    {
        $db = new DB;
        $this->db = $db->setName('assets');
        $this->assetDir = config('ASSET_DIR');
        $this->user = new User;
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

        $createdAsset = $this->db->create($saveAsset);

        $this->createFile($asset['name'], $asset['content']);

        return $createdAsset;
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
     * Find asset by id
     *
     * @param string $id
     * 
     * @return mixed
     */
    public function findById($id)
    {
        $asset = $this->db->findById($id);

        if (empty($asset)) {
            return false;
        }

        $asset['content'] = '';

        $assetFile = $this->assetDir . $asset['name'];

        if (file_exists($assetFile)) {
            $asset['content'] = file_get_contents($assetFile);
        }

        $asset['created_user'] = $this->user->findOne('id', $asset['created_user_id']);
        $asset['updated_user'] = $this->user->findOne('id', $asset['updated_user_id']);

        return $asset;
    }

    /**
     * Update asset content
     *
     * @param string $id
     * @param array $asset
     * 
     * @return array
     */
    public function update($id, $asset)
    {
        $saveAsset = array_only($asset, $this->fillable);
        
        $user = User::auth();
        $saveAsset['created_user_id'] = $user['id'];
        $saveAsset['updated_user_id'] = $user['id'];

        $file = $this->assetDir . $asset['name'];

        if (file_exists($file)) {
            $this->db->update($id, $saveAsset);
            file_put_contents($file, $saveAsset['content']);
        }

        return $saveAsset;
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
     * Delete asset by id
     *
     * @param string $id
     * 
     * @return boolean
     */
    public function delete($id)
    {
        $asset = $this->db->findById($id);

        if (!$asset) {
            return false;
        }

        $this->db->delete($id);
        $file = $this->assetDir . $asset['name'];

        if (!file_exists($file)) {
            return false;
        }

        unlink($file);

        return true;
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