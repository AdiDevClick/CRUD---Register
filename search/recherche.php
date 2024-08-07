<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');

$title = "MaxiRecettes - Votre recherche";

$loggedUser = LoginController::checkLoggedStatus();

ob_start();

?>

<section class="container">
    <div class="searched-recipes">

    </div>
</section>

<?php
    $content = ob_get_clean();
    require('../templates/layout.php');
?>



