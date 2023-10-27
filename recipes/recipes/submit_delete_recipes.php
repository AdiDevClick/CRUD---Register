<?php


if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../config/mysql.php");
include_once("../includes/variables.inc.php");
include_once("../includes/class-autoloader.inc.php");

$getData = $_SERVER['REQUEST_METHOD'] == 'POST';;
$errorId = 'ID';
$getDatas = $_POST['id'];


//$checkId = new CheckId($getData, (int)$inputId, $errorId);
$checkId = new Recipecontroller($getDatas);


try {
    if ($checkId->checkIds()) {
        //$checkId->getRecipeId((int)$getDatas);
        $checkId->deleteRecipeId($getDatas);
    }
/*try {

    
     if ($checkId->checkIds()) {
        $sqlQuery = 'DELETE FROM recipes WHERE recipe_id = :id';
        $deteRecipeStatement = $db->prepare($sqlQuery);
        $deteRecipeStatement->execute([
            'id' => $getDatas
        ]);
        header('Location: ../index.php');
    } */
    } catch (Error $e) {
        die('Erreur : ' . $e->getMessage());
}
/* $redirectUrl = 'Location: contact.php';
$url = '/recettes/submit_contact.php'; */
//getUrl() === $url
