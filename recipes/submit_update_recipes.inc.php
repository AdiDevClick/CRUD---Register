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
$data = $_SERVER['REQUEST_METHOD'] === 'GET';
$err = [];
$loggedUser = [];
/***
 * Grabbing URL ID from index page and fetching rows datas
 */
if($data && isset($_GET['id']) && is_numeric($_GET['id'])) {

    $getDatas = $_GET['id'];
    $checkId = new RecipeView($getDatas);
    $getInfos = $checkId->getRecipeInfoById();
    // echo $getInfos['custom_ingredients'];
    // echo '<pre>';
    // print_r($getInfos);
    // echo '</pre>';
} else {
    // echo ('pas did');
    // echo $_GET['id'] . 'test';
    // print_r($_GET);
    header('Location: ../index.php?error=no-update-id');
    exit("Il n'y a malheureusement plus rien à voir !");
}

$loggedUser = LoginController::checkLoggedStatus();
$err = CheckInput::getErrorMessages();
$errorMessage = CheckInput::showErrorMessage();
ob_start()
?>
<?php if ((isset($loggedUser['email']) || isset($_SESSION['LOGGED_USER'])) && !isset($_SESSION['UPDATED_RECIPE'])):?>
    <h1>Recette à éditer : <?= htmlspecialchars($getInfos['title'])?></h1>
    <?php include '../templates/recipe_layout.php'?>
    <?php include '../templates/recipe_creation_menu.html'?>
    <!-- <section class="card_container">
    <form
        id="preparation-form"
        action="update_recipes.php"
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
            <section class="card form-recipe">
                    <?php //if (!empty($err)) : ?>
                        <?php //echo 'test' ?>
                        <div>
                            <p class="alert-error"><?php //echo(strip_tags($errorMessage)) ?></p>
                        </div>
                    <?php //endif ?>
                    <div class="">
                        <label for="title" class="label">Titre de votre recette</label>
                        <input class="form" name="title" type="text" id="title" placeholder="Votre titre..." value="<?php echo strip_tags($getInfos['title'])?>">
                    </div>
                    <div class="">
                        <label for="step_1" class="label">Etape 1</label>
                        <textarea class="" name="step_1" id="step_1" cols="60" rows="3" placeholder="Renseignez votre première étape..."><?php echo strip_tags($getInfos['step_1'])?></textarea>
                    </div>
                    <div class="">
                        <label for="step_2" class="label">Etape 2</label>
                        <textarea name="step_2" id="step_2" cols="60" rows="3" placeholder="Renseignez  votre deuxième étape..."><?php echo strip_tags($getInfos['step_2'])?></textarea>
                    </div>
                    <div class="">
                        <label for="step_3" class="label">Etape 3</label>
                        <textarea name="step_3" id="step_3" cols="60" rows="3" placeholder="Renseignez  votre troisième étape..."><?php echo strip_tags($getInfos['step_3'])?></textarea>
                    </div>
                    <div class="">
                        <label for="step_4" class="label">Etape 4</label>
                        <textarea name="step_4" id="step_4" cols="60" rows="3" placeholder="Renseignez  votre quatrième étape..."><?php echo strip_tags($getInfos['step_4'])?></textarea>
                    </div>
                    <div class="">
                        <label for="step_5" class="label">Etape 5</label>
                        <textarea name="step_5" id="step_5" cols="60" rows="3" placeholder="Renseignez  votre cinquième étape..."><?php echo strip_tags($getInfos['step_5'])?></textarea>
                    </div>
                    <div class="">
                        <label for="step_6" class="label">Etape 6</label>
                        <textarea name="step_6" id="step_6" cols="60" rows="3" placeholder="Renseignez  votre sixième étape..."><?php echo strip_tags($getInfos['step_6'])?></textarea>
                    </div>
            </section>
            <div class="opening_drawer_button show">
                <img src='../img/add.svg' alt="image représentant une addition">
            </div>
            <section class="card recipe" id="js-preparation">
                <div class="drawer">
                    <div class="drawer__button"></div>
                    <div class="drawer__close">
                        <img src='../img/close.svg' alt="image représentant une fermeture">
                    </div>
                </div>
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
                                <input id="total_time" type="text" name="total_time" class="input" value="<?= htmlspecialchars((string)$getInfos['total_time'])?>">
                                <select class="select" name="total_time_length" id="total_time_length" aria-placeholder="temps">
                                    <option value="min" <?php // $getInfos['total_time_length'] === 'min' ? htmlspecialchars('selected') : null ?>>min</option>
                                    <option value="heures" <?php // $getInfos['total_time_length'] === 'heures' ? htmlspecialchars('selected') : null ?>>heures</option>
                                </select>
                            </div>
                            <div class="time">
                                <label for="resting_time" class="label">Temps de repos</label>
                                <input id="resting_time" type="text" name="resting_time" class="input" value="<?= htmlspecialchars((string)$getInfos['resting_time'])?>">
                                <select class="select" name="resting_time_length" id="resting_time_length" aria-placeholder="test">
                                    <option value="min" <?php // $getInfos['resting_time_length'] === 'min' ? htmlspecialchars('selected') : null ?>>min</option>
                                    <option value="heures" <?php // $getInfos['resting_time_length'] === 'heures' ? htmlspecialchars('selected') : null ?>>heures</option>
                                </select>
                            </div>
                            <div class="time">
                                <label for="oven_time" class="label">Temps de cuisson</label>
                                <input id="oven_time" type="text" name="oven_time" class="input" value="<?= htmlspecialchars((string)$getInfos['oven_time'])?>">
                                <select class="select" name="oven_time_length" id="oven_time_length" aria-placeholder="test">
                                    <option value="min" <?php // $getInfos['oven_time_length'] === 'min' ? htmlspecialchars('selected') : null ?>>min</option>
                                    <option value="heures" <?php // $getInfos['oven_time_length'] === 'heures' ? htmlspecialchars('selected') : null ?>>heures</option>
                                </select>
                            </div>
                        </div>
                    </div>
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
                            <input id="persons" type="text" name="persons" class="input" value="<?= htmlspecialchars((string)$getInfos['persons'])?>">
                        </div>
                        <div class="">
                        <template id="ingredient-template">
                            <div contenteditable="false" type="text" class="custom-ingredient">
                                <p contenteditable="false" class="js-value"></p>
                            </div>
                        </template>
                            <p class="label">Vos ingrédients</p>
                            <div class="ingredient-stack js-ingredient-group js-modal-stop"></div>
                            <div class="ingredient">
                                <select class="select js-select1" name="ingredient" id="ingredient1" aria-placeholder="test">
                                    <option value="oeuf" <?php // $getInfos['ingredient_1'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                    <option value="sel" <?php // $$getInfos['ingredient_1'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                    <option value="sucre" <?php // $$getInfos['ingredient_1'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                    <option value="beurre" <?php // $$getInfos['ingredient_1'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                                </select>
                                <select class="select js-select2" name="ingredient2" id="ingredient2" aria-placeholder="test">
                                    <option value="oeuf" <?php // $$getInfos['ingredient_2'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                    <option value="sel" <?php // $$getInfos['ingredient_2'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                    <option value="sucre" <?php // $$getInfos['ingredient_2'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                    <option value="beurre" <?php // $$getInfos['ingredient_2'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                                </select>
                                <select class="select js-select3" name="ingredient3" id="ingredient3" aria-placeholder="test">
                                    <option value="oeuf" <?php // $$getInfos['ingredient_3'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                    <option value="sel" <?php // $$getInfos['ingredient_3'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                    <option value="sucre" <?php // $$getInfos['ingredient_3'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                    <option value="beurre" <?php // $$getInfos['ingredient_3'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                                </select>
                                <select class="select js-select4" name="ingredient4" id="ingredient4" aria-placeholder="test">
                                    <option value="oeuf" <?php // $$getInfos['ingredient_4'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                    <option value="sel" <?php // $$getInfos['ingredient_4'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                    <option value="sucre" <?php // $$getInfos['ingredient_4'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                    <option value="beurre" <?php // $$getInfos['ingredient_4'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                                </select>
                                <select class="select js-select5" name="ingredient5" id="ingredient5" aria-placeholder="test">
                                    <option value="oeuf" <?php // $$getInfos['ingredient_5'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                    <option value="sel" <?php // $$getInfos['ingredient_5'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                    <option value="sucre" <?php // $$getInfos['ingredient_5'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                    <option value="beurre" <?php // $$getInfos['ingredient_5'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                                </select>
                                <select class="select js-select6" name="ingredient6" id="ingredient6" aria-placeholder="test">
                                    <option value="oeuf" <?php // $$getInfos['ingredient_6'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                    <option value="sel" <?php // $$getInfos['ingredient_6'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                    <option value="sucre" <?php // $$getInfos['ingredient_6'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                    <option value="beurre" <?php // $$getInfos['ingredient_6'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                                </select>
                            </div>
                        </div>
                        <div class="add_ingredient">
                            <label for="custom_ingredient" class="label">Votre ingrédient particulier</label>
                            <input id="custom_ingredient" type="text" name="custom_ingredient" class="input" placeholder="Votre ingrédient...">
                            <button name="add_custom" id="add_custom" type="button" class="btn">Ajouter un nouvel ingrédient</button>
                        </div>
                        <div class="img_preview">
                            <label id="add_image" for="file" class="label"> Ajouter une image</label>
                            <div class="profile-picture">
                                <h1 class="upload-icon">
                                    <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                                </h1>
                                <input class="file-uploader" type="file" id="file" name="file" class="form"/>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="add_ingredient">
                        <button name="add_preparation" id="submit" type="submit" class="btn">Valider vos modifications</button>
                    </div>
                </div>
            </section>
        </section>
    </form>
</section> -->
<?php endif?>
<!-- start of success message -->
<?php //$content = ob_get_clean()?>
<?php //ob_start()?>

<?php //elseif (isset($_SESSION['REGISTERED_RECIPE'])):?>
    <?php //$setRecipe->displayShareSuccess($getDatas, $loggedUser)?>
    <?php //unset($_SESSION['REGISTERED_RECIPE'])?>
    <?php //else:?>
        <?php //session_destroy()?>
        <?php //header('Location: ../register.php?failed=recipe-creation')?>
        <?php //exit()?>
<?php //endif?>
<!-- end of success message --> 
<?php $content = ob_get_clean() ?>