<?php

namespace App\Controller;

use App\Model\Media;
use Flight;

class MediaController
{
    protected $media;

    public function __construct()
    {
        $this->media = new Media;
    }

    /**
     * Index
     *
     * @return \FLight
     */
    public function index()
    {
        $data = array(
            'title' => 'Media'
        );

        style(array(
            'media'
        ));

        script(array(
            'vue',
            'axios',
            'media.editor'
        ), array(
            'url' => array(
                'dirs' => '/media/dirs',
                'files' => '/media/files'
            )
        ));
        
        view('media.index', $data, 'content');

        return layout('app');
    }

    /**
     * Get dirs
     *
     * @return \Flight
     */
    public function dirs()
    {
        $files = $this->media->findDirList();

        return Flight::json(array(
            'data' => array(
                'files' => $files,
            ),
            'message' => 'get_dirs'
        ), 200);
    }

    /**
     * Create diretory
     *
     * @return void
     */
    public function createDir()
    {
        $req = post();

        if (empty($req['directory'])) {
            return Flight::json(array(
                'message' => 'directory_is_required'
            ), 422);
        }

        try {
            $this->media->createDir($req['directory']);
        } catch (\Exception $e) {
            return Flight::json(array(
                'message' => $e->getMessage()
            ), 500);
        }
    }

    /**
     * Delete directory
     *
     * @param string $name
     * 
     * @return \Flight
     */
    public function deleteDir($name)
    {
        if (empty($name)) {
            return Flight::json(array(
                'message' => 'directory_is_required'
            ), 422);
        }

        try {
            $this->media->deleteDir($name);

            return Flight::json(array(
                'message' => 'delete_success'
            ), 200);

        } catch (\MediaFileNotExistsException $e) {
            return Flight::json(array(
                'message' => $e->getMessage()
            ), 500);
        }
    }

    /**
     * Get file in directory
     *
     * @return \Flight
     */
    public function files()
    {
        $req = post();

        $files = $this->media->findFileList(!empty($req['directory']) ? $req['directory'] : 'root');

        return Flight::json(array(
            'data' => array(
                'files' => $files,
            ),
            'message' => 'get_files'
        ), 200);
    }
}