<?php declare(strict_types=1)?>

<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("../includes/class-autoloader.inc.php");

$serverData = $_SERVER['REQUEST_METHOD'] == 'POST';
//if ($getDatas = isset($_GET)){

/***
 * Grabing URL ID from index page and fetching rows datas
 */
if(isset($_GET) && ($getDatas = $_GET['id'])) {
    $getDatas = $_GET['id'];    
    $idDatas = new RecipeView($getDatas);
    //$idDatas->checkId();
    $getTitle = $idDatas->getRecipeId();
    //echo $getTitle['title'];
} else {
    header('Location: ../index.php?error=noId');
}

if ($serverData && isset($_POST['submit'])) {
    $getDatas = $_POST['id'];
 /*    $datas = new Recipecontroller($postDatas);
    $get = $datas->getRecipeId($postDatas); */
    
    $checkId = new RecipeView($getDatas);

    try {
        if ($checkId->checkId()) {
            $checkId->deleteRecipe();
            header('Location: ../index.php?delete=success');
        } else {
            header('Location: ../index.php?delete=error');
        }
    } catch (Error $e) {
        die('Erreur : '. $e->getMessage()) . 'Nous ne pouvons pas supprimer cette recette';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Love Food - Suppression de votre recette</title>
</head>
<body>
    <!-- Le Header -->

    <?php include_once('../includes/header.inc.php')?>

    <!-- Fin du Header -->

    <!--  Le Main -->

    <?php //include_once('../recipes/submit_delete_recipes.php')?>
    <section class="container">
        <div class="form-flex">
            <?php //if (isset($_GET['title'])) :?>                     
            <h1>Suppression de la recette : <?php echo htmlspecialchars($getTitle['title'])?> ?</h1>
            <?php //endif?>
            <div class="form">
                <form action="delete_recipes.php" method="post">
                    <div class="visually-hidden">
                        <input type="hidden" class="input" name="id" id="id" value="<?php echo strip_tags($getDatas)?>"/>
                    </div>

                    <button type="submit" name="submit" class="btn btn-alert">Supprimer définitivement</button>
                </form>
            </div>    
        </div>
    </section>

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('../includes/footer.inc.php') ?>

    <!--  Fin du Footer -->
</body>
</html>