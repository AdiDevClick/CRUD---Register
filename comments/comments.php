<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<form action="<?= strip_tags($rootUrl) . 'recettes/comments/post_create.php' ?>" method="POST">
<!--<form action="<?php //echo($rootUrl . 'recettes/recipes/read.php');?>" method="POST"> -->
    <div class="mb-3 hidden">
    <!-- <div class="mb-3 visually-hidden"> -->
        <input class="form-control" type="text" name="recipe_id" value="<?= $getID ?>" />
    </div>
    <div class="mb-3">
        <label for="comment" class="form-label">Postez un commentaire</label>
        <textarea class="form-control" placeholder="Soyez respectueux/se, nous sommes humain(e)s." id="comment" name="comment"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>