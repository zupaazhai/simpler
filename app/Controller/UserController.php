<?php

namespace App\Controller;

use App\Model\User;
use Flight;

class UserController
{
    public function __construct()
    {
        $this->user = new User;    
    }

    public function loginForm()
    {
        $user = $this->user->create(array(
            'username' => 'zupaazhai',
            'password' => 'p@ssw0rds',
            'email' => 'aaa@gmail.com',
            'token' => 'aaa'
        ));

        die;

        $data = array(
            'bodyClass' => 'login-page'
        );

        view('login', $data, 'content');

        return layout('app', array(), '');
    }
}