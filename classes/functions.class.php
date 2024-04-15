<?php

// readonly class Functions {
class Functions
{
    public static function getUrl()
    {
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = array_pop($url);
        return $url;
    }

    public static function getRootUrl()
    {
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return $rootUrl;
    }

    public static function actualServer($rootUrl) : string
    {
        // $rootUrl = Functions::getRootUrl();
        // $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        if ($rootUrl === 'https://adi.ezaya.fr/') {
            return 'ClicRepare';
        } else {
            return 'recettes';
        }
    }

    public function getThisUrl()
    {
        echo $this->getUrl();
    }
    public function getThisRootUrl()
    {
        return $this->getRootUrl();
    }
    public function getThisActualServer($rootUrl) : string
    {
        return $this->actualServer($rootUrl);
    }
}
