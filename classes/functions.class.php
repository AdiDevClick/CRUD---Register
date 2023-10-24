<?php

readonly class Functions {
    static public function getUrl() {
        $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = array_pop($url);
        return $url;
    }

    public function getThisUrl()
    {
        echo $this->getUrl();
    }
}


