<?php
declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
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
);

/* try {
    if ($check = $checkInput->checkInputs())
    {
        $db = new PDO('mysql:host=localhost;dbname=my_recipes;charset=utf8', 'root', '');
    }
} catch (TypeError $e) {
    die ('Error ! : '. $e->getMessage() .'Something went wrong...');
} */
// On affiche chaque recette une à une


try {
    if ($check = $checkInput->checkInputs()) {
        $sqlQuery = 'UPDATE recipes SET title = :title, recipe = :recipe WHERE recipe_id = :id';

        $insertRecipeStatement = $db->prepare($sqlQuery);

        $insertRecipeStatement->execute([
            'title' => $title,
            'recipe' => $recipe,
            'id' => $id,
        ]);
    }
} catch (Error $e) {
    die('Erreur : ' . $e->getMessage() . 'Quelque chose ne va pas...') ;
}


/* if (
    !isset($_POST['title'])
    || !isset($_POST['recipe'])
    )
{
    echo 'Il faut un titre et une recette pour soumettre le formulaire.';
    return;
} */


?>   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Love Food - Modification de recttes</title>
</head>
<body>
    <!-- Le Header -->

    <?php include_once('../includes/header.inc.php')?>

<!-- Fin du Header -->

<!--  Le Main -->

    <!-- Success message -->

    <section class="container">
        <div class="form-flex">
            <h1>La modification de votre recette à bien été partagée !</h1>
            <div class="card">
                <div class="card-body">
                    <h5>Rappel de vos informations :</h5>
                    <p><b>Titre de votre recette</b> : <?php echo strip_tags($title) ?></p>
                    <p><b>Votre recette</b> : <?php echo strip_tags($recipe) ?></p>
                    <p><b>Modifié par </b> : <?php echo($loggedUser['email']) ?></p>
                </div>
            </div>  
        </div>
    </section>

    <!-- End of success message -->

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('../includes/footer.inc.php'); ?>

    <!--  Fin du Footer -->
</body>
</html>