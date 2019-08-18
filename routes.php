<?php

use App\Controller\AssetController;
use App\Controller\UserController;
use App\Controller\DashboardController;

Flight::route('GET /login', array(new UserController, 'loginForm'));
Flight::route('POST /login', array(new UserController, 'login'));
Flight::route('POST /new-user', array(new UserController, 'create'));
Flight::route('POST /logout', array(new UserController, 'logout'));

Flight::before(config('ADMIN_PREFIX'), function (&$params, &$output) {
    $guestRoutes = array('login', 'new-user', 'logout');

    if (is_contains(Flight::request()->url, $guestRoutes) && UserController::loggedIn()) {
        Flight::redirect(CALLBACK_REDIRECT);
    }
});

Flight::map(config('ADMIN_PREFIX'), function () {
    if (UserController::loggedIn()) {
        Flight::route('/dashboard', array(new DashboardController, 'index'));
        Flight::route('/user', array(new UserController, 'index'));
        Flight::route('GET /user/create', array(new UserController, 'createForm'));
        Flight::route('POST /user/create', array(new UserController, 'adminCreate'));
        Flight::route('GET /user/@id', array(new UserController, 'edit'));
        Flight::route('PUT /user/@id', array(new UserController, 'update'));
        Flight::route('DELETE /user/@id', array(new UserController, 'delete'));

        Flight::route('/asset', array(new AssetController, 'index'));
    }
});

$adminPrefix = config('ADMIN_PREFIX');
Flight::$adminPrefix();
