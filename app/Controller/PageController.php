<?php

namespace App\Controller;

class PageController
{
    public function __construct()
    {
    }

    /**
     * List of page
     *
     * @return \Flight
     */
    public function index()
    {
        $data = array(
            'title' => 'Page'
        );

        view('page.index', $data, 'content');
        
        style(array(
            'page'
        ));

        script(array(
            'vue',
            'vue-validate',
            'page.index'
        ));
        return layout('app');
    }
}