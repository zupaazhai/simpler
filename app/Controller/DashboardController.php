<?php

namespace App\Controller;

class DashboardController
{
    public function index()
    {
        $data = array(
            'title' => 'Dashboard'
        );

        view('dashboard.index', $data, 'content');

        return layout('app', array(), '');
    }
}