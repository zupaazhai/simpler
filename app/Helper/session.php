<?php

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