<?php

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');

$script2 = 'type="module" src="' . $rootUrl . $clicServer .'/scripts/recipeApp.js" defer';
$title = "We Love Food - Mise à jour de votre recette";
// ob_start();

require("../recipes/submit_update_recipes.inc.php");
// $content = ob_get_clean();
require('../templates/layout.php');
