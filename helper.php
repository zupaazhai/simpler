<?php

/**
 * Render view
 *
 * @param string $path
 * @param array $data
 * 
 * @return \Flight
 */
function view($path, array $data = array(), $layout = null)
{
    $path = preg_replace(array('/\.php/', '/\./'), array('', DS), $path);
    $path = $path . '.php';
    
    return Flight::render(VIEW_DIR . $path, $data, $layout);
}

/**
 * Render layout
 *
 * @param string $path
 * @param array $data
 * @param array $layout
 * 
 * @return \Flight
 */
function layout($path, array $data = array(), $layout = null)
{
    $path = preg_replace(array('/\.php/', '/\./'), array('', DS), $path);
    $path = $path . '.php';

    return Flight::render(VIEW_DIR . 'layouts' . DS . $path, $data);
}

/**
 * Get asset path
 *
 * @param string $path
 * 
 * @return void
 */
function asset($path = '')
{
    return '/' . PUBLIC_DIR . '/' . $path;
}

/**
 * Debug
 *
 * @param mixed $debug
 * 
 * @return void
 */
function dump(&$debug)
{
    echo "<pre>";
    var_dump($debug);
    echo "</pre>";
}

/**
 * Filter array by key
 *
 * @param array $arrayData
 * @param array $columns
 * 
 * @return array
 */
function array_only($arrayData, $columns = array())
{
    $result = array();

    foreach ($arrayData as $key => $value) {
        if (!in_array($key, $columns)) {
            continue;
        }

        $result[$key] = $value;
    }

    return $result;
}

/**
 * Aliase of request
 *
 * @return \Flight
 */
function req()
{
    return Flight::request();
}

/**
 * Get post request
 *
 * @return array
 */
function post()
{
    if (!is_request('post')) {
        return array();
    }

    $request = Flight::request()->data->getData();

    return $request;
}

/**
 * Redirect back
 *
 * @param mixed $withData
 * 
 * @return \Flight
 */
function back($withData = null)
{
    if (!empty($withData)) {
        put_session('old', $withData);
    }

    return Flight::redirect(Flight::request()->referrer);
}

/**
 * Get flash session
 *
 * @param string $key
 * 
 * @return mixed
 */
function bind($key) {
    return get_session($key, true);
}

/**
 * Unbind session
 *
 * @param string $key
 * 
 * @return void
 */
function unbind($key)
{
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

/**
 * Get request from back
 *
 * @return mixed
 */
function old() {
    return get_session('old', true);
}

/**
 * Put session
 *
 * @param string $key
 * @param mixed $data
 * 
 * @return void
 */
function put_session($key, $data = null)
{
    if (is_array($data)) {
        $data = json_encode($data);
    }

    $_SESSION[$key] = $data;
}

/**
 * Flash session
 *
 * @param string $key
 * @param mixed $data
 * 
 * @return void
 */
function flash($key, $data = null)
{
    put_session($key, $data);
}

/**
 * Get session
 *
 * @param string $key
 * @param boolean $delete
 * 
 * @return mixed
 */
function get_session($key, $delete = false)
{
    $session = !isset($_SESSION[$key]) ? false : $_SESSION[$key];
    
    if (!$session) {
        return false;
    }

    $parseToArray = json_decode($session, true);

    if (is_array($parseToArray)) {
        $session = $parseToArray;
    }

    if ($delete) {
        unset($_SESSION[$key]);
    }

    return $session;
}

/**
 * Check is request
 *
 * @param string $method
 * 
 * @return boolean
 */
function is_request($method)
{
    $currentMethod = Flight::request()->method;

    return strtolower($method) == strtolower($currentMethod); 
}

/**
 * Check contains in string
 *
 * @param string $heystack
 * @param array $needles
 * 
 * @return boolean
 */
function is_contains($heystack, $needles = array())
{
    $result = array();

    foreach ($needles as $needle) {
        $result[] = strpos($heystack, $needle) !== false; 
    }

    return in_array(true, $result);
}