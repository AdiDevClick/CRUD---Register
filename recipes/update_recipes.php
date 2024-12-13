<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}
// require_once __DIR__ . "/includes/common.php";
// require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR ."class-autoloader.inc.php";
// require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR ."customErrorHandlers.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "variables.inc.php";
// include_once("../vendor/class-autoloader.inc.php");
// include_once('../logs/customErrorHandlers.php');
// include_once('../includes/variables.inc.php');

$script = 'src="' . strip_tags($rootUrl) . $clicServer . '/scripts/recipeApp.js" type="module" async';

$pageTitle = "Mise à jour de votre recette";

$title = $pageTitle;

require_once '../recipes/submit_update_recipes.inc.php';

require '../templates/layout.php';
