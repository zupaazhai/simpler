<?php

namespace App\Controller;

use App\Model\Asset;

class AssetController
{
    protected $asset;

    public function __construct()
    {
        $this->asset = new Asset;    
    }
    /**
     * Index
     *
     * @return \Flight
     */
    public function index()
    {
        $asset = $this->asset->create(array(
            'name' => 'app.js',
            'type' => 'js',
            'position' => 'bottom',
            'content' => 'This is content'
        ));

        dump($asset);
        die;

        $data = array(
            'title' => 'Asset',
            'assets' => $assets
        );

        view('asset.index', $data, 'content');

        return layout('app');
    }
}