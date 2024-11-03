<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');

$serverData = $_SERVER['REQUEST_METHOD'] === 'POST';
$loggedUser = LoginController::checkLoggedStatus();
$sessionName = 'ID_RECIPE';

/**
 * Grabing URL ID from index page and fetching rows datas
 */
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $getDatas = $_GET['id'];
    $id = new RecipeView($getDatas);
    // Grab author & title and create session
    $recipeInfos = $id->retrieveFromRecipesTable(['author', 'title'], $id->getSelfData(), $sessionName);
}
/**
 * Lors d'un submit si la recette existe
 */
if ($serverData && isset($_POST['submit'])) {
    $postID = $_POST['id'];
    // Retrieve session data
    $sessionID = $_SESSION[$sessionName][$sessionName] ?? null;
    // Grab author & title
    if (is_numeric($postID) && (is_numeric($sessionID) && $sessionID == $postID)) {
        $id = new RecipeView($postID);
        $recipeInfos = $id->retrieveFromRecipesTable(['author', 'title'], $id->getSelfData(), $sessionName);
    }
    // If POST ID && GET ID && SessionID aren't same it will fail
    try {
        if ($recipeInfos['author'] === $loggedUser['email']) {
            $id->deleteRecipe();
            unset($_SESSION[$sessionName]);
            header('Location: ../index.php?delete=success');
            exit("Il n'y a malheureusement plus rien à voir !");
        } else {
            unset($_SESSION[$sessionName]);
            header('Location: ../index.php?delete=error');
            exit("Il n'y a malheureusement plus rien à voir !");
        }
    } catch (\Throwable $th) {
        unset($_SESSION[$sessionName]);
        die('Erreur : '. $e->getMessage()) . ' Nous ne pouvons pas supprimer cette recette. ';
    }
}
// If it belongs to the user, display delete button

ob_start();
?>
<?php if ($recipeInfos['author'] === $loggedUser['email']) : ?>
    <?php $title = "We Love Food - Suppression de votre recette"?>
    <section class="container">
        <div class="form-flex">
            <?php //if (isset($_GET['title'])) :?>
            <h1>Suppression de la recette : <?= strip_tags($recipeInfos['title'])?> ?</h1>
            <?php //endif?>
            <div class="form">
                <form action="delete_recipes.php" method="post">
                    <div class="visually-hidden">
                        <input type="hidden" class="input" name="id" id="id" value="<?= strip_tags($getDatas)?>"/>
                    </div>
                    <button type="submit" name="submit" class="btn btn-alert">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </section>
    <?php else : ?>
        <?php session_destroy()?>
        <?php header('Location: ../index.php?delete=error')?>
        <?php exit("Il n'y a malheureusement plus rien à voir !") ?>
<?php endif ?>
<?php $content = ob_get_clean()?>

<?php
require '../templates/layout.php';
