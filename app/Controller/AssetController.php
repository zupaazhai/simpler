<?php

namespace App\Controller;

use App\Enum\AssetEnum;
use App\Exception\AssetFileExistException;
use App\Model\Asset;
use Flight;

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
        $assets = $this->asset->findAllAndGroup();

        $data = array(
            'title' => 'Asset',
            'assets' => $assets,
            'sources' => AssetEnum::$sources,
            'positions' => AssetEnum::$positions
        );

        style(array(
            'asset'
        ));

        script(array(
            'vue',
            'vue-validate', 
            'axios',
            'jquery-ui',
            'asset.index'
        ), array(
            'url' => array(
                'sort' => '/asset/sort'
            )
        ));

        view('asset.index', $data, 'content');

        return layout('app');
    }

    /**
     * Create asset file
     *
     * @return \Flight
     */
    public function save()
    {
        $req = Flight::request()->data->getData();

        try {
            $req['content'] = '';
            $asset = $this->asset->create($req);

            flash('status', array(
                'status' => 'success',
                'message' => 'Create asset file success'
            ));

            return Flight::json(array(
                'data' => $asset,
                'message' => 'create_asset_success'
            ));

        } catch (AssetFileExistException $e) {

            return Flight::json(array(
                'data' => array(),
                'message' => 'asset_file_exists'
            ), 500);
        }
    }

    /**
     * Edit asset file
     *
     * @param string $id
     * 
     * @return \Flight
     */
    public function edit($id)
    {
        $asset = $this->asset->findById($id);

        if (!$asset) {
            return Flight::redirect('/asset');
        }

        $data = array(
            'asset' => $asset,
            'title' => 'Edit ' . $asset['name'], 
            'types' => AssetEnum::$types,
            'positions' => AssetEnum::$positions,
            'sources' => AssetEnum::$sources
        );

        style(array('codemirror', 'codemirror-theme'));
        
        $jsData = array('type' => $asset['type']);
        script(array(
            'vue', 
            'codemirror', 
            'codemirror-javascript', 
            'codemirror-css', 
            'asset.edit'
        ), $jsData);

        view('asset.edit', $data, 'content');

        return layout('app');
    }

    /**
     * Update asset
     *
     * @param string $id
     * 
     * @return \Flight
     */
    public function update($id)
    {
        $req = put();

        try {

            $result = $this->asset->update($id, $req);

        } catch (AssetFileExistException $e) {

            flash('status', array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));

            return back();
        }


        if (!$result) {
            flash('status', array(
                'status' => 'error',
                'message' => 'Update asset fail'
            ));

            return back();
        }

        flash('status', array(
            'status' => 'success',
            'message' => 'Update asset success'
        ));

        return back();
    }

    /**
     * Delete asset file
     *
     * @param string $id
     * 
     * @return \Flight
     */
    public function delete($id)
    {
        $result = $this->asset->delete($id);
        $status = array(
            'status' => 'success',
            'message' => 'Delete asset success'
        );

        if (!$result) {
            $status['status'] = 'error';
            $status['message'] = 'Delete asset fail';
        }

        flash('status', $status);

        return back();
    }

    /**
     * Sort asset item
     *
     * @return \Flight
     */
    public function sort()
    {
        $req = post();

        $this->asset->sort($req);

        return Flight::json(array(
            'message' => 'sorted'
        ));
    }
}