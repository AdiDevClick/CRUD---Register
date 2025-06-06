<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "variables.inc.php";

$scripts = [
    // 'src="' . strip_tags($rootUrl) . $clicServer . '/scripts/fadeInScroller.js" defer',
    'type="module" src="' . strip_tags($rootUrl) . $clicServer . '/scripts/recipeApp.js" async'
];

$pageTitle = "Partagez votre recette";

$title = "We Love Food - $pageTitle";

require_once '../recipes/submit_recipes.php';

require '../templates/layout.php';
