<?php

use App\Controller\UserController;
use App\Controller\DashboardController;

Flight::route('/login', array(new UserController, 'loginForm'));
Flight::route('/dashboard', array(new DashboardController, 'index'));
