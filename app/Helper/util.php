<?php

use App\Model\Setting;

global $_SETTING;

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

            $result .= "window.{$key} = " . $jsValue . ";\n";
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

/**
 * Make slugify
 * 
 * this original code from user "leoap" in stackoverflow forum
 * 
 * https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
 *
 * @param [type] $text
 * @return void
 */
function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

/**
 * Convert file mb to kb
 *
 * @param string $size
 * 
 * @return float
 */
function convert_file_mb_size($size)
{
    if (strpos($size, 'M') === false) {
        return 0;
    }
    
    $fileSize = preg_replace('/[^0-9.]/', '', $size);
    
    return $fileSize * 1024 * 1024;
}

/**
 * Convert size readable
 *
 * @param float $size
 * 
 * @return string
 */
function size_readable($size)
{
    $unit = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $f = floor((strlen($size) - 1) / 3);

    return sprintf("%.2f", $size / pow(1024, $f)) . @$unit[$f];
}

/**
 * Get setting
 *
 * @param string $key
 * 
 * @return void
 */
function setting($key)
{
    global $_SETTING;

    if (!empty($_SETTING)) {
        return $_SETTING;
    }

    $setting = $setting = new Setting;
    $_SETTING = $setting->findAll();

    return $_SETTING;
}

/**
 * Truncate URL
 *
 * @param string $text
 * @param int $firstLength
 * 
 * @return string
 */
function url_truncate($text, $firstLength = 11)
{
    $text = preg_replace(array('/https?\:/'), '', $text);
    $first = substr($text, 0, $firstLength);
    $url = parse_url($text);
    $filname = empty($url['path']) ? '' : basename($url['path']);
    
    return $first . '...' . $filname;
}

/**
 * Sort assoc arryc
 *
 * @param array $data
 * @param string $key
 * @param string $direction
 * 
 * @return array
 */
function sort_assoc_array($data = array(), $key, $direction = 'asc')
{
    $direction = $direction == 'desc' ? SORT_DESC : SORT_ASC;
    $groupOfKeys = array();
    
    foreach ($data as $value) {
        $groupOfKeys[] = $value[$key];
    }

    array_multisort($groupOfKeys, $direction, $data);

    return $data;
}

/**
 * Is Image
 *
 * @param string $path
 * 
 * @return boolean
 */
function is_image($path)
{
    $images = array(
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/gif'
    );

    $mime = mime_content_type($path);

    return in_array($mime, $images);
}