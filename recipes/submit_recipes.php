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
    'step_3' => $_POST['step_3'],
    'step_4' => $_POST['step_4'],
    'step_5' => $_POST['step_5'],
    'step_6' => $_POST['step_6'],
    'ingredient' => $_POST['time'],
    ];
    print_r($getDatas);
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
                <!-- <h1>Partagez votre recette !</h1> -->
                <div class="form-recipe">
                    <form action="create_recipes.php" method="post">
                        <label for="title" class="label">Titre de votre recette :</label>
                        <input class="form" name="title" type="text" id="title" placeholder="Votre titre..." class="input">

                        <label for="step_1" class="label">Votre première étape :</label>
                        <textarea class="" name="step_1" id="step_1" cols="60" rows="3" placeholder="Renseignez votre première étape..."></textarea>
                        
                        <label for="step_2" class="label">Votre deuxième étape :</label>
                        <textarea name="step_2" id="step_2" cols="60" rows="3" placeholder="Renseignez  votre deuxième étape..."></textarea>
                        
                        <label for="step_3" class="label">Votre troisième étape :</label>
                        <textarea name="step_3" id="step_3" cols="60" rows="3" placeholder="Renseignez  votre troisième étape..."></textarea>
                        
                        <label for="step_4" class="label">Votre quatrième étape :</label>
                        <textarea name="step_4" id="step_4" cols="60" rows="3" placeholder="Renseignez  votre quatrième étape..."></textarea>
                        
                        <label for="step_5" class="label">Votre cinquième étape :</label>
                        <textarea name="step_5" id="step_5" cols="60" rows="3" placeholder="Renseignez  votre cinquième étape..."></textarea>
                        
                        <label for="step_6" class="label">Votre sixième étape :</label>
                        <textarea name="step_6" id="step_6" cols="60" rows="3" placeholder="Renseignez  votre sixième étape..."></textarea>

                        <button type="submit" name="submit" class="btn">Partagez votre recette</button>
                    <!-- </form> -->
                </div>
                <div class="recipe">
                    <!-- <div class="preparation_header"> -->
                    <!-- DEBUT DE LA CARTE PREPARATION -->
                    <div class="card-header">
                        <div class="contact-section icon">
                            <img src="" alt="icône représentant un fouet" srcset="">
                        </div>
                        <div class="card-header title">
                            <h3 class="contact-section header">Préparation</h3>
                        </div>
                    </div>
                    <div class="total_time">
                        <div class="time">
                            <label for="time" class="label">Temps total</label>
                            <input id="time" type="text" name="time" class="input"></label>
                        </div>
                        <div class="resting_time">
                            <label for="resting_time" class="label">Temps de repos</label>
                            <input id="resting_time" type="text" name="resting_time" class="input"></label>
                        </div>
                        <div class="oven">
                            <label for="oven" class="label">Temps de cuisson</label>
                            <input id="oven" type="text" name="oven" class="input"></label>
                        </div>
                    </div>
                    <!-- FIND DE LA CARTE PREPARATION -->
                    <!-- DEBUT DE LA CARTE INGREDIENTS -->
                    <div class="card-header">
                        <div class="contact-section icon">
                            <img src="" alt="icône représentant un ingrédient" srcset="">
                        </div>
                        <div class="card-header title">
                            <h3 class="contact-section header">Ingrédients</h3>
                        </div>
                    </div>
                    <div class="ingredients">
                        <div class="persons">
                            <label for="persons" class="label">Nombre de personnes</label>
                            <input id="persons" type="text" name="persons" class="input"></label>
                        </div>
                        <div class="ingredient">
                            <label for="ingredient" class="label">Vos ingrédients</label>
                            <select class="select" name="ingredient" id="ingredient" aria-placeholder="test">
                                <option value="empty">-- Choisissez votre ingrédient --</option>
                                <option value="oeuf">Oeuf</option>
                                <option value="sel">Sel</option>
                                <option value="sucre">Sucre</option>
                                <option value="beurre">Beurre/option>
                            </select>
                            <select class="select" name="ingredient" id="ingredient" aria-placeholder="test">
                                <option value="empty">-- Choisissez votre ingrédient --</option>
                                <option value="oeuf">Oeuf</option>
                                <option value="sel">Sel</option>
                                <option value="sucre">Sucre</option>
                                <option value="beurre">Beurre/option>
                            </select>
                            <select class="select" name="ingredient" id="ingredient" aria-placeholder="test">
                                <option value="empty">-- Choisissez votre ingrédient --</option>
                                <option value="oeuf">Oeuf</option>
                                <option value="sel">Sel</option>
                                <option value="sucre">Sucre</option>
                                <option value="beurre">Beurre/option>
                            </select>
                            <select class="select" name="ingredient" id="ingredient" aria-placeholder="test">
                                <option value="empty">-- Choisissez votre ingrédient --</option>
                                <option value="oeuf">Oeuf</option>
                                <option value="sel">Sel</option>
                                <option value="sucre">Sucre</option>
                                <option value="beurre">Beurre</option>
                            </select>
                            <div class="add_ingredient">
                                <button name="add_ingredient" id="button" type="button" class="btn">Ajouter un ingrédient</button>
                            </div>
                            <div class="add_ingredient">
                                <label for="custom_ingredient" class="label">Votre ingrédient particulier</label>
                                <input id="custom_ingredient" type="text" name="custom_ingredient" class="input" placeholder="Votre ingrédient..."></input>
                                <button name="add_custom" id="add_custom" type="button" class="btn">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
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