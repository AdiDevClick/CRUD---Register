<?php

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Title = "We Love Food - Mise à jour de votre recette";
ob_start()

?>
    <!-- Fin du Header -->

    <!--  Le Main -->
    
    <?php require("../recipes/submit_update_recipes.inc.php")?>
    <?php $content = ob_get_clean()?>
    <?php require('../templates/layout.php')?>

    <!--  Fin du Footer -->
</body>
</html>