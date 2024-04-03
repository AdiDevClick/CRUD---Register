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

    public function getThisUrl()
    {
        echo $this->getUrl();
    }
    public function getThisRootUrl()
    {
        echo $this->getRootUrl();
    }
}
