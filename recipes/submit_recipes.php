<?php declare(strict_types=1);

/* if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
} */
//ob_start();


include_once('../includes/class-autoloader.inc.php');
$rootUrl = Functions::getRootUrl();
echo $rootUrl;
$data = $_SERVER['REQUEST_METHOD'] == 'POST';
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
    //header('Location: ../index.php?error=none');
    //unset($_SESSION['REGISTERED_RECIPE']);
    header('refresh:10, ../index.php?success=recipe-shared');
    //unset($_SESSION['REGISTERED_RECIPE']);
    //exit();
    //header('refresh:10, ../index.php');
    //$content = ob_get_contents();
    //$content = ob_get_clean();
    //} catch (Error $e) {
    //die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas dans l\'insertion...') ;
    //}
}

//$content = ob_get_clean();
//$content = ob_end_flush();

//ob_start()
$loggedUser = LoginController::checkLoggedStatus();
echo ' le array dans submit recipe';
print_r($loggedUser)
?>
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
            <?php //endif?>
            <?php //session_destroy()?>
        </section>
<!-- start of success message -->
<?php //ob_start()?>
<?php elseif (isset($_SESSION['REGISTERED_RECIPE'])):?>
    <?php //require_once('signup_success.php')?>
    <?php //ob_start()?>
    <?php //ob_get_contents()?>
    <?php $setRecipe->displayShareSuccess($getDatas, $loggedUser)?>
    <?php unset($_SESSION['REGISTERED_RECIPE'])?>
    <?php //session_destroy()?>
    <?php //ob_get_contents()?>
    <?php //header('refresh:10, ../index.php?error=none')?>
    <?php else:?>
        <?php session_destroy()?>
        <?php header('Location: ../register.php?failed=recipe-creation')?>
        <?php exit()?>
<?php endif?>
<!-- end of success message --> 
<?php //$content = ob_end_flush()?>
<?php //$content = ob_get_clean()?>