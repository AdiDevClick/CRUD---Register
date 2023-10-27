<?php

spl_autoload_register('myAutoLoader');

function myAutoLoader($className)
{
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    if (strpos($url, 'includes') !== false || strpos($url, 'recipes') !== false) {
        $path = '../classes/';
    } else {
        $path = 'classes/';
    }
    $extension = ".class.php";
    require_once $path . $className . $extension;
}

// get the current URL :
/* $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$url = array_pop($url); */