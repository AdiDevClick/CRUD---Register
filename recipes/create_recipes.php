<?php

declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."class-autoloader.inc.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR ."customErrorHandlers.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."variables.inc.php";

$script = 'src="' . strip_tags($rootUrl) . $clicServer .'/scripts/fadeInScroller.js" defer';
$script2 = 'type="module" src="' . strip_tags($rootUrl) . $clicServer .'/scripts/recipeApp.js" defer';
$pageTitle = "Partagez votre recette";

$title = "Clic'Répare - $pageTitle";

require_once '../recipes/submit_recipes.php';

require '../templates/layout.php';
