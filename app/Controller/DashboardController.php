<?php

namespace App\Controller;

class DashboardController extends Controller
{   
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
                    'count' => 0,
                    'icon' => 'fas fa-photo-video',
                    'bg' => 'bg-primary',
                    'route' => '/media'
                ),
                array(
                    'title' => 'Asset',
                    'count' => 0,
                    'icon' => 'fas fa-copy',
                    'bg' => 'bg-green',
                    'route' => '/asset'
                ),
                array(
                    'title' => 'User',
                    'count' => 0,
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