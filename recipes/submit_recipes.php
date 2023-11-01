<?php declare(strict_types=1)?>

<?php
require_once('../includes/class-autoloader.inc.php');
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* include_once("../includes/class-autoloader.inc.php");
include_once('../config/mysql.php');
include_once("../config/user.php");
include_once("../includes/variables.inc.php"); */



$data = $_SERVER['REQUEST_METHOD'] == 'POST';
/* $title = $_POST['title'];
$recipe = $_POST['recipe'];
$errorName = 'Titre';
$errorRecipe = 'Recette'; */

/* $checkInput = new checkInputs(
    $getData
); */

/* try {
    if ($check = $checkInput->checkInputs())
    {
        $db = new PDO('mysql:host=localhost;dbname=my_recipes;charset=utf8', 'root', '');
    }
} catch (TypeError $e) {
    die ('Error ! : '. $e->getMessage() .'Something went wrong...');
} */
// On affiche chaque recette une à une

if ($data && isset($_POST['submit'])) {
    //try {
    //require_once("../includes/class-autoloader.inc.php");
    $getDatas = [
    'title' => $_POST['title'],
    'recipe' => $_POST['recipe']
    ];
    $setRecipe = new RecipeView($getDatas);
    $setRecipe->insertRecipe();

    header('refresh:10, ../index.php?error=none');
    //} catch (Error $e) {
    //die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas dans l\'insertion...') ;
    //}
}

/* if (isset($_COOKIE['REGISTERED_RECIPE']) || isset($_SESSION['REGISTERED_RECIPE'])) {
    $registeredRecipe = [
        'email' => $_COOKIE['REGISTERED_RECIPE'] ?? $_SESSION['REGISTERED_RECIPE'],
    ];
} */

?>


    

<?php $loggedUser = LoginController::checkLoggedStatus()?>
    <?php //if (isset($loggedUser['email']) && !isset($loggedUser['recipe'])):?>
        
        <?php if (isset($loggedUser['email'])  && !isset($_SESSION['REGISTERED_RECIPE'])): ?> 
            <?php //(isset($loggedUserState)):?>
        <section class="container">
        <div class="form-flex">
            <h1>Partagez votre recette !</h1>
            <div class="form">
                <form action="create_recipes.php" method="post">
                    <label for="title" class="label">Titre de la recette :</label>
                    <input name="title" type="text" id="title" placeholder="Votre titre..." class="input">

                    <label for="recipe" class="label">Votre recette :</label>
                    <textarea name="recipe" id="recipe" cols="60" rows="10" placeholder="Renseignez votre recette..."></textarea>

                    <button type="submit" name="submit" class="btn">Envoyer</button>
                </form>
            </div>    
        </div>
    </section>
    
    <?php //endif?>
<!-- start of success message --> 

<?php elseif (isset($_SESSION['REGISTERED_RECIPE'])):?>
    <?php //require_once('signup_success.php')?>
    <?php $setRecipe->displayShareSuccess($getDatas, $loggedUser) ?>
    <?php unset($_SESSION['REGISTERED_RECIPE']) ?>
    <?php else : ?>
        <?php header('Location: ../register.php')?>
<?php endif ?>

<!-- end of success message --> 
<?php 
/* if (
    !isset($_POST['title'])
    || !isset($_POST['recipe'])
    )
{
    echo 'Il faut un titre et une recette pour soumettre le formulaire.';
    return;
} */

?>   

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Love Food - Partage de recttes</title>
</head>
<body> -->
    <!-- Le Header -->

    <?php //include_once('../includes/header.inc.php')?>

<!-- Fin du Header -->

<!--  Le Main -->
<!-- Success message of setRecipes -->
    <!-- <section class="container">
        <div class="form-flex">
            <h1>Votre recette à bien été partagée !</h1>
            <div class="card">
                <div class="card-body">
                    <h5>Rappel de vos informations :</h5>
                    <p><b>Titre de votre recette</b> : <?php //echo strip_tags($title)?></p>
                    <p><b>Votre recette</b> : <?php //echo strip_tags($recipe)?></p>
                    <p><b>Crée par </b> : <?php //echo strip_tags($loggedUser['email'])?></p>
                </div>
            </div>  
        </div>
    </section> -->
<!-- End of Success message of setRecipes -->

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php //include_once('../includes/footer.inc.php');?>

    <!--  Fin du Footer -->
</body>
</html>