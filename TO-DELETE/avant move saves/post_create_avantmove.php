<?php declare(strict_types=1)?>

<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");

$postData = $_POST;

if (
    !isset($postData['comment']) &&
    !isset($postData['recipe_id']) &&
    !is_numeric($postData['recipe_id'])
    )
{
	echo('Le commentaire est invalide.');
    return;
}
$loggedUser = LoginController::checkLoggedStatus();
if (!isset($loggedUser)) {
    echo('Vous devez être authentifié pour soumettre un commentaire');
    return;
}
/* echo $loggedUser ['user'][1] . 'test';

foreach($postData as $recipes => $value) {
    echo($recipes .' => '. $value . '<br>');
} */

/* $comment = $postData['comment'];
$recipeId = $postData['recipe_id']; */

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


/* $insertRecipe = $db->prepare('INSERT INTO comments(comment, recipe_id, user_id) VALUES (:comment, :recipe_id, :user_id)');
$insertRecipe->execute([
    'comment' => $comment,
    'recipe_id' => $recipeId,
    'user_id' => retrieve_id_from_user_mail($loggedUser['email'], $users),
]);
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Création de commentaire</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">

    <?php include_once('../includes/header.inc.php') ?>
        <h1>Commentaire ajouté avec succès !</h1>        
        <div class="card">            
            <div class="card-body">
                <p class="card-text"><b>Votre commentaire</b> : <?php echo strip_tags($getDatas['comment']); ?></p>
            </div>
        </div>
    </div>
    <?php include_once('../includes/footer.inc.php') ?>
</body>
</html>