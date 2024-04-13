<?php

declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once("includes/class-autoloader.inc.php");
// include_once('../logs/customErrorHandlers.php');
require_once(__DIR__ . "/logs/customErrorHandlers.php");

$script = 'src="' . $rootUrl . $clicServer .'scripts/fadeInScroller.js" defer';
$title = "Clic'Répare - Partagez votre recette";

ob_start();

require_once('../recipes/submit_recipes.php');

$content = ob_get_clean();
require('../templates/layout.php');
