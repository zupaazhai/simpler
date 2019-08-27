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
 * Include view partial
 *
 * @param string $path
 * @param array $data
 * 
 * @return void
 */
function partial($path, $data = array())
{
    $path = preg_replace(array('/\.php/', '/\./'), array('', DS), $path);
    $path = $path . '.php';

    include(config('VIEW_DIR') . 'partials' . DS . $path);
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