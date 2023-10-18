<?php
/***
 * On vérifie si un cookie est présent
 */
if(isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
    $loggedUser = [
        'email' => $_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER'],
    ];
} else {
    throw new Exception('Veuillez vous identifier pour ajouter une recette');
}
