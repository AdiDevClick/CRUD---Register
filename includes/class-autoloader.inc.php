<?php

class Autoloader
{
    public static function register()
    {
        // Charger l'autoloader de Composer
        require_once ROOT_PATH . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";
        // require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

        spl_autoload_register(function ($class) {
            $file =  ROOT_PATH . 'classes' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.class.php';
            // $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.class.php';

            if (!file_exists($file)) {
                // Recherche récursive des fichiers dans les sous-dossiers
                $files = glob(ROOT_PATH . 'classes' . DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*.class.php');
                // $files = glob(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*.class.php');
                foreach ($files as $f) {
                    if (strpos($f, str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.class.php') !== false) {
                        $file = $f;
                        break;
                    }
                }
            }

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
