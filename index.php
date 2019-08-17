<?php

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('VIEW_DIR', __DIR__ . DS . 'resources' . DS . 'views' . DS);
define('PUBLIC_DIR', 'public');
define('STORAGE_DIR', __DIR__ . DS . 'storage' . DS);

define('USER_FILE_KEY', STORAGE_DIR . 'a3ea38c654bf21d70902ab9f90f98e9c');
define('USER_PASSPHARSE', 'a3ea38c654bf21d70902ab9f90f98e9c');

define('FALLBACK_REDIRECT', '/login');
define('CALLBACK_REDIRECT', '/dashboard');

require 'vendor/autoload.php';
require 'helper.php';
require 'routes.php';

Flight::start();