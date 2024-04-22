<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}
//ob_start();


// include_once("../includes/class-autoloader.inc.php");
// include_once('../logs/customErrorHandlers.php');
// require_once(__DIR__ . './' . "includes/class-autoloader.inc.php");
// require_once(__DIR__ . "/logs/customErrorHandlers.php");

$rootUrl = Functions::getRootUrl();
echo $rootUrl;
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];
$loggedUser = [];
// On affiche chaque recette une à une
if ($data && isset($_POST['submit'])) {
    //try {
    //require_once("../includes/class-autoloader.inc.php");
    $getDatas = [
    'title' => $_POST['title'],
    'step_1' => $_POST['step_1'],
    'step_2' => $_POST['step_2'],
    'step_3' => $_POST['step_3']
    ];
    $setRecipe = new RecipeView($getDatas);
    $setRecipe->insertRecipe();
    $err = CheckInput::getErrorMessages();

    if (count($err) > 0) {
        print_r($err);
        session_destroy();
    } else {
        header('refresh:10, ../index.php?success=recipe-shared');
        // session_destroy();
    }
        //header('Location: ../index.php?error=none');
    //unset($_SESSION['REGISTERED_RECIPE']);
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
print_r($loggedUser);
$errorMessage = CheckInput::showErrorMessage();
echo 'voici le message => ' . $errorMessage
?>
    <?php //if (isset($loggedUser['email']) && !isset($loggedUser['recipe'])):?>
        <?php if (isset($loggedUser['email'])  && !isset($_SESSION['REGISTERED_RECIPE'])): ?> 
            <?php //(isset($loggedUserState)):?>
        <section class="container">
            <div class="form-flex">
                <h1>Partagez votre recette !</h1>
                <div class="form">
                    <form action="create_recipes.php" method="post">
                        <label for="title" class="label">Titre de votre recette :</label>
                        <input name="title" type="text" id="title" placeholder="Votre titre..." class="input">

                        <label for="step_1" class="label">Votre première étape :</label>
                        <textarea name="step_1" id="step_1" cols="60" rows="10" placeholder="Renseignez votre première étape..."></textarea>
                        
                        <label for="step_2" class="label">Votre deuxième étape :</label>
                        <textarea name="step_2" id="step_2" cols="60" rows="10" placeholder="Renseignez  votre deuxième étape..."></textarea>
                        
                        <label for="step_3" class="label">Votre votre troisième étape :</label>
                        <textarea name="step_3" id="step_3" cols="60" rows="10" placeholder="Renseignez  votre troisième étape..."></textarea>

                        <button type="submit" name="submit" class="btn">Partagez votre recette</button>
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