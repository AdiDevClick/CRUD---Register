<?php declare(strict_types=1)?>

<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once('../config/mysql.php');
//include_once('../includes/variables.inc.php');

/* $getData = $_GET;

if (!isset($getData['id']) && is_numeric($getData['id'])) {
    echo('Il faut un identifiant de recette pour la modifier.');
    return;
}

    $getRecipesIdStatement->execute([
        'id' => $getData['id'],
    ]) or die(print_r($db->errorInfo()));
 */

include_once("../includes/class-autoloader.inc.php");

/* $data = $_SERVER['REQUEST_METHOD'] == 'POST';
$getDatas = (int)$_GET['id'];

if ($data && isset($_POST['submit'])) {
    $getDatas = $_POST['id'];
    $checkId = new RecipeView($getDatas);
    try {
        if ($checkId->checkId()) {
            //$checkId->deleteRecipe();
            header('Location: ../index.php');
        }
    } catch (Error $e) {
        die('Erreur : '. $e->getMessage()) . 'Nous ne pouvons pas éditer cette recette';
    }
} */

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Love Food - Modifier votre recette</title>
</head>
<body>
    <!-- Le Header -->

    <?php include_once('../includes/header.inc.php')?>

    <!-- Fin du Header -->

    <!--  Le Main -->

    <?php include_once('../recipes/submit_update_recipes.php')?>
    <!-- <section class="container">
        <div class="form-flex">
            <h1>Mettre à jour <?php //echo strip_tags($recipe['title'])?></h1>
            <div class="form">
                <form action="submit_update_recipes.php" method="post">
                    <div class="visually-hidden">
                        <label for="id" class="label">Identifiant de la recette</label>
                        <input type="hidden" class="input" name="id" id="id" value="<?php //echo($getData['id'])?>"/>
                    </div>
                    <label for="title" class="label">Titre de la recette :</label>
                    <input name="title" type="text" id="title" class="input" placeholder="Votre titre..." value="<?php //echo strip_tags($recipe['title'])?>"/>

                    <label for="recipe" class="label">Votre recette :</label>
                    <textarea name="recipe" id="recipe" cols="60" rows="10" placeholder="Renseignez votre recette..."> <?php //echo strip_tags($recipe['recipe'])?></textarea>

                    <button type="submit" class="btn" name="submit" >Modifier</button>
                </form>
            </div>    
        </div>
    </section> -->

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('../includes/footer.inc.php') ?>

    <!--  Fin du Footer -->
</body>
</html>