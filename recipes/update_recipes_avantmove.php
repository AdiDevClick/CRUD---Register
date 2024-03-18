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

include_once("../includes/class-autoloader.inc.php");

$data = $_SERVER['REQUEST_METHOD'] == 'POST';
//$getDatas = $_GET['id'];


/* if(isset($_GET) && (isset($_GET['id']))) {
    $getDatas = $_GET['id'];
    $checkId = new RecipeView($getDatas);
    $getInfos = $checkId->getRecipeInfoById();
    try {
        if ($data && isset($_POST['submit'])) {
            $getDatas = [
                'recipe_id' => $_POST['id'],
                'recipe' => $_POST['recipe'],
                'title' => $_POST['title'],
            ];
            $checkId = new RecipeView($getDatas);
            if ($checkId->checkId()) {
                $checkId->updateRecipeInfoById();
            } else {
                header('Location: ../index.php?update=error');
            }
        }
    } catch (Error $e) {
        die('Erreur : '. $e->getMessage()) . 'Nous ne pouvons pas éditer cette recette';
    }
} */


/***
 * Grabing URL ID from index page and fetching rows datas
 */
/* if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $getDatas = $_GET['id'];
    //$idDatas = new RecipeView($getDatas);
    $checkId = new RecipeView($getDatas);
    //$idDatas->checkId();
    //$getInfos = $idDatas->getRecipeInfoById();
    $getInfos = $checkId->getRecipeInfoById();
} else {
    //header('Location: ../index.php?error=noId');
}

if ($data && isset($_POST['submit'])) {
    $getDatas = [
        'recipe_id' => $_POST['id'],
        'recipe' => $_POST['recipe'],
        'title' => $_POST['title'],
    ];
    //try {
    $checkId = new RecipeView($getDatas);
    if ($checkId->checkId()) {
        //$checkId->checkId();
        //$checkId->updateRecipes($getDatas['title'], $getDatas['recipe'], $getDatas['recipe_id']);

        //$checkId->updateRecipeInfoById($getDatas['title'], $getDatas['recipe'], $getDatas['recipe_id']);
        $checkId->updateRecipeInfoById();
        header('refresh:10, ../index.php?update=success');
        //header('Location: ../index.php?update=success');
    } else {
        header('Location: ../index.php?update=error');
    }
    /* } catch (Error $e) {
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
    <?php include_once("../recipes/submit_update_recipes.inc.php");?>
    <?php //include_once("../includes/class-autoloader.inc.php");?>
    <!-- <?php //$loggedUser = LoginController::checkLoggedStatus()?>
    <?php //if (isset($_SESSION['LOGGED_USER']) && !isset($_SESSION['UPDATED_RECIPE'])):?>
    <section class="container">
        <div class="form-flex">
            <h1>Mettre à jour : <?php //echo strip_tags($getInfos['title'])?></h1>
            <div class="form">
                <form action="update_recipes.php" method="post">
                    <div class="visually-hidden">
                        <input type="hidden" class="input" name="id" id="id" value="<?php //echo strip_tags($getDatas)?>"/>
                    </div>
                    <label for="title" class="label">Titre de la recette :</label>
                    <input name="title" type="text" id="title" class="input" placeholder="Votre titre..." value="<?php //echo strip_tags($getInfos['title'])?>"/>

                    <label for="recipe" class="label">Votre recette :</label>
                    <textarea name="recipe" id="recipe" cols="60" rows="10" placeholder="Renseignez votre recette..."> <?php //echo strip_tags($getInfos['recipe'])?></textarea>

                    <button type="submit" class="btn" name="submit" >Modifier</button>
                </form>
            </div>    
        </div>
    </section> -->

    <!-- Success message -->
    <?php //elseif (isset($_SESSION['UPDATED_RECIPE'])):?>
    <?php //$checkId->displayUpdateSuccess($getDatas, $loggedUser)?>
    <?php //unset($_SESSION['UPDATED_RECIPE'])?>
    <?php //else :?>
        <?php //header('Location: ../register.php')?>
    <?php //endif ?>            
    <!--  <section class="container">
        <div class="form-flex">
            <h1>La modification de votre recette à bien été partagée !</h1>
            <div class="card">
                <div class="card-body">
                    <h5>Rappel de vos informations :</h5>
                    <p><b>Titre de votre recette</b> : <?php //echo strip_tags($getDatas['title'])?></p>
                    <p><b>Votre recette</b> : <?php //echo strip_tags($getDatas['recipe'])?></p>
                    <p><b>Modifié par </b> : <?php //echo strip_tags($loggedUser['email'])?></p>
                </div>
            </div>  
        </div>
    </section> -->

    <!-- End of success message -->

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('../includes/footer.inc.php') ?>

    <!--  Fin du Footer -->
</body>
</html>