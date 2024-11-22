<?php

// Définir le chemin racine du projet
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Définir l'environnement en développement pour cet exemple (à adapter selon vos besoins)
putenv('APP_ENV=dev');
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
