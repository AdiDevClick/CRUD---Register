<?php

declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}
// $_SESSION['LAST_ID'] !== 0 || null ? null : $_SESSION['LAST_ID'] = 0;

// Sets last search index in database
$_SESSION['LAST_ID'] ?? $_SESSION['LAST_ID'] = 0;

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

/**
 * Dans le cas d'une recherche, on utilise $_SESSION['LAST_ID'] pour sauvegarder l'état de la recherche -
 * Script JS : 'SearchBar.js' -
 * Fetch les éléments depuis la Database -
 * 4 options peuvent être passées : query, _reset, _page, _limit -
 * _reset : Permet de remettre le compteur $_SESSION['LAST_ID'] = 0 -
 *          S'effectue automatiquement par JS quand une nouvelle recherche est amorcée par l'utilisateur -
 * _page :  Le numéro page ne DOIT PAS être modifiée, elle est gérée par JS et
 *          s'incrémente au fur et à mesure que ne nouveaux éléments sont chargés -
 * _limit : Permet de limiter le nombre d'éléments fetch par demande utilisateurs -
 *          Il est préférable de ne pas aller en dessous de 5 -
 * query :  Est défini dans les searchParams par JS dès que l'utilisateur effectue une recherche -
 *          N'est pas modifiable -
 */
if (isset($_GET['query'])) {
    // header('Content-Type: application/json; charset=utf-8');
    // $content = file_get_contents("php://input");
    // $dataTest = json_decode($content, true);

    // echo json_encode($_SESSION['LAST_ID']) ;

    // $_SESSION['LAST_ID'] = 0;
    // $getSearchLimit = $_GET['_limit'];
    $getSearchRequest = $_GET['query'];
    // $getSearchReset = $_GET['_reset'];
    $optionnalData = [
        'limit' => $_GET['_limit'],
        // 'query' => $_GET['query'],
        'resetState' => $_GET['_reset']
    ];
    $getRecipe = new RecipeView($getSearchRequest, $optionnalData);
    // Retourne un array d'objets contenant :
    // author: "email",
    // img_path: "img/path/to/images"
    // recipe_id: int
    // score: int
    // title: "titre de ma recette"
    // youtubeID: "idvideoyoutube"
    $recipe = $getRecipe->getRecipesTitle();
    // foreach ($recipe as $key) {
    //     echo json_encode(array("title"=> $key));
    // }
    // if ($getSearchLimit > 0) {
    //     for ($i = 0; $i < $getSearchLimit; $i++) {
    //         array_slice($recipe, $i);
    //         echo json_encode([$recipe[$i]]);
    //     }
    // }
    // echo json_encode($_SESSION['LAST_ID']);

    echo json_encode($recipe);
    // $_SESSION['LAST_ID'] = $recipe['recipe_id'];
    // echo json_encode($_SESSION['LAST_ID']) ;

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
    // voir si on récupère les fichiers du dessus
    $process_Ingredients = new Process_Ajax($dataTest ?? $_POST, $_FILES, $is_Post, $getIdDatas ?? null);
    
    // Remove session user cookies
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
