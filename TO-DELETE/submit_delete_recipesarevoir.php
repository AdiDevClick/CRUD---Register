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
/* $getId = [
    $_GET['id']
]; */
if ($data && isset($_POST['submit'])) {
    $id = isset($_GET['id']);
    
    /* $getDatas = [
        //'id' => $_GET['id'],
        //'id' => isset($_POST['id'])
    ]; */
    echo($id);
    $checkId = new RecipeView($id);
    try {
        if ($checkId->checkIds()) {
            echo($id);
            $checkId->deleteRecipe();
        }
        /* if ($checkId->checkIds()) {
            $checkId->getRecipeId((int)$getDatas);
            $deletingRecipe = [
                'id' => $getDatas
            ];
            $_SESSION['DELETING_RECIPE'] = $deletingRecipe;
            echo 'Session okay !';
            //return $deletingRecipe;
        }
        if (isset($deletingRecipe)) {
            $checkId->deleteRecipeId((int)$getDatas);
            echo 'On peut delete !';
            unset($deletingRecipe);
        } */
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
<section class="container">
        <div class="form-flex">
            <h1>Suppression de la recette : <?php //echo $recipe['title'] ?> ?</h1>
            <div class="form">
                <form action="submit_delete_recipes.php" method="post">
                    <div class="visually-hidden">
                        <label for="id" class="label">Identifiant de la recette</label>
                        <input type="hidden" class="input" name="id" id="id" value="<?php echo($id)?>"/>
                    </div>

                    <button type="submit" name="submit" class="btn btn-alert">Supprimer définitivement</button>
                </form>
            </div>    
        </div>
    </section>