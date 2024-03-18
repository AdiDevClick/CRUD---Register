<?php
ob_start();
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <?php //ob_start()?>
    <?php $title = "Clic'Répare - Partagez votre recette"?>
    <?php require('../recipes/submit_recipes.php')?>
    <?php //header('refresh:10, ../index.php?error=none')?>
    <?php $content = ob_get_clean()?>
    <?php require('../templates/layout.php')?>