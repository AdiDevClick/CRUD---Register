<?php 

class UseFunctions extends Functions
{
    public function getThisUrl()
    {
        $url = '';
        $this->getUrl();
        return $url;
    }
}