<?php

namespace App\Model;

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
        $this->assetDir = ASSET_DIR;

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
        $this->db->create($saveAsset);

        $this->createFile($asset['name'], $asset['content']);
    }

    public function createFile($name, $content)
    {
        if (!is_dir($this->assetDir)) {
            mkdir($this->assetDir, 0775, true);
        }

        $filename = $this->assetDir . $name;

        file_put_contents($filename, $content);
    }

    public function findAll()
    {
    }
}