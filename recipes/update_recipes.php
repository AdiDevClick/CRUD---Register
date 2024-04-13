<?php

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Title = "We Love Food - Mise à jour de votre recette";
ob_start();

require("../recipes/submit_update_recipes.inc.php");
$content = ob_get_clean();
require('../templates/layout.php');
