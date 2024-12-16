<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";
// include_once("../vendor/class-autoloader.inc.php");
// include_once('../logs/customErrorHandlers.php');

// $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// $path = $protocol.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
// echo $path . '</br>';

// $current_url_path = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// $current_url_path = substr($current_url_path, -4) == ".php" ?
//     implode("/", array_slice((explode("/", $current_url_path)), 0, -1)) : $current_url_path;

// echo $current_url_path . '</br>';


// $filename = pathinfo('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], PATHINFO_FILENAME);

// echo $_SERVER['PATH_INFO'];
// echo $filename;

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