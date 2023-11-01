<?php declare(strict_types=1)?>

<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* include_once("../includes/class-autoloader.inc.php");
include_once("../config/mysql.php");
include_once("../config/user.php");
include_once("../includes/variables.inc.php");

$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$title = $_POST['title'];
$recipe = $_POST['recipe'];
$errorName = 'Titre';
$errorRecipe = 'Recette';
$id = $_POST['id'];

$checkInput = new checkInputs(
    $data,
    $title,
    $recipe,    
    $errorName,
    $errorRecipe
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


/* try {
    if ($check = $checkInput->checkInputs()) {
        updateRecipes();
    }
} catch (Error $e) {
    die('Erreur : ' . $e->getMessage() . 'Quelque chose ne va pas...') ;
} */


/* if (
    !isset($_POST['title'])
    || !isset($_POST['recipe'])
    )
{
    echo 'Il faut un titre et une recette pour soumettre le formulaire.';
    return;
} */
include_once("../includes/class-autoloader.inc.php");

$data = $_SERVER['REQUEST_METHOD'] == 'POST';
$getDatas = (int)$_GET['id'];

if ($data && isset($_POST['submit'])) {
    $getDatas = [
        'id' => $_POST['id'],
        'recipe' => $_POST['recipe'],
        'title' => $_POST['title'],
    ];

    $checkId = new RecipeView($getDatas);    
    try {
        if ($checkId->checkId()) {
            $checkId->updateRecipes($getDatas['title'], $getDatas['recipe'], $getDatas['id']);
            header('Location: ../index.php');
        }
    } catch (Error $e) {
        die('Erreur : '. $e->getMessage()) . 'Nous ne pouvons pas éditer cette recette';
    }
}

?>   

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Love Food - Modification de recttes</title>
</head>
<body>
    <-- Le Header -->

    <!-- <?php //include_once('../includes/header.inc.php')?> -->

<!-- Fin du Header -->

<!--  Le Main -->

<section class="container">
        <div class="form-flex">
            <h1>Mettre à jour <?php echo strip_tags($getDatas['title']) ?></h1>
            <div class="form">
                <form action="submit_update_recipes.php" method="post">
                    <div class="visually-hidden">
                        <label for="id" class="label">Identifiant de la recette</label>
                        <input type="hidden" class="input" name="id" id="id" value="<?php echo($getDatas['id'])?>"/>
                    </div>
                    <label for="title" class="label">Titre de la recette :</label>
                    <input name="title" type="text" id="title" class="input" placeholder="Votre titre..." value="<?php echo strip_tags($getDatas['title']) ?>"/>

                    <label for="recipe" class="label">Votre recette :</label>
                    <textarea name="recipe" id="recipe" cols="60" rows="10" placeholder="Renseignez votre recette..."> <?php echo strip_tags($getDatas['recipe']) ?></textarea>

                    <button type="submit" class="btn" name="submit" >Modifier</button>
                </form>
            </div>    
        </div>
    </section>

    <!-- Success message -->

    <section class="container">
        <div class="form-flex">
            <h1>La modification de votre recette à bien été partagée !</h1>
            <div class="card">
                <div class="card-body">
                    <h5>Rappel de vos informations :</h5>
                    <p><b>Titre de votre recette</b> : <?php echo strip_tags($getDatas['title']) ?></p>
                    <p><b>Votre recette</b> : <?php echo strip_tags($getDatas['recipe']) ?></p>
                    <p><b>Modifié par </b> : <?php echo strip_tags($loggedUser['email']) ?></p>
                </div>
            </div>  
        </div>
    </section>

    <!-- End of success message -->

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php //include_once('../includes/footer.inc.php'); ?>

    <!--  Fin du Footer -->
<!-- </body>
</html> -->