<?php
declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("../includes/class-autoloader.inc.php");

$data = $_SERVER['REQUEST_METHOD'] == 'POST';
$getDatas = (int)$_GET['id'];

if ($data && isset($_POST['submit'])) {
    $getDatas = $_POST['id'];
    $checkId = new RecipeView($getDatas);    
    try {
        if ($checkId->checkId()) {
            $checkId->deleteRecipe();
            header('Location: ../index.php');
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
            <h1>Suppression de la recette : <?php //echo $recipe['title']?> ?</h1>
            <div class="form">
                <form action="delete_recipes.php" method="post">
                    <div class="visually-hidden">
                        <label for="id" class="label">Identifiant de la recette</label>
                        <input type="hidden" class="input" name="id" id="id" value="<?php echo($getDatas)?>"/>
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