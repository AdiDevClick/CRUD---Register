<?php declare(strict_types=1);

// if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
//ob_start();
// require('../recipes/test.php');
// include_once("../vendor/class-autoloader.inc.php");
// include_once('../logs/customErrorHandlers.php');
// require_once(__DIR__ . './' . "vendor/class-autoloader.inc.php");
// require_once(__DIR__ . "/logs/customErrorHandlers.php");
// include('../recipes/test.php');

// $rootUrl = Functions::getRootUrl();
// // echo $rootUrl;
$data = $_SERVER['REQUEST_METHOD'] === 'GET';
$err = [];
$loggedUser = LoginController::checkLoggedStatus();
$sessionName = 'INFO_RECIPE';
$params = [
    'fields' => ['*'],
    'table' => ['recipes r'],
    'join' => ['images i' => 'r.recipe_id = i.recipe_id'],
    "where" => [
        "conditions" => [
            "r.recipe_id" => "= :recipe_id"
        ],
    ],
    'error' => ["Erreur dans la récupération de la recette"],
];

/**
 * Grabbing URL ID from index page and fetching rows datas
 */
if($data && isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Grab ID from the url
    $getID = $_GET['id'];
    // passing recipe ID to the constructor
    $id = new RecipeView($getID);

    // Retrieve all recipe infos
    $getInfos = $id->retrieveFromTable($params, $sessionName);
    // After this point, 'submit' event is handled by JavaScript
} else {
    header('Location: ../index.php?error=no-update-id');
    exit("Il n'y a malheureusement plus rien à voir !");
}

// Check for errors
$err = CheckInput::getErrorMessages();
// Show errors
$errorMessage = CheckInput::showErrorMessage();
ob_start()

?>
<?php if ((isset($loggedUser['email']) || isset($_SESSION['LOGGED_USER'])) && $getInfos['author'] === $loggedUser['email'] && !isset($_SESSION['UPDATED_RECIPE'])):?>
    <h1>Recette à éditer : <?= htmlspecialchars($getInfos['title'])?></h1>
    
    <?php include '../templates/recipe_layout.php'?>
    <?php // include '../templates/recipe_creation_menu.html'?>
    
<!-- start of success message -->

<?php elseif (isset($_SESSION['UPDATED_RECIPE'])):?>
    <?php //$setRecipe->displayShareSuccess($getDatas, $loggedUser)?>
    <?php unset($_SESSION['UPDATED_RECIPE'])?>
    <?php else: ?>
        <?php session_destroy()?>
        <?php header('Location: ../index.php?failed=update-recipe')?>
        <?php exit("Il n'y a malheureusement plus rien à voir !") ?>
<?php endif ?>
<!-- end of success message --> 
<?php $content = ob_get_clean() ?>