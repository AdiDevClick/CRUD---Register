<?php

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.class.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}

Autoloader::register();


/*spl_autoload_register('myAutoLoader');

function myAutoLoader($className)
{
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    /* $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $url = array_pop($url);

    if (strpos($url, 'includes') !== false ||
        strpos($url, 'recipes') !== false ||
        strpos($url, 'comments') !== false) {
        $path = '../classes/';
    } else {
        $path = 'classes/';
    }
    $extension = ".class.php";
    require_once $path . $className . $extension;
}*/
