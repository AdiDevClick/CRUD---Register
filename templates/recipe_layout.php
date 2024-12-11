<!-- <section class="card_container mobile-only"> -->
<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "functions.inc.php";
?>


<div class="tabulation_container">
    <ul class="tabulation">
        <li>
            <a href=#><?= $pageTitle ?></a>
        </li>
        <li class="li-arrow"></li>
        <li>
            <a class="active js-one" href="http://">Recette</a>
        </li>
        <li class="li-arrow"></li>
        <li>
            <a class="greyed js-two" href="http://">Préparation</a>
        </li>
        <li class="li-arrow"></li>
        <li>
            <a class="greyed js-three" href="http://">Ingrédient</a>
        </li>
        <li class="li-arrow"></li>
        <li>
            <a class="greyed js-four" href="http://">Images</a>
        </li>
    </ul>
    <a id="return" href="../index.php">Retour</a>
    <!-- <img src="../img/close.svg" alt="image représentant une fermeture"> -->
</div>

<section class="card_container container">
    <form
        id="preparation-form-all-resolutions"
        action="update_recipes.php"
        data-endpoint="https://jsonplaceholder.typicode.com/comments"
        data-template="#ingredient-template"
        data-target=".js-ingredient-group-all-resolution"
        data-list_elements='{
            "ingredient-": ".js-value",
            "id": ".js-value",
            "ingredient": ".js-select1",
            "ingredient2": ".js-select2",
            "ingredient3": ".js-select3",
            "ingredient4": ".js-select4",
            "ingredient5": ".js-select5",
            "ingredient6": ".js-select6"
        }'
        data-steps_template='#recipe-input-template'
        data-elements='{
            "ingredient": ".js-value"
        }'
        class="js-form-fetch"
        method="post">
        <section class="contact-grid js-stop-appender" id="recipe_creation">
            <!-- Error Message -->
            <?php if (!empty($err)) : ?>
                <div>
                    <p class="alert-error"><?= strip_tags($errorMessage) ?></p>
                </div>
            <?php endif ?>
            <div class="full js-one" id="js-mutator-obs">
                <div class="three-columns">
                    <p>Renseignez le titre ainsi qu’une courte description.</p>
                </div>
                <!-- Title -->
                <div class="js-form-recipe">
                    <label for="title" class="label">Titre de votre recette</label>
                    <input class="form" name="title" type="text" id="title" placeholder="Votre titre..." value="<?php $getInfos !== null && $getInfos['title'] ? print strip_tags($getInfos['title']) : null ?>">
                </div>
                <!-- QUICK DESCRIPTION -->
                <div class="js-form-recipe">
                    <label for="description" class="label">Courte description</label>
                    <textarea name="description" id="description" cols="60" rows="3" placeholder="Une courte description... Exemple : Une recette facile, peu épicée et économique !"><?php $getInfos !== null && $getInfos['description'] ? print strip_tags($getInfos['description']) : null ?></textarea>
                </div>
                <div class="three-columns">
                    <p>Complétez le contenu de votre recette.<br>Il est possible d’ajouter jusqu’à 6 étapes.</p>
                </div>
                <!-- STEPS 1 TO 6 -->
                <?php
                echo createDivWithTextArea($getInfos);
                ?>
                <!-- ADD STEPS BUTTON -->
                <!-- <a href="#step_2" class="plus three-columns" >
                    <span></span>
                </a> -->
                <?php include '../templates/recipe_step_template.html' ?>
            </div>
            <!-- DRAWER BUTTONS -->
            <div class="opening_drawer_button show">
                <img src='../img/add.svg' alt="image représentant une addition">
                <span class="tooltiptext">Une erreur se trouve dans le tiroir de préparations</span>
            </div>
            <div class="drawer js-recipe">
                <div class="drawer__button"></div>
                <div class="drawer__close">
                    <img src='../img/close.svg' alt="image représentant une fermeture">
                </div>
            </div>
            <!-- END OF DRAWER BUTTONS -->
            <!-- DEBUT DE LA CARTE PREPARATION -->
            <div id="js-append-to-drawer" class="js-append-to-drawer full js-two hidden">
                <div class="card-header-section three-columns">
                    <div class=" icon form-logo">
                        <img src="../img/cooking.svg" alt="icône représentant une cuisson" srcset="">
                    </div>
                    <div class="card-header title">
                        <h3 class="contact-section header">Préparation de votre plat</h3>
                    </div>
                </div>
                <div class="three-columns">
                    <p>Définissez les informations de temps de préparation et le nombre de personnes.</p>
                </div>
                <div class="total_time full">
                    <?php
                    $inputs = [
                        'total_time' => 'Temps Total',
                        'resting_time' => 'Temps de repos',
                        'oven_time' => 'Temps de cuisson',
                    ];
                    echo createDivWithSelectAndInputs($inputs, $getInfos);
                    ?>
                    <div class="persons time">
                        <label for="persons" class="label first-column-bottom-border">Nombre de personnes</label>
                        <input id="persons" type="text" name="persons" class="input" value="<?= $getInfos !== null && $getInfos['persons'] ? htmlspecialchars((string)$getInfos['persons']) : null ?>">
                    </div>
                </div>
            </div>
            <!-- FIN DE LA CARTE PREPARATION -->

            <!-- DEBUT DE LA CARTE INGREDIENTS -->
            <div id="show_drawer" class="js-three show_drawer three-columns hidden">
                <div class="three-columns card-header-section">
                    <div class="icon form-logo">
                        <img src="../img/food.svg" alt="icône représentant un panier d'ingrédients" srcset="">
                    </div>
                    <div class="card-header title">
                        <h3 class="contact-section">Ingrédients</h3>
                    </div>
                </div>
                <div class="full ingredients js-ingredients-list">
                    <div class="full">
                        <!-- <template id="ingredient-template">
                            <div contenteditable="false" type="text" class="custom-ingredient">
                                <p contenteditable="false" class="js-value"></p>
                            </div>
                        </template> -->
                        <?php include '../templates/custom_ingredient_template.html' ?>
                        <?php include '../templates/dynamic_tooltips_template.html' ?>
                        <div class="three-columns">
                            <p>Les ingrédients particuliers que vous ajouterez dans la section du bas apparaîtront ici.
                                <br>Il est possible de les supprimer ou de les éditer en cliquant dessus.
                            </p>
                        </div>
                        <div class="three-columns ingredient-stack js-ingredient-group-all-resolution js-ingredient-group js-modal-stop"></div>
                        <div class="three-columns">
                            <p>Sélectionnez ou ajoutez un ingrédient.</p>
                        </div>

                    </div>
                    <div class="full add_ingredient">
                        <div class="ingredient three-columns">
                            <select class="select js-select1" name="ingredient_1" id="ingredient_1" aria-placeholder="test">
                                <option type="text" class="test">Selectionnez</input>
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select2" name="ingredient_2" id="ingredient_2" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select3" name="ingredient_3" id="ingredient_3" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select4" name="ingredient_4" id="ingredient_4" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select5" name="ingredient_5" id="ingredient_5" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select6" name="ingredient_6" id="ingredient_6" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                        </div>
                        <label for="custom_ingredients" class="label three-columns">Votre ingrédient particulier</label>
                        <input id="custom_ingredients" type="text" name="custom_ingredients" class="three-columns input js-ingredient-input" placeholder="Votre ingrédient...">
                        <button name="add_custom" id="add_custom" type="button" class="three-columns plus js-add-custom">Ajouter un nouvel ingrédient</button>
                        <!-- <button name="add_custom" id="add_custom" type="button" class="btn js-add-custom">Ajouter un nouvel ingrédient</button> -->
                    </div>
                </div>
            </div>
            <!-- FIN DE LA CARTE INGREDIENTS -->

            <!-- FILE -->
            <div class="full js-four hidden">
                <div class="three-columns">
                    <p>Définissez une image et/ou une vidéo</p>
                </div>
                <div id="img_preview" class="img_preview">
                    <label id="add_image" for="file" class="label"> Ajouter une image</label>
                    <div class="profile-picture"
                        <?php
                        $getInfos == null ?: print 'style="background-image: url(../' . $getInfos['img_path'] . ' )"'
                        ?>>
                        <h1 class="upload-icon">
                            <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                        </h1>
                        <input class="file-uploader" type="file" id="file" name="file" class="form" />
                    </div>
                    <!-- <hr append en beforeend> -->
                </div>
                <!-- VIDEO -->
                <div id="video_preview" class="img_preview">
                    <label id="add_video" for="video_file" class="label"> Ajouter une vidéo</label>
                    <div class="profile-picture"
                        <?php
                        $getInfos == null ?: print 'style="background-image: url(../' . $getInfos['video_path'] . ' )"'
                        ?>>
                        <h1 class="upload-icon">
                            <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                        </h1>
                        <input class="file-uploader" type="file" id="video_file" name="video_file" class="form" />
                    </div>

                </div>
                <div class="three-columns">
                    <p>Le cas échéant, définissez un ID Youtube de votre vidéo</p>
                </div>
                <!-- <hr append en beforeend> -->
                <div class="js-form-recipe">
                    <label id="video_link_label" for="video_link" class="label"> Youtube Vidéo ID</label>
                    <input type="text" placeholder="Youtube Vidéo ID" id="video_link" name="video_link" class="form" value="<?php $getInfos !== null && $getInfos['youtubeID'] ? print strip_tags($getInfos['youtubeID']) : null ?>" />
                </div>
            </div>

            <!-- Send Button -->
            <div class="add_ingredient" id="submit-recipe">
                <hr>
                <button name="add_preparation" id="submit" type="submit" class="btn">Valider vos modifications</button>
            </div>

            <!-- Next / Preview Buttons -->
            <?php include '../templates/step_button_template.html' ?>
            <!-- </section> -->
        </section>
    </form>
</section>