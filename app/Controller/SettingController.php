<?php

namespace App\Controller;

class SettingController
{
    public function __construct()
    {
        
    }

    /**
     * Setting index
     *
     * @return \Flight
     */
    public function index()
    {
        $data = array(
            'title' => 'Setting'
        );

        view('setting.index', $data, 'content');

        return layout('app');
    } 
}