<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";

$data = $_SERVER['REQUEST_METHOD'] === 'POST';
// $client_Side_Datas = file_get_contents("php://input");
// $data = json_decode($client_Side_Datas, true);
// echo json_encode($data);
if (!isset($loggedUser)) {
    echo 'Vous devez être authentifié pour soumettre un commentaire ';
    return;
}
if (
    $data && isset($_POST['comment']) &&
    isset($_POST['recipe_id']) &&
    is_numeric($_POST['recipe_id'])
) {
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

    // $content = ob_get_clean();
    // ob_end_clean();

    // header('refresh:5, ../index.php?error=none');

    //header('refresh:5, '.Functions::getUrl().'?error=none');
    $title = "Création de commentaire";
    $err = CheckInput::getErrorMessages();

    $errorMessage = CheckInput::showErrorMessage();
    // Vérifie si $comment est un JSON valide $decodedComment = json_decode($comment
    // $decodedComment = json_decode($comment, true);
    // if ($decodedComment) {
    //     if ($$decodedComment["status"] !== 200) {
    //         $errorMessage = 'Une erreur est survenu';
    //     }
    // }
    if ($comment["status"] === 403 || $comment["status"] === 500) {
        $errorMessage = 'Une erreur est survenu';
    }
    // $errorMessage = CheckInput::showErrorMessage();
    if (isset($_SESSION['REGISTERED_COMMENT'])) {
        unset($_SESSION['REGISTERED_COMMENT']);
        header('refresh:1, read.php?id=' . $_GET['id'] . '&shared=success');
    }
    // die(var_dump($errorMessage));
    // <body class="d-flex flex-column min-vh-100">
    //   <div class="container"> -->

}
// $loggedUser = LoginController::checkLoggedStatus();

// Trouver la note de l'utilisateur

?>

<form data-endpoint="<?= AJAXCONTROLLER ?>"
    data-elements='{
        "comment_date": ".js-created_at",
        "title": ".js-title",
        "comment": ".js-comment",
        "comment_id": ".js-comment-id",
        "user_id": ".js-user-id"
        }'
    class="comment-form" action="<?= strip_tags($rootUrl) . 'recettes/recipes/read.php?id=' . $_GET['id'] ?>" method="POST">
    <!-- <form data-target="../recipes/Process_PreparationList.php" class="comment-form" action="<?php //strip_tags($rootUrl) . 'recettes/comments/post_create.php' 
                                                                                                    ?>" method="POST"> -->
    <!--<form action="<?php //echo($rootUrl . 'recettes/recipes/read.php');
                        ?>" method="POST"> -->
    <div class="mb-3 hidden">
        <!-- <div class="mb-3 visually-hidden"> -->
        <input class="form-control" type="text" data-name="<?= $loggedUser['name'] ?>" id="<?= $loggedUser['userId'] ?>" name="recipe_id" value="<?= $getID ?>" />
    </div>
    <?php // $errorMessage = $err['invalidReview'] ?? $err['invalidComment'] ?? $err['invalidTitle'] ?? ''; 
    ?>
    <?php // $errorMessage = CheckInput::showErrorMessage() 
    ?>
    <?php if (isset($errorMessage) && !empty($errorMessage)) : ?>
        <div>
            <p class="alert-error"><?php echo strip_tags($errorMessage) ?></p>
        </div>
    <?php endif ?>
    <div class="comment-form__note-star">
        <p class="comment-form__note-text">Définissez votre note</p>
        <?php if (isset($err) && array_key_exists('invalidReview', $err)) : ?>
            <div class="comment-form__star-container input-error">
            <?php else: ?>
                <div class="comment-form__star-container">
                <?php endif ?>
                <?php
                echo display_5_stars(isset($filteredArray) ? (string) $filteredArray['review'] : "0", 0);
                ?>
                </div>
            </div>
            <!-- <div class="comment-form__title"> -->
            <label for="title" class="form-label">Titre rapide</label>
            <?php if (isset($err) && array_key_exists('invalidTitle', $err)) : ?>
                <input id="title" class="form-control input_error" type="text" name="title" placeholder="<?php echo strip_tags($err['invalidTitle'] ?? 'Description rapide...') ?>" value="<?php echo strip_tags($filteredArray['title']) ?>" />
            <?php else: ?>
                <input id="title" class="form-control" type="text" name="title" value="<?php echo strip_tags($filteredArray['title'] ?? '') ?>" placeholder="Description rapide..." />
            <?php endif ?>

            <!-- </div> -->
            <!-- <div class="comment-form__body"> -->
            <label for="comment" class="form-label">Votre commentaire</label>
            <?php if (isset($err) && array_key_exists('invalidComment', $err)) : ?>
                <textarea class="form-control input_error" placeholder="<?php echo strip_tags($err['invalidComment'] ?? 'Soyez respectueux/se, nous sommes humain(e)s.') ?>" id="comment" name="comment" value="<?php echo strip_tags($filteredArray['comment']) ?>"></textarea>
            <?php else: ?>
                <textarea class=" form-control" placeholder="Soyez respectueux/se, nous sommes humain(e)s." id="comment" name="comment"><?php echo htmlspecialchars($filteredArray['comment'] ?? '') ?></textarea>
            <?php endif ?>

            <!-- </div> -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
</form>