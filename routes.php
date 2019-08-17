<?php

use App\Controller\UserController;
use App\Controller\DashboardController;

Flight::route('GET /login', array(new UserController, 'loginForm'));
Flight::route('POST /login', array(new UserController, 'login'));
Flight::route('POST /new-user', array(new UserController, 'create'));
Flight::route('POST /logout', array(new UserController, 'logout'));

Flight::before(ADMIN_PREFIX, function (&$params, &$output) {
    $guestRoutes = array('login', 'new-user', 'logout');

    if (is_contains(Flight::request()->url, $guestRoutes) && UserController::loggedIn()) {
        Flight::redirect(CALLBACK_REDIRECT);
    }
});

Flight::map(ADMIN_PREFIX, function () {
    if (UserController::loggedIn()) {
        Flight::route('/dashboard', array(new DashboardController, 'index'));
    }
});

$adminPrefix = ADMIN_PREFIX;
Flight::$adminPrefix();
