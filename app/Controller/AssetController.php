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
        $assets = $this->asset->findAll();

        $data = array(
            'title' => 'Asset',
            'assets' => $assets
        );

        view('asset.index', $data, 'content');

        return layout('app');
    }
}