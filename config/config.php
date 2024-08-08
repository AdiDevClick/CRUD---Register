<?php

class Config 
{
    public static function vite(bool $bool) : bool
    {
        return $bool ? Config::get("") : Config::get("");
    }
}