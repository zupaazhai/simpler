<?php

namespace App\Controller;

use App\Model\Media;

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

        $files = $this->media->findAllRecursive();
        $data = array();

        style(array(
            'media'
        ));

        script(array(
            'vue',
            'media.editor'
        ), array('files' => $files));
        
        view('media.index', $data, 'content');

        return layout('app');
    }
}