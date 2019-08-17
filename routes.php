<?php

use App\Controller\UserController;
use App\Controller\DashboardController;

Flight::route('GET /login', array(new UserController, 'loginForm'));
Flight::route('POST /login', array(new UserController, 'login'));
Flight::route('POST /new-user', array(new UserController, 'create'));

Flight::map('admin', function () {
    Flight::route('/dashboard', array(new DashboardController, 'index'));
});
Flight::admin();
