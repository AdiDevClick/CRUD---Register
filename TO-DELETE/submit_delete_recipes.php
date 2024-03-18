<?php


if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once("../config/mysql.php");
//include_once("../includes/variables.inc.php");
include_once("../includes/class-autoloader.inc.php");

$data = $_SERVER['REQUEST_METHOD'] == 'POST';


/* if ($data) {
    $getDatas = $GET['id'];
        /* 'id' => $GET['id'],
        ];
    $checkId = new CheckId($data, (int)$getDatas, 'ID');
    //$check = $checkId->checkIds();
    //$setRecipe = new RecipeView($getDatas);
    //$setRecipe->getRecipeId((int)$getDatas);
    //$checkId->checkIds();
    //header('Location: ../index.php');
} */

//$getDatas = $GET['id'];
echo $getDatas;

if ($data) {
    $getDatas = (int) $_POST['id'];
    $checkId = new RecipeController($getDatas);

    try {
        if ($checkId->checkId()) {

            $checkId->deleteRecipeId($getDatas);
        }
    } catch (Error $e) {
        die('Erreur : '. $e->getMessage()) . 'Nous ne pouvons pas supprimer cette recette';
    }
}

/* try {
    $checkId = new CheckId($data, (int)$getDatas, 'ID');
    if ($checkId->checkIds()) {
       /*  $sqlQuery = 'DELETE FROM recipes WHERE recipe_id = :id';
        $deteRecipeStatement = $db->prepare($sqlQuery);
        $deteRecipeStatement->execute([
            'id' => $inputId,
        ]);
        $setRecipe = new RecipeView($getDatas);
        $setRecipe->getRecipeId((int)$getDatas);
        header('Location: ../index.php');
    }
} catch (Error $e) {
    die('Erreur : ' . $e->getMessage());
} */
/* $redirectUrl = 'Location: contact.php';
$url = '/recettes/submit_contact.php'; */
//getUrl() === $url

?>
<!-- <section class="container">
        <div class="form-flex">
            <h1>Suppression de la recette : <?php //echo $recipe['title']?> ?</h1>
            <div class="form">
                <form action="delete_recipes.php" method="post">
                    <div class="visually-hidden">
                        <label for="id" class="label">Identifiant de la recette</label>
                        <input type="hidden" class="input" name="id" id="id" value="<?php //echo($getDatas)?>"/>
                    </div>

                    <button type="submit" name="submit" class="btn btn-alert">Supprimer définitivement</button>
                </form>
            </div>    
        </div>
    </section> -->