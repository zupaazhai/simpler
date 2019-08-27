<?php

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
 * Get php input
 *
 * @return string
 */
function get_php_input()
{
    return file_get_contents('php://input');
}
