<?php declare(strict_types=1);

// if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
//ob_start();
// require('../recipes/test.php');
// include_once("../includes/class-autoloader.inc.php");
// include_once('../logs/customErrorHandlers.php');
// require_once(__DIR__ . './' . "includes/class-autoloader.inc.php");
// require_once(__DIR__ . "/logs/customErrorHandlers.php");
// include('../recipes/test.php');

// $rootUrl = Functions::getRootUrl();
// // echo $rootUrl;
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];
$loggedUser = [];
// print_r($_REQUEST);
// print_r($data);
// include('../recipes/Process_PreparationList.php');

// print_r($getDatas);
// On affiche chaque recette une à une
// if ($data && isset($_POST['submit'])) {
//     //try {
//     //require_once("../includes/class-autoloader.inc.php");
//     print_r($data);
//     $getDatas = [
//     'title' => $_POST['title'],
//     'step_1' => $_POST['step_1'],
//     'step_2' => $_POST['step_2'],
//     'step_3' => $_POST['step_3'],
//     'step_4' => $_POST['step_4'],
//     'step_5' => $_POST['step_5'],
//     'step_6' => $_POST['step_6'],
//     // 'ingredient' => $_POST['time'],
//     ];

//     print_r($getDatas);
//     // $content = trim(file_get_contents("php://input"));

//     // $dataTest = json_decode($content, true);

//     // echo json_encode($dataTest['persons']);
//     // echo($dataTest["persons"]);
//     // echo($content);

//     // print_r($dataTest);
//     // print_r($getDatas);
//     $setRecipe = new RecipeView($getDatas);
//     $setRecipe->insertRecipe();
//     $err = CheckInput::getErrorMessages();

//     if (count($err) > 0) {
//         // print_r($err);
//         session_destroy();
//     } else {
//         header('refresh:10, ../index.php?success=recipe-shared');
//         // session_destroy();
//     }
//     //header('Location: ../index.php?error=none');
//     //unset($_SESSION['REGISTERED_RECIPE']);
//     //unset($_SESSION['REGISTERED_RECIPE']);
//     //exit();
//     //header('refresh:10, ../index.php');
//     //$content = ob_get_contents();
//     //$content = ob_get_clean();
//     //} catch (Error $e) {
//     //die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas dans l\'insertion...') ;
//     //}
// }

//$content = ob_get_clean();
//$content = ob_end_flush();

//ob_start()
$loggedUser = LoginController::checkLoggedStatus();
// echo ' le array dans submit recipe';
// print_r($loggedUser);
$err = CheckInput::getErrorMessages();
// if (count($err) > 0) {
//     // print_r($err);
//     $errorMessage = CheckInput::showErrorMessage();

//     $successMessage = '';
//     //if (isset($_SESSION['REGISTERED_USER'])) {
//     //ob_start();
//     $successMessage = '<div>';
//     $successMessage .= '<p class="alert-error"> ' . strip_tags($errorMessage) . '</p>';
//     $successMessage .= '</div>';
//     echo $successMessage;
//     echo $dataTest['failed'] = true;
//     // session_destroy();
// } else {
//     header('Location: ../index.php?success=recipe-shared');
//     // header('refresh:10, ../index.php?success=recipe-shared');
//     // session_destroy();
// }
$errorMessage = CheckInput::showErrorMessage();
// ob_get_clean();
ob_start();
// ob_end_clean();
// ob_get_status();
// ob_get_contents()
//if (isset($loggedUser['email']) && !isset($loggedUser['recipe'])):

