<?php

namespace App\Controller;

class DashboardController extends Controller
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