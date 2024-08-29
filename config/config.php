<?php

class Config 
{

    private string $website_title = "Maxi Recettes";
    private bool $vite = false;
    private bool $dev = true;

    public static function vite(bool $bool) : bool
    {
        return $bool ? Config::get("") : Config::get("");
    }
}