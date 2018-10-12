<?php
header("Access-Control-Allow-Origin: *");
include "define.php";
/***
 * @param $classname
 */
function __autoload($classname)
{
    $class_file = strtolower($classname) . ".php";
    require_once(LIBDIR . $class_file);

    if (file_exists($class_file)) {
        require_once($class_file);
        // .mod require_once(LIBDIR_TWO.$class_file);
    } else {

    }
}
/***
 * @param $object
 * @return bool
 */
function isBlank($object)
{
    if (is_null($object) || '' === $object || (is_array($object) && count($object) < 1) || !isset($object)) {
        return true;
    }
    return empty($object);
}

/***
 * @param $tag
 * @return bool
 */
function config($tag)
{
    $url = include "config.php";
    if (isset($url[$tag]) && !empty($url[$tag])) {
        return $url[$tag];
    }
    log_message::info('configs is null ');
    return false;
}