?>
<?php if (isset($loggedUser['email'])  && !isset($_SESSION['REGISTERED_RECIPE'])): ?> 
        <?php //(isset($loggedUserState)):?>
    <section class="card_container">
        <h1>Partagez votre recette</h1>
        <form
            id="preparation-form"
            action="create_recipes.php"
            data-endpoint="https://jsonplaceholder.typicode.com/comments"
            data-template="#ingredient-template"
            data-target=".js-ingredient-group"
            data-list-elements='{
                "ingredient-": ".js-value",
                "id": ".js-value",
                "ingredient": ".js-select1",
                "ingredient2": ".js-select2",
                "ingredient3": ".js-select3",
                "ingredient4": ".js-select4",
                "ingredient5": ".js-select5",
                "ingredient6": ".js-select6"
            }'
            data-elements='{
                "ingredient": ".js-value"
            }'
            class="js-form-fetch" method="post"
        >
            <section class="contact-grid" id="recipe_creation">
            <!-- <div class="form-flex " id="recipe_creation"> -->
                <!-- <h1>Partagez votre recette !</h1> -->
                <section class="card form-recipe">
                    <!-- <form action="create_recipes.php" method="post" id="recipe-form"> -->
                        <?php if (!empty($err)) : ?>
                            <?php echo 'test' ?>
                            <div>
                                <p class="alert-error"><?php echo(strip_tags($errorMessage)) ?></p>
                            </div>
                        <?php endif ?>
                        <!-- Title -->
                        <div class="">
                            <label for="title" class="label">Titre de votre recette</label>
                            <input class="form" name="title" type="text" id="title" placeholder="Votre titre...">
                        </div>
                        <!-- STEP 1 -->
                        <div class="">
                            <label for="step_1" class="label">Etape 1</label>
                            <textarea class="" name="step_1" id="step_1" cols="60" rows="3" placeholder="Renseignez votre première étape..."></textarea>
                        </div>
                        <!-- STEP 2 -->
                        <div class="">
                            <label for="step_2" class="label">Etape 2</label>
                            <textarea name="step_2" id="step_2" cols="60" rows="3" placeholder="Renseignez  votre deuxième étape..."></textarea>
                        </div>
                        <!-- STEP 3 -->
                        <div class="">
                            <label for="step_3" class="label">Etape 3</label>
                            <textarea name="step_3" id="step_3" cols="60" rows="3" placeholder="Renseignez  votre troisième étape..."></textarea>
                        </div>
                        <!-- STEP 4 -->
                        <div class="">
                            <label for="step_4" class="label">Etape 4</label>
                            <textarea name="step_4" id="step_4" cols="60" rows="3" placeholder="Renseignez  votre quatrième étape..."></textarea>
                        </div>
                        <!-- STEP 5 -->
                        <div class="">
                            <label for="step_5" class="label">Etape 5</label>
                            <textarea name="step_5" id="step_5" cols="60" rows="3" placeholder="Renseignez  votre cinquième étape..."></textarea>
                        </div>
                        <!-- STEP 6 -->
                        <div class="">
                            <label for="step_6" class="label">Etape 6</label>
                            <textarea name="step_6" id="step_6" cols="60" rows="3" placeholder="Renseignez  votre sixième étape..."></textarea>
                        </div>
                        <!-- Submit -->
                        <!-- <div id="register-btn" class="form form-hidden">
                            <button id="recipe-submit" type="submit" name="submit" class="btn">Partagez votre recette</button>
                        </div> -->
                        <!-- </form> -->
                    <!-- </form> test -->
                </section>
                <div class="opening_drawer_button show">
                    <img src='../img/add.svg' alt="image représentant une addition">
                </div>
                <!-- <div class="opening_drawer_button show">
                    <img src='../img/add.svg' alt="image représentant une addition">
                </div> -->
                
                <section class="card recipe" id="js-preparation">
                    <div class="drawer">
                        <div class="drawer__button"></div>
                        <div class="drawer__close">
                            <img src='../img/close.svg' alt="image représentant une fermeture">
                        </div>
                    </div>
                    <!-- <form
                        id="preparation-form"
                        action="create_recipes.php"
                        data-endpoint="https://jsonplaceholder.typicode.com/comments"
                        data-template="#ingredient-template"
                        data-target=".js-ingredient-group"
                        data-list-elements='{
                            "ingredient-": ".js-value",
                            "id": ".js-value",
                            "ingredient": ".js-select1",
                            "ingredient2": ".js-select2",
                            "ingredient3": ".js-select3",
                            "ingredient4": ".js-select4",
                            "ingredient5": ".js-select5",
                            "ingredient6": ".js-select6"
                        }'
                        data-elements='{
                            "ingredient": ".js-value"
                        }'
                        class="js-form-fetch" method="post"
                    > -->
                    <!-- <div class="preparation_header"> -->
                    <!-- DEBUT DE LA CARTE PREPARATION -->
                    <div class="show_drawer">
                        <div>
                            <div class="card-header-section">
                                <div class=" icon form-logo">
                                    <img src="../img/cooking.svg" alt="icône représentant une cuisson" srcset="">
                                </div>
                                <div class="card-header title">
                                    <h3 class="contact-section header">Préparation</h3>
                                </div>
                            </div>
                            <div class="total_time">
                                <div class="time">
                                    <label for="total_time" class="label">Temps total</label>
                                    <input id="total_time" type="text" name="total_time" class="input">
                                    <select class="select" name="total_time_length" id="total_time_length" aria-placeholder="test">
                                        <option value="empty">-- Temps --</option>
                                        <option value="min">min</option>
                                        <option value="heures">heures</option>
                                    </select>
                                </div>
                                <div class="time">
                                <!-- <div class="resting_time"> -->
                                    <label for="resting_time" class="label">Temps de repos</label>
                                    <input id="resting_time" type="text" name="resting_time" class="input">
                                    <select class="select" name="resting_time_length" id="resting_time_length" aria-placeholder="test">
                                        <option value="empty">-- Temps --</option>
                                        <option value="min">min</option>
                                        <option value="heures">heures</option>
                                    </select>
                                </div>
                                <div class="time">
                                <!-- <div class="oven"> -->
                                    <label for="oven_time" class="label">Temps de cuisson</label>
                                    <input id="oven_time" type="text" name="oven_time" class="input">
                                    <select class="select" name="oven_time_length" id="oven_time_length" aria-placeholder="test">
                                        <option value="empty">-- Temps --</option>
                                        <option value="min">min</option>
                                        <option value="heures">heures</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                    <!-- FIN DE LA CARTE PREPARATION -->
                    <!-- DEBUT DE LA CARTE INGREDIENTS -->
                        <div class="card-header-section">
                            <div class="icon form-logo">
                                <img src="../img/food.svg" alt="icône représentant un panier d'ingrédients" srcset="">
                            </div>
                            <div class="card-header title">
                                <h3 class="contact-section">Ingrédients</h3>
                            </div>
                        </div>
                        <div class="ingredients js-ingredients-list">
                            <div class="persons time">
                                <label for="persons" class="label">Nombre de personnes</label>
                                <input id="persons" type="text" name="persons" class="input">
                            </div>
                            <div class="">
                            <template id="ingredient-template">
                                <!-- <input type="text" class="custom-ingredient js-value"></input> -->
                                <div contenteditable="false" type="text" class="custom-ingredient">
                                    <p contenteditable="false" class="js-value"></p>
                                </div>
                                <!-- <select class="select" name="ingredient-1" id="ingredient-1"> -->
                                    <!-- <option class="js-value">heures</option> -->
                                <!-- </select> -->
                            </template>
                                <p class="label">Vos ingrédients</p>
                                <div class="ingredient-stack js-ingredient-group js-modal-stop"></div>
                                <div class="ingredient">
                                    <select class="select js-select1" name="ingredient" id="ingredient1" aria-placeholder="test">
                                        <option value="empty">-- Choisissez votre ingrédient --</option>
                                        <option value="oeuf">Oeuf</option>
                                        <option value="sel">Sel</option>
                                        <option value="sucre">Sucre</option>
                                        <option value="beurre">Beurre</option>
                                    </select>
                                    <select class="select js-select2" name="ingredient2" id="ingredient2" aria-placeholder="test">
                                        <option value="empty">-- Choisissez votre ingrédient --</option>
                                        <option value="oeuf">Oeuf</option>
                                        <option value="sel">Sel</option>
                                        <option value="sucre">Sucre</option>
                                        <option value="beurre">Beurre</option>
                                    </select>
                                    <select class="select js-select3" name="ingredient3" id="ingredient3" aria-placeholder="test">
                                        <option value="empty">-- Choisissez votre ingrédient --</option>
                                        <option value="oeuf">Oeuf</option>
                                        <option value="sel">Sel</option>
                                        <option value="sucre">Sucre</option>
                                        <option value="beurre">Beurre</option>
                                    </select>
                                    <select class="select js-select4" name="ingredient4" id="ingredient4" aria-placeholder="test">
                                        <option value="empty">-- Choisissez votre ingrédient --</option>
                                        <option value="oeuf">Oeuf</option>
                                        <option value="sel">Sel</option>
                                        <option value="sucre">Sucre</option>
                                        <option value="beurre">Beurre</option>
                                    </select>
                                    <select class="select js-select5" name="ingredient5" id="ingredient5" aria-placeholder="test">
                                        <option value="empty">-- Choisissez votre ingrédient --</option>
                                        <option value="oeuf">Oeuf</option>
                                        <option value="sel">Sel</option>
                                        <option value="sucre">Sucre</option>
                                        <option value="beurre">Beurre</option>
                                    </select>
                                    <select class="select js-select6" name="ingredient6" id="ingredient6" aria-placeholder="test">
                                        <option value="empty">-- Choisissez votre ingrédient --</option>
                                        <option value="oeuf">Oeuf</option>
                                        <option value="sel">Sel</option>
                                        <option value="sucre">Sucre</option>
                                        <option value="beurre">Beurre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="add_ingredient">
                                <label for="custom_ingredient" class="label">Votre ingrédient particulier</label>
                                <input id="custom_ingredient" type="text" name="custom_ingredient" class="input" placeholder="Votre ingrédient...">
                                <button name="add_custom" id="add_custom" type="button" class="btn">Ajouter un nouvel ingrédient</button>
                            </div>
                            <!-- File -->
                            <div class="select">
                            <!-- <div class="form form-hidden"> -->
                                <label for="file" class="label"> Ajouter une image</label>
                                <input type="file" id="file" name="file" class="form"/>
                            </div>
                            <hr>
                            <!-- <div class="add_ingredient">
                                <button name="add_preparation" id="button" type="submit" class="btn">Valider vos ingrédients</button>
                            </div> -->
                        </div>
                        <div class="add_ingredient">
                            <button name="add_preparation" id="submit" type="submit" class="btn">Valider votre recette</button>
                        </div>
                    </div>
                    <!-- </form> -->
                </section>
                <!-- </form> -->
            </section>
            </form>

            <?php //endif?>
            <?php //session_destroy()?>
        </section>
<!-- start of success message -->
<?php //$content = ob_get_clean()?>
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
<?php $content = ob_get_clean()?>