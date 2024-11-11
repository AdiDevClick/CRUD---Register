<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');

$postData = $_POST;

if (
    !isset($postData['comment']) &&
    !isset($postData['recipe_id']) &&
    !is_numeric($postData['recipe_id'])
) {
    echo('Le commentaire est invalide.');
    return;
}
$loggedUser = LoginController::checkLoggedStatus();
if (!isset($loggedUser)) {
    echo('Vous devez être authentifié pour soumettre un commentaire');
    return;
}

$getDatas = [
    'comment' => $postData['comment'],
    'recipeId' => $postData['recipe_id'],
];
$insertComment = new RecipeView(
    $getDatas
);
//$insertCcomment->insertComments($comment, $recipeId, $loggedUser['user'][1]);
//$insertCcomment->insertComments($getDatas['comment'], $getDatas['recipeId'], $loggedUser['user'][1]);
$insertComment->insertComment($getDatas);

header('refresh:10, ../index.php?error=none');

unset($_SESSION['REGISTERED_COMMENT']);
//header('refresh:5, '.Functions::getUrl().'?error=none');

$title = "Site de Recettes - Création de commentaire";
$err = CheckInput::getErrorMessages();
$errorMessage = CheckInput::showErrorMessage();
// <body class="d-flex flex-column min-vh-100">
//   <div class="container"> -->
ob_start()
?>
    <?php //include_once('../includes/header.inc.php')?>
        <h1>Commentaire ajouté avec succès !</h1>
        <p><?php echo $errorMessage?></p>

        <div class="card">
            <div class="card-body">
                <p class="card-text"><b>Votre commentaire</b> : <?php echo strip_tags($getDatas['comment']); ?></p>
                <p>Vous serez redirigé vers la page d'accueil dans 10 secondes</p>
            </div>
        </div>
    <!-- </div> -->
<?php
$content = ob_get_clean();
require('../templates/layout.php')
?>
