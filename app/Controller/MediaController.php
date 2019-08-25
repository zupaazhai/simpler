<?php

namespace App\Controller;

use App\Enum\MediaEnum;
use App\Exception\MediaFileNotExistsException;
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
            'title' => 'Media',
            'maxFileUpload' => ini_get('upload_max_filesize'),
            'postMaxSize' => ini_get('post_max_size')
        );

        style(array(
            'media'
        ));

        script(array(
            'vue',
            'axios',
            'jquery-form',
            'media.editor'
        ), array(
            'url' => array(
                'dirs' => '/media/dirs',
                'files' => '/media/files',
                'uploadFile' => '/media/upload-files'
            ),
            'allowedMimes' => MediaEnum::getAllowedMimes(),
            'fileSize' => $data['maxFileUpload']
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

    /**
     * Upload new file
     *
     * @return \Flight
     */
    public function uploadFile()
    {
        $req = post();
        $file = Flight::request()->files->file;

        $maxFileUpload = convert_file_mb_size(ini_get('upload_max_filesize'));

        if ($file['size'] > $maxFileUpload) {
            return Flight::json(array(
                'message' => 'File size invalid'
            ), 500);    
        }
        
        if (!in_array($file['type'], MediaEnum::getAllowedMimes())) {
            return Flight::json(array(
                'message' => 'File type invalid'
            ), 500);    
        }

        $this->media->uploadFile($req['directory'], $file);

        return Flight::json(array(
            'message' => 'Upload file success'
        ), 200);
    }

    /**
     * Delete file
     *
     * @return \Flight
     */
    public function deleteFile()
    {
        $req = json_decode(get_php_input(), true);

        try {

            $this->media->deleteFile($req['directory'], $req['filename']);

            return Flight::json(array(
                'message' => 'delete_file_success'
            ), 200);

        } catch (MediaFileNotExistsException $e) {

            return Flight::json(array(
                'message' => $e->getMessage()
            ), 500);
        }
    }
}