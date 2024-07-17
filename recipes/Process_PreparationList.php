<?php

declare(strict_types=1);

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');
include_once('../includes/functions.inc.php');

$fetchData = $_SERVER['REQUEST_METHOD'] === 'GET';
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$getIdDatas;
$is_Post = true;
$session = 'REGISTERED_RECIPE';

// header('Content-Type: application/json; charset=utf-8');
// $content = file_get_contents("php://input");
// $dataTest = json_decode($content, true);
// print_r(isset($_GET)) ;
// print_r ($dataTest);

if (isset($_GET['query'])) {
    // header('Content-Type: application/json; charset=utf-8');
    // $content = file_get_contents("php://input");
    // $dataTest = json_decode($content, true);
    $getIdDatas = $_GET['query'];
    $getRecipe = new RecipeView($getIdDatas);
    $recipe = $getRecipe->getRecipesTitle();
    // foreach ($recipe as $key) {
    //     echo json_encode(array("title"=> $key));
    // }
    echo json_encode($recipe);
    // echo json_encode(array("title"=> $recipe));

}

/**
 * LORS D'UNE MISE A JOUR :
 * Récupère et renvoi l'ID de la recette au script JS 'RecipePreparation.js'
 * Cela permet d'afficher les ingrédients dynamiques liés à la recette
 */
if ($fetchData && isset($_GET['id'])) {
    $getIdDatas = $_GET['id'];
    $setRecipe = new RecipeView($getIdDatas);
    $setRecipe->fetchIngredientsById();
}

/**
 * IMPORTANT !!
 * LORS DE L'ENVOI DU FORMULAIRE POUR UNE MISE A JOUR :
 * Récupère à nouveau l'ID de la recette pour la passer au serveur
 */
if (isset($_GET['id'])) {
    $getIdDatas = $_GET['id'];
    $is_Post = false;
    $session = 'UPDATED_RECIPE';
}

/**
 * Renvoi les informations formulaires vers le serveur
 */
// if (!isset($_SESSION[$session]) && $data && isset($_POST)) {
if ($data && isset($_POST)) {
    header('Content-Type: application/json; charset=utf-8');
    $content = file_get_contents("php://input");
    $dataTest = json_decode($content, true);
    $process_Ingredients = new Process_Ajax($dataTest ?? $_POST, $_FILES, $is_Post, $getIdDatas ?? null); 
    // return;
    unset($_SESSION[$session]);
    $err = CheckInput::getErrorMessages();

    if (count($err) > 0) {
        $errorMessage = CheckInput::showErrorMessage();
        $successMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
        //ob_start();
        $successMessage = '<div>';
        $successMessage .= '<p class="alert-error"> ' . strip_tags($errorMessage) . '</p>';
        $successMessage .= '</div>';
        // echo json_encode($successMessage);
        // print $getDatas['failed'] = true;
        // echo $getDatas['failed'] = 1;
        // echo json_encode($getDatas['failed'] = 1);
        // echo 'window.location.href = ../index.php?success=recipe-shared';
        // session_destroy();
    }
    // }
} else {
    return;
}