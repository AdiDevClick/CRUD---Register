<?php

// Définir le chemin racine du projet
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Définir l'environnement en développement (dev / prod)
putenv('APP_ENV=dev');

// Définir si vite est utilisé
// true or false
const VITE = true;

// Définir si nous somme dans une configuration de développement
// true or false
const DEV = true;

class Config
{
    private string $website_title = "Maxi Recettes";
    private bool $vite = false;
    private bool $dev = true;

    public static function vite(bool $bool): bool
    {
        return $bool ? Config::get("") : Config::get("");
    }
}
