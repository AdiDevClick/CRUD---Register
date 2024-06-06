<?php
/***
 * On vérifie si un cookie est présent
 */

if(isset($_COOKIE['EMAIL']) || isset($_SESSION['LOGGED_USER'])) {
    $loggedUser = [
        'email' => $_COOKIE['EMAIL'] ?? $_SESSION['LOGGED_USER'],
    ];
} else if (isset($_COOKIE['FULLNAME']) || isset($_SESSION['USER_NAME'])) {
    $loggedUser = [
        'user' => $_COOKIE['FULLNAME'] ?? $_SESSION['USER_NAME'],
];
} else if (isset($_COOKIE['USERID']) || isset($_SESSION['USER_ID'])) {
    $loggedUser = [
        'userId'=> $_COOKIE['USERID'] ?? $_SESSION['USER_ID'],
        ];
} else {
    throw new Exception('Veuillez vous identifier pour ajouter une recette');
}