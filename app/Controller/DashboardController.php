<?php

namespace App\Controller;

use App\Model\Media;
use App\Model\Asset;
use App\Model\User;

class DashboardController extends Controller
{   
    protected $media;

    protected $asset;

    protected $user;

    public function __construct()
    {
        $this->media = new Media;
        $this->asset = new Asset;
        $this->user = new User;
    }

    /**
     * Dashboard index
     *
     * @return \Flight
     */
    public function index()
    {
        $data = array(
            'topBlocks' => array(
                array(
                    'title' => 'Page',
                    'count' => 0,
                    'icon' => 'fas fa-file',
                    'bg' => 'bg-info',
                    'route' => ''
                ),
                array(
                    'title' => 'Media',
                    'count' => $this->media->count(),
                    'icon' => 'fas fa-photo-video',
                    'bg' => 'bg-primary',
                    'route' => '/media'
                ),
                array(
                    'title' => 'Asset',
                    'count' => $this->asset->count(),
                    'icon' => 'fas fa-copy',
                    'bg' => 'bg-green',
                    'route' => '/asset'
                ),
                array(
                    'title' => 'User',
                    'count' => $this->user->count(),
                    'icon' => 'fas fa-users',
                    'bg' => 'bg-danger',
                    'route' => '/user'
                )
            ),
            'title' => 'Dashboard'
        );

        view('dashboard.index', $data, 'content');

        return layout('app', array(), '');
    }
}