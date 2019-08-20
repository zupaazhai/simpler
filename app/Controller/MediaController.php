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

        view('media.index', $data, 'content');

        return layout('app');
    }
}