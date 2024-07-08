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
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];
$loggedUser = [];
// print_r($_REQUEST);
// print_r($data);
// include('../recipes/Process_PreparationList.php');

// print_r($getDatas);
// On affiche chaque recette une à une
// if ($data && isset($_POST['submit'])) {
//     //try {
//     //require_once("../includes/class-autoloader.inc.php");
//     print_r($data);
//     $getDatas = [
//     'title' => $_POST['title'],
//     'step_1' => $_POST['step_1'],
//     'step_2' => $_POST['step_2'],
//     'step_3' => $_POST['step_3'],
//     'step_4' => $_POST['step_4'],
//     'step_5' => $_POST['step_5'],
//     'step_6' => $_POST['step_6'],
//     // 'ingredient' => $_POST['time'],
//     ];

//     print_r($getDatas);
//     // $content = trim(file_get_contents("php://input"));

//     // $dataTest = json_decode($content, true);

//     // echo json_encode($dataTest['persons']);
//     // echo($dataTest["persons"]);
//     // echo($content);

//     // print_r($dataTest);
//     // print_r($getDatas);
//     $setRecipe = new RecipeView($getDatas);
//     $setRecipe->insertRecipe();
//     $err = CheckInput::getErrorMessages();

//     if (count($err) > 0) {
//         // print_r($err);
//         session_destroy();
//     } else {
//         header('refresh:10, ../index.php?success=recipe-shared');
//         // session_destroy();
//     }
//     //header('Location: ../index.php?error=none');
//     //unset($_SESSION['REGISTERED_RECIPE']);
//     //unset($_SESSION['REGISTERED_RECIPE']);
//     //exit();
//     //header('refresh:10, ../index.php');
//     //$content = ob_get_contents();
//     //$content = ob_get_clean();
//     //} catch (Error $e) {
//     //die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas dans l\'insertion...') ;
//     //}
// }

//$content = ob_get_clean();
//$content = ob_end_flush();

//ob_start()
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
// ob_get_clean();
ob_start();
// ob_end_clean();
// ob_get_status();
// ob_get_contents()
//if (isset($loggedUser['email']) && !isset($loggedUser['recipe'])):

?>
<?php if (isset($loggedUser['email'])  && !isset($_SESSION['REGISTERED_RECIPE'])): ?> 
        <?php //(isset($loggedUserState)):?>
    <h1>Partagez votre recette</h1>
    <?= include '../templates/recipe_layout.php'?>
<!-- start of success message -->

<?php elseif (isset($_SESSION['REGISTERED_RECIPE'])):?>
    <?php //require_once('signup_success.php')?>
    <?php //ob_start()?>
    <?php //ob_get_contents()?>
    <?php $setRecipe->displayShareSuccess($getDatas, $loggedUser)?>
    <?php unset($_SESSION['REGISTERED_RECIPE'])?>
    <?php //session_destroy()?>
    <?php //ob_get_contents()?>
    <?php //header('refresh:10, ../index.php?error=none')?>
    <?php else:?>
        <?php session_destroy()?>
        <?php header('Location: ../register.php?failed=recipe-creation')?>
        <?php exit()?>
<?php endif?>
<!-- end of success message --> 
<?php //$content = ob_end_flush()?>
<?php $content = ob_get_clean()?>