<?php

if(session_status() !== PHP_SESSION_ACTIVE || session_status() !== PHP_SESSION_NONE) {
    session_start();
}

// require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."common.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."common.php";

// include_once("includes/class-autoloader.inc.php");

// include_once('config/user.php');
$loggedUser = LoginController::checkLoggedStatus();


if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time() - 1000);
        setcookie($name, '', time() - 1000, '/');
    }
}
// } else {
//     throw new Error("Erreur dans la suppression du cookie");
// }

header('Location: index.php?success=disconnected');


// if((session_status() === PHP_SESSION_ACTIVE) && isset($loggedUser['email'])) {
//     session_unset();
//     session_destroy();
//     header_remove('$_COOKIE');
//     setcookie(
//         'LOGGED_USER',
//         '',
//         [
//             'expires' => time() - 3600,
//             'secure' => true,
//             'httponly' => true,
//         ]
//     );
// } else {
//     throw new Error("Erreur dans la suppression du cookie");
// }

// header('Location: index.php');
