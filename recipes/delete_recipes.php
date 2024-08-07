<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');

$serverData = $_SERVER['REQUEST_METHOD'] === 'POST';
//if ($getDatas = isset($_GET)){

/***
 * Grabing URL ID from index page and fetching rows datas
 */
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $getDatas = $_GET['id'];
    $idDatas = new RecipeView($getDatas);
    $getTitle = $idDatas->getRecipeId();
} else {
    // header('Location: ../index.php?error=no-delete-id');
    //throw new Exception('La recette est introuvable');
}

if ($serverData && isset($_POST['submit'])) {
    $getDatas = $_POST['id'];
    /*    $datas = new Recipecontroller($postDatas);
       $get = $datas->getRecipeId($postDatas); */
    $checkId = new RecipeView($getDatas);
    $getTitle = $checkId->getRecipeId();
    try {
        if ($checkId->checkId()) {
            $checkId->deleteRecipe();
            header('Location: ../index.php?delete=success');
            exit("Il n'y a malheureusement plus rien à voir !");
        } else {
            header('Location: ../index.php?delete=error');
            exit("Il n'y a malheureusement plus rien à voir !");
        }
    } catch (Error $e) {
        die('Erreur : '. $e->getMessage()) . 'Nous ne pouvons pas supprimer cette recette. ';
    }
}
ob_start();

?>
<?php $title = "We Love Food - Suppression de votre recette"?>
<section class="container">
    <div class="form-flex">
        <?php //if (isset($_GET['title'])) :?>
        <h1>Suppression de la recette : <?= strip_tags($getTitle['title'])?> ?</h1>
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
<?php $content = ob_get_clean()?>

<?php require('../templates/layout.php');
