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
    
    return Flight::render(config('VIEW_DIR') . $path, $data, $layout);
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

    return Flight::render(config('VIEW_DIR') . 'layouts' . DS . $path, $data);
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
    return '/' . config('PUBLIC_DIR') . '/' . $path;
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
 * Get put request
 *
 * @return array
 */
function put()
{
    if (!is_request('put')) {
        return array();
    }

    $request = Flight::request()->data->getData();
    unset($request['_method']);

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

/**
 * Get config value
 *
 * @param string $key
 * @param mixed $default
 * 
 * @return mixed
 */
function config($key, $default = null)
{
    return defined($key) ? constant($key) : $default;
}

/**
 * Include view partial
 *
 * @param string $path
 * 
 * @return void
 */
function partial($path)
{
    $path = preg_replace(array('/\.php/', '/\./'), array('', DS), $path);
    $path = $path . '.php';

    include(config('VIEW_DIR') . 'partials' . DS . $path);
}

/**
 * Include view
 *
 * @param string $path
 * 
 * @return void
 */
function inc($path, $data = array())
{
    $path = preg_replace(array('/\.php/', '/\./'), array('', DS), $path);
    $path = $path . '.php';

    require(config('VIEW_DIR') . $path);
}

/**
 * Check current route
 *
 * @param string $match
 * 
 * @return boolean
 */
function current_route($match)
{
    $currentRoute = Flight::request()->url;

    return strpos($currentRoute, $match) !== false;
}

/**
 * Create hidden form method
 *
 * @param string $method
 * 
 * @return void
 */
function form_method($method)
{
    echo '<input type="hidden" name="_method" value="' . $method . '">';
}

/**
 * Short echo
 *
 * @param mixed $str
 * 
 * @return void
 */
function __(&$str)
{
    echo empty($str) ? '' : $str;
}

/**
 * Load script to footer
 *
 * @param array $scripts
 * 
 * @return void
 */
function script($scripts = array(), $jsData = array())
{
    $result = '';

    if (!empty($jsData)) {
        $result .= "<script>\n";
        foreach ($jsData as $key => $value) {
            $jsValue = '';

            if (is_array($value)) {
                $jsValue = json_encode($value);
            }

            if (is_string($value)) {
                $jsValue = "'" . $value . "'";
            }

            $result .= "window.{$key} = " . $jsValue;
        }
        $result .= "</script>\n";
    }
    
    foreach ($scripts as $script) {
        $script = preg_replace(array('/\./'), array('/'), $script);
        $script = 'js/' . $script . '.js';

        $result .= ('<script src="' . asset($script) . '"></script>' . "\n");
    }

    Flight::view()->set('scripts', $result);
}

/**
 * Load style to header
 *
 * @param array $styles
 * 
 * @return void
 */
function style($styles = array())
{
    $result = '';
    
    foreach ($styles as $style) {
        $style = preg_replace(array('/\./'), array('/'), $style);
        $style = 'css/' . $style . '.css';
    
        $result .= ('<link rel="stylesheet" href="' . asset($style) . '">' . "\n");
    }

    Flight::view()->set('styles', $result);
}

/**
 * Show data format
 *
 * @param float $timestamp
 * 
 * @return string
 */
function format_date($timestamp)
{
    return date('Y-m-d H:i:s', $timestamp);
}