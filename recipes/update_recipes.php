<?php
ob_start();
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <?php $Title = "We Love Food - Mise à jour de votre recette"?>

    <!-- Fin du Header -->

    <!--  Le Main -->
    
    <?php require("../recipes/submit_update_recipes.inc.php")?>
    <?php $content = ob_get_clean()?>
    <?php require('../templates/layout.php')?>

    <!--  Fin du Footer -->
</body>
</html>