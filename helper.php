<?php

$helperPath = ROOT_PATH . 'app' . DS . 'Helper' . DS;
$helpers = array(
    'view.php',
    'session.php',
    'request.php',
    'util.php'
);

foreach ($helpers as $helper) {
    require_once $helperPath . $helper;
}