<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<form class="comment-form" action="<?= strip_tags($rootUrl) . 'recettes/comments/post_create.php' ?>" method="POST">
<!--<form action="<?php //echo($rootUrl . 'recettes/recipes/read.php');?>" method="POST"> -->
    <div class="mb-3 hidden">
    <!-- <div class="mb-3 visually-hidden"> -->
        <input class="form-control" type="text" name="recipe_id" value="<?= $getID ?>" />
    </div>
    <div class="comment-form__note-star">
        <p class="comment-form__note-text">Définissez votre note</p>
        <div class="comment-form__star-container">
            <?php
                echo display_5_stars("0", 0);
            ?>
        </div>
    </div>
    <div class="comment-form__title">
        <label for="title" class="form-label">Titre rapide</label>
        <input id="title" class="form-control" type="text" name="title" placeholder="Description rapide" />
    </div>
    <div class="comment-form__body">
        <label for="comment" class="form-label">Votre commentaire</label>
        <textarea class="form-control" placeholder="Soyez respectueux/se, nous sommes humain(e)s." id="comment" name="comment"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>