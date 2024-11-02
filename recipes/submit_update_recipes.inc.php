<?php declare(strict_types=1);

// if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
//ob_start();
// require('../recipes/test.php');
// include_once("../includes/class-autoloader.inc.php");
// include_once('../logs/customErrorHandlers.php');
// require_once(__DIR__ . './' . "includes/class-autoloader.inc.php");
// require_once(__DIR__ . "/logs/customErrorHandlers.php");
// include('../recipes/test.php');

// $rootUrl = Functions::getRootUrl();
// // echo $rootUrl;
$data = $_SERVER['REQUEST_METHOD'] === 'GET';
$err = [];
$loggedUser = LoginController::checkLoggedStatus();

// if (!isset($loggedUser['email'])) {

// }

/**
 * Grabbing URL ID from index page and fetching rows datas
 */
if($data && isset($_GET['id']) && is_numeric($_GET['id'])) {

    $getDatas = $_GET['id'];
    $checkId = new RecipeView($getDatas);

    $getInfos = $checkId->getRecipeInfoById();
    // echo $getInfos['custom_ingredients'];
    // echo '<pre>';
    // print_r($getInfos);
    // echo '</pre>';
} else {
    // echo ('pas did');
    // echo $_GET['id'] . 'test';
    // print_r($_GET);
    header('Location: ../index.php?error=no-update-id');
    exit("Il n'y a malheureusement plus rien à voir !");
}

// echo json_encode($getInfos);
echo $getInfos['author'];

$err = CheckInput::getErrorMessages();
$errorMessage = CheckInput::showErrorMessage();
ob_start()

//&& $getInfos['author'] === $loggedUser['email'] 
?>
<?php if ((isset($loggedUser['email']) || isset($_SESSION['LOGGED_USER'])) && $getInfos['author'] === $loggedUser['email'] && !isset($_SESSION['UPDATED_RECIPE'])):?>
    <h1>Recette à éditer : <?= htmlspecialchars($getInfos['title'])?></h1>
    
    <?php include '../templates/recipe_layout.php'?>
    <?php include '../templates/recipe_creation_menu.html'?>
    
<?php // endif?>
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