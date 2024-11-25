<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."common.php";

if (
    !isset($_POST['comment']) &&
    !isset($_POST['recipe_id']) &&
    !is_numeric($_POST['recipe_id'])
) {
    echo 'Le commentaire est invalide. ';
    return;
}
$loggedUser = LoginController::checkLoggedStatus();
if (!isset($loggedUser)) {
    echo 'Vous devez être authentifié pour soumettre un commentaire ';
    return;
}

$insertComment = new RecipeView(
    $_POST
);
$comment = $insertComment->insertComment($_POST);

header('refresh:5, ../index.php?error=none');

unset($_SESSION['REGISTERED_COMMENT']);
//header('refresh:5, '.Functions::getUrl().'?error=none');

$title = "Création de commentaire";
$err = CheckInput::getErrorMessages();
$errorMessage = CheckInput::showErrorMessage();
// <body class="d-flex flex-column min-vh-100">
//   <div class="container"> -->
ob_start()
?>

<h1>Commentaire ajouté avec succès !</h1>
<p><?php echo $errorMessage?></p>

<div class="card">
    <div class="card-body">
        <p class="card-text"><b>Votre commentaire</b> : <?php echo strip_tags($comment['body']); ?></p>
        <p>Vous serez redirigé vers la page d'accueil dans 10 secondes</p>
    </div>
</div>

<?php
$content = ob_get_clean();
require '../templates/layout.php'
?>
