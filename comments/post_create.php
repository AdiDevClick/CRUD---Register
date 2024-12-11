<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";

// $client_Side_Datas = file_get_contents("php://input");
// $data = json_decode($client_Side_Datas, true);
// echo json_encode($data);

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
// Trouver la note de l'utilisateur
$maxKey = array_reduce(array_keys($_POST), function ($max, $key) {
    if ($max < 0) $max = 0;
    return is_numeric($key) && $key > $max ? $key : $max;
}, PHP_INT_MIN);

// Filtrer le tableau pour ne garder que la note de l'utilisateur et toutes les autres clés
$filteredArray = array_filter($_POST, function ($key) {
    return !is_numeric($key);
}, ARRAY_FILTER_USE_KEY);

$filteredArray['review'] = $maxKey;
$filteredArray['session_name'] = 'REGISTERED_COMMENT';

$insertComment = new RecipeView(
    $filteredArray
);
$comment = $insertComment->insertComment($filteredArray);

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
<p><?php echo $errorMessage ?></p>

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