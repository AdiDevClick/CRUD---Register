<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) session_start();

//include_once("includes/class-autoloader.inc.php");
include_once('logs/customErrorHandlers.php');

$script = 'src="scripts/fadeInScroller.js" defer';
$title = "Clic'Répare - S'enregistrer";

ob_start();

require_once('submit_register.php');

$content = ob_get_clean();
require('templates/layout.php');
