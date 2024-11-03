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

$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];
$loggedUser = [];
$getInfos = null;

$loggedUser = LoginController::checkLoggedStatus();
// echo ' le array dans submit recipe';
// print_r($loggedUser);
$err = CheckInput::getErrorMessages();
// if (count($err) > 0) {
//     // print_r($err);
//     $errorMessage = CheckInput::showErrorMessage();

//     $successMessage = '';
//     //if (isset($_SESSION['REGISTERED_USER'])) {
//     //ob_start();
//     $successMessage = '<div>';
//     $successMessage .= '<p class="alert-error"> ' . strip_tags($errorMessage) . '</p>';
//     $successMessage .= '</div>';
//     echo $successMessage;
//     echo $dataTest['failed'] = true;
//     // session_destroy();
// } else {
//     header('Location: ../index.php?success=recipe-shared');
//     // header('refresh:10, ../index.php?success=recipe-shared');
//     // session_destroy();
// }
$errorMessage = CheckInput::showErrorMessage();
ob_start();
//if (isset($loggedUser['email']) && !isset($loggedUser['recipe'])):
?>

<?php if (isset($loggedUser['email'])  && !isset($_SESSION['REGISTERED_RECIPE'])): ?> 
        <?php //(isset($loggedUserState)):?>
    <h1>Partagez votre recette</h1>
        
    <!-- <script type="text/javascript">
        if (window.innerWidth <= 576) {
            // fetch('../templates/recipe_layout_mobile_only.php')
            // .then((include) => include.text())
            // .then((data) => document.documentElement.querySelector('main').innerHTML = data)
            document.querySelector('main').innerHTML = fetch('../templates/recipe_layout_mobile_only.php');
            // console.log(include)
        } ;

        if (window.innerWidth > 576) {
            // fetch('../templates/recipe_layout_all_resolutions.php')
            // .then((include) => include.text())
            // .then((data) => document.documentElement.querySelector('main').innerHTML = data)
            document.querySelector('main').innerHTML = fetch('../templates/recipe_layout_mobile_only.php');
            // console.log(includes)

        }

    </script> -->
    <?php include '../templates/recipe_layout.php'?>
    <?php include '../templates/recipe_creation_menu.html'?>
<!-- start of success message -->

<?php elseif (isset($_SESSION['REGISTERED_RECIPE'])):?>
    <?php //require_once('signup_success.php')?>
    <?php // $setRecipe->displayShareSuccess($getDatas, $loggedUser)?>
    <?php unset($_SESSION['REGISTERED_RECIPE'])?>
    <?php //header('refresh:10, ../index.php?error=none')?>
    <?php else:?>
        <?php session_destroy()?>
        <?php header('Location: ../register.php?failed=recipe-creation')?>
        <?php exit("Il n'y a malheureusement plus rien à voir !") ?>
<?php endif ?>
<!-- end of success message --> 
<?php $content = ob_get_clean()?>