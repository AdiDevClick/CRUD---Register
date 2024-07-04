<?php

declare(strict_types=1);

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');
include_once('../includes/functions.inc.php');

$fetchData = $_SERVER['REQUEST_METHOD'] === 'GET';
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
// isset($_GET['id']) ?: $getIdDatas = $_GET['id'];
$getIdDatas;
$is_Post = true;
if (isset($_GET['id'])) {
// if ($fetchData && isset($_GET['id'])) {
    $getIdDatas = $_GET['id'];
    $is_Post = false;
    // $setRecipe = new RecipeView($getIdDatas);
    // $setRecipe->fetchIngredientsById();
}

if ($data && isset($_POST)) {
    header('Content-Type: application/json; charset=utf-8');
    $content = file_get_contents("php://input");
    $dataTest = json_decode($content, true);
    // !$is_Post ? null : $is_Post = true;
    $process_Ingredients = new Process_Ajax($dataTest ?? $_POST, $_FILES, $is_Post, $getIdDatas ?? null); 
    $err = CheckInput::getErrorMessages();
    // exit;
    if (count($err) > 0) {
        // print_r($err);
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
}