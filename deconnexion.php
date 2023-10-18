<?php



if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('config/user.php');

if((session_status() === PHP_SESSION_ACTIVE) && isset($loggedUser)) {
    session_destroy();
    header_remove('$_COOKIE');
    setcookie(
        'LOGGED_USER',
        '',
        [
            'expires' => time() - 3600,
            'secure' => true,
            'httponly' => true,
        ]
    );
} else {
    throw new Exception("Erreur dans la suppression du cookie");
}

header('Location: index.php');
