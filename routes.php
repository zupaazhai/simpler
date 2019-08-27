<?php

use App\Controller\AssetController;
use App\Controller\UserController;
use App\Controller\DashboardController;
use App\Controller\MediaController;
use App\Controller\SettingController;

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
        Flight::route('POST /asset/create', array(new AssetController, 'save'));
        Flight::route('GET /asset/@id', array(new AssetController, 'edit'));
        Flight::route('PUT /asset/@id', array(new AssetController, 'update'));
        Flight::route('DELETE /asset/@id', array(new AssetController, 'delete'));

        Flight::route('/media', array(new MediaController, 'index'));
        Flight::route('GET /media/dirs', array(new MediaController, 'dirs'));
        Flight::route('POST /media/dirs', array(new MediaController, 'createDir'));
        Flight::route('DELETE /media/dirs/@name', array(new MediaController, 'deleteDir'));
        
        Flight::route('POST /media/files', array(new MediaController, 'files'));
        Flight::route('POST /media/upload-files', array(new MediaController, 'uploadFile'));
        Flight::route('DELETE /media/files', array(new MediaController, 'deleteFile'));

        Flight::route('GET /setting', array(new SettingController, 'index'));
        Flight::route('PUT /setting', array(new SettingController, 'update'));
    }
});

$adminPrefix = config('ADMIN_PREFIX');
Flight::$adminPrefix();
