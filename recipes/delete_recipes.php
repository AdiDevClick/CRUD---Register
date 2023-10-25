<?php
declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../config/mysql.php');
include_once('../includes/variables.inc.php');


$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$inputId = $_GET['id'];
$errorId = 'ID';

/* if (!isset($getData['id']) && is_numeric($getData['id']))
{
    echo ('Il faut un identifiant de recette pour la modifier.');
    return;
} */

$checkId = new CheckId($data, (int)$inputId, $errorId);


try {
    if ($check = $checkId->checkIds()) {
        $sqlQuery = 'SELECT * FROM recipes WHERE recipe_id = :id';
        $getRecipesIdStatement = $db->prepare($sqlQuery);
        $getRecipesIdStatement->execute([
            'id' => $inputId,
        ]);
        $recipe = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC);
    }
} catch (Error $e) {
    die('Erreur : '. $e->getMessage());
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
    <section class="container">
        <div class="form-flex">
            <h1>Suppression de la recette : <?php echo $recipe['title'] ?> ?</h1>
            <div class="form">
                <form action="submit_delete_recipes.php" method="post">
                    <div class="visually-hidden">
                        <label for="id" class="label">Identifiant de la recette</label>
                        <input type="hidden" class="input" name="id" id="id" value="<?php echo($inputId)?>"/>
                    </div>

                    <button type="submit" class="btn btn-alert">Supprimer définitivement</button>
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