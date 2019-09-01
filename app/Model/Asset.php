<?php

namespace App\Model;

use App\Enum\AssetEnum;
use App\Exception\AssetFileExistException;
use App\Library\DB;

class Asset
{
    protected $fillable = array(
        'name',
        'type',
        'position',
        'content',
        'source',
        'url',
        'order'
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
        $saveAsset = array_only($asset, $this->fillable);
        $isFile = $asset['source'] == AssetEnum::$sources['file'];

        if ($isFile) {
            $asset['name'] = $this->parseFileName($asset['name']);
        } else {
            $asset['name'] = 'file.cdn';
        }
        
        if ($isFile && $this->isFileExist($asset['name'])) {
            throw new AssetFileExistException();
        }

        $user = User::auth();
        $saveAsset['name'] = $asset['name'];
        $saveAsset['created_user_id'] = $user['id'];
        $saveAsset['updated_user_id'] = $user['id'];
        $saveAsset['source'] = empty($asset['source']) ? AssetEnum::$sources['file'] : $asset['source'];
        $saveAsset['url'] = empty($asset['url']) ? '' : $asset['url'];
        $saveAsset['order'] = $this->findLatestOrder($saveAsset['position']);

        $createdAsset = $this->db->create($saveAsset);

        if ($isFile) {
            $this->createFile($asset['name'], $asset['content']);
        }

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
            $asset['created_user'] = $this->user->findOne('id', $asset['created_user_id']);
            $asset['updated_user'] = $this->user->findOne('id', $asset['updated_user_id']);
            $result[] = $asset;
        }

        return $result;
    }

    /**
     * Find all and group with position
     *
     * @return array
     */
    public function findAllAndGroup()
    {
        $assets = $this->findAll();
        $tops = array_filter($assets, function ($asset) {
            return $asset['position'] == 'top';
        });
        $bottoms = array_filter($assets, function ($asset) {
            return $asset['position'] == 'bottom';
        });

        return array(
            'top' => sort_assoc_array($tops, 'order'),
            'bottom' => sort_assoc_array($bottoms, 'order')
        );
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
        $savedAsset = $this->findById($id);
        $isFile = $savedAsset['source'] == AssetEnum::$sources['file'];

        if (empty($asset['name'])) {
            $asset['name'] = $savedAsset['name'];
        }

        if (empty($asset['content'])) {
            $asset['content'] = $savedAsset['content'];
        }

        if (empty($asset['position'])) {
            $asset['position'] = $savedAsset['position'];
        }

        if (!$isFile) {
            $asset['name'] = 'file.cdn';
        }

        if ($savedAsset['name'] != $asset['name'] && $isFile) {
            $file = $this->assetDir . $asset['name'];
            
            if (file_exists($file)) {
                throw new AssetFileExistException();
            }

            unlink($this->assetDir . $savedAsset['name']);
        }

        $saveAsset = array_only($asset, $this->fillable);
        
        $user = User::auth();
        $saveAsset['name'] = $this->parseFileName($saveAsset['name']);
        $saveAsset['updated_user_id'] = $user['id'];

        $file = $this->assetDir . $asset['name'];
        $this->db->update($id, $saveAsset);

        if ($isFile) {
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
        $isFile = $asset['source'] == AssetEnum::$sources['file'];

        if (!$asset) {
            return false;
        }

        $this->db->delete($id);
        $file = $this->assetDir . $asset['name'];

        if ($isFile && !file_exists($file)) {
            return false;
        }

        if ($isFile) {
            unlink($file);
        }

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
            if (in_array($file, array('.', '..', 'index.php', '.htaccess'))) {
                continue;
            }

            unlink($this->assetDir . $file);
        }
    }

    /**
     * Parse file name
     *
     * @param string $filename
     * 
     * @return string
     */
    private function parseFileName($filename)
    {
        $fileinfo = pathinfo($filename);
        $ext = '.' . $fileinfo['extension'];

        if (!in_array($fileinfo['extension'], array('js', 'css'))) {
            $ext = '';
        }

        $filename = str_replace('.', '-', $fileinfo['filename']);

        return $filename . $ext;
    }
    
    /**
     * Find latest order
     *
     * @param string $position
     * 
     * @return int
     */
    private function findLatestOrder($position)
    {
        $assets = $this->findAll();
        $latestId = 1;
        $orders = array();

        foreach ($assets as $asset) {
            if ($asset['position'] !== $position) {
                continue;
            }

            $orders[] = $asset['order'];
        }

        if (empty($orders)) {
            return $latestId;
        }

        $latestId = max($orders);
        ++$latestId;

        return $latestId;
    }

    /**
     * Sort asset item
     *
     * @param array $data
     * 
     * @return void
     */
    public function sort($data)
    {
        $order = 1;

        foreach ($data as $position => $assetIds) {
            foreach ($assetIds as $assetId) {
                $asset = array(
                    'id' => $assetId,
                    'order' => $order,
                    'position' => $position
                );
                ++$order;

                $this->update($assetId, $asset);
            }
            $order = 1;
        }
    }
}