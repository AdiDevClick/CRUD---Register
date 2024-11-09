<?php

declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sets last search index in database
$_SESSION['LAST_ID'] ?? $_SESSION['LAST_ID'] = 0;

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');
include_once('../includes/functions.inc.php');

$fetchData = $_SERVER['REQUEST_METHOD'] === 'GET';
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$id;
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

    $getSearchRequest = $_GET['query'];
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
    echo json_encode($recipe);
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    /**
     * LORS D'UNE MISE A JOUR :
     * Récupère et renvoi l'ID de la recette au script JS 'RecipePreparation.js'.
     * Cela permet d'afficher les ingrédients dynamiques liés à la recette.
     * L'ID de la recette DOIT être le même que lors de l'initialisation de la page.
     */
    if ($fetchData && isset($_SESSION['INFO_RECIPE']) && $_SESSION['INFO_RECIPE']['INFO_RECIPE'] === $_GET['id']) {
        // Destroy previews Session cookie
        unset($_SESSION['INFO_RECIPE']);
        // Prepare the new SQL params
        $getID = $_GET['id'];
        $sessionName = 'CUSTOM_INGREDIENTS';
        $params = [
            'fields' => ['custom_ingredients'],
            'table' => ['recipes r'],
            'error' => ["Erreur dans la récupération d'ingrédients"],
        ];
        // Prepare the controller for JavaScript submit handler
        $id = new RecipeView($getID, 'reply_Client');
        // Send the SQL request
        $getInfos = $id->retrieveFromTable($params, $sessionName);
        //Destroy previews session cookie
        unset($_SESSION['CUSTOM_INGREDIENTS']);

        // Send the datas to JavaScript in JSON then kills the script
        die($getInfos);
    }
    /**
     * IMPORTANT !!
     * LORS DE L'ENVOI DU FORMULAIRE POUR UNE MISE A JOUR :
     * Récupère à nouveau l'ID de la recette pour la passer au serveur
     */
    // Grab ID from url
    $id = $_GET['id'];
    // Setting the UPDATE RECIPE intention from the user
    $is_Post = false;
    // Setting information to pass inside the SESSION Cookie
    // This will allow the server to display the page content
    $session = 'UPDATED_RECIPE';
    $_SESSION[$session] = $session;

}

/**
 * Renvoi les informations formulaires vers le serveur
 */
// if (!isset($_SESSION[$session]) && $data && isset($_POST)) {
if ($data && isset($_POST)) {

    // Sets the type of header content type to talk to JavaScript
    header('Content-Type: application/json; charset=utf-8');
    // Grab all input datas
    $client_Side_Datas = file_get_contents("php://input");
    // Decoding JSON data
    $data = json_decode($client_Side_Datas, true);
    // Voir si on récupère les fichiers du dessus
    $process_Ingredients = new Process_Ajax($data ?? $_POST, $_FILES, $is_Post, $session, $id ?? null);
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
