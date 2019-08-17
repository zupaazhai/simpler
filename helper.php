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