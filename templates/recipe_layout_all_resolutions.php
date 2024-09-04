<?php
    $getInfos = null;
?>
<section class="card_container all-resolutions">
    <form
        id="preparation-form-all-resolutions"
        action="update_recipes.php"
        data-endpoint="https://jsonplaceholder.typicode.com/comments"
        data-template="#ingredient-template"
        data-target=".js-ingredient-group-all-resolution"
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
        <section class="contact-grid js-stop-appender" id="recipe_creation_all_resolutions">
            <!-- Error Message -->
            <?php if (!empty($err)) : ?>
                <?php echo 'test' ?>
                <div>
                    <p class="alert-error"><?php echo(strip_tags($errorMessage)) ?></p>
                </div>
            <?php endif ?>
            <!-- Title -->
            <div class="">
                <label for="title" class="label">Titre de votre recette</label>
                <input class="form" name="title" type="text" id="title" placeholder="Votre titre..." value="<?php $getInfos !== null && $getInfos['title'] ? print strip_tags($getInfos['title']) : null ?>">
            </div>
            <!-- STEP 1 -->
            <div class="">
                <label for="step_1" class="label">Etape 1</label>
                <textarea class="" name="step_1" id="step_1" cols="60" rows="3" placeholder="Renseignez votre première étape..."><?php $getInfos !== null && $getInfos['step_1'] ? print strip_tags($getInfos['step_1']) : null ?></textarea>
            </div>
            <!-- STEP 2 -->
            <div class="">
                <label for="step_2" class="label">Etape 2</label>
                <textarea name="step_2" id="step_2" cols="60" rows="3" placeholder="Renseignez  votre deuxième étape..."><?php $getInfos !== null && $getInfos['step_2'] ? print strip_tags($getInfos['step_2']) : null ?></textarea>
            </div>
            <!-- STEP 3 -->
            <div class="">
                <label for="step_3" class="label">Etape 3</label>
                <textarea name="step_3" id="step_3" cols="60" rows="3" placeholder="Renseignez  votre troisième étape..."><?php $getInfos !== null && $getInfos['step_3'] ? print strip_tags($getInfos['step_3']) : null ?></textarea>
            </div>
            <!-- STEP 4 -->
            <div class="">
                <label for="step_4" class="label">Etape 4</label>
                <textarea name="step_4" id="step_4" cols="60" rows="3" placeholder="Renseignez  votre quatrième étape..."><?php $getInfos !== null && $getInfos['step_4'] ? print strip_tags($getInfos['step_4']) : null ?></textarea>
            </div>
            <!-- STEP 5 -->
            <div class="">
                <label for="step_5" class="label">Etape 5</label>
                <textarea name="step_5" id="step_5" cols="60" rows="3" placeholder="Renseignez  votre cinquième étape..."><?php $getInfos !== null && $getInfos['step_5'] ? print strip_tags($getInfos['step_5']) : null ?></textarea>
            </div>
            <!-- STEP 6 -->
            <div class="">
                <label for="step_6" class="label">Etape 6</label>
                <textarea name="step_6" id="step_6" cols="60" rows="3" placeholder="Renseignez  votre sixième étape..."><?php $getInfos !== null && $getInfos['step_6'] ? print strip_tags($getInfos['step_6']) : null ?></textarea>
            </div>
            
            <!-- DEBUT DE LA CARTE PREPARATION -->
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
                        <input id="total_time" type="text" name="total_time" class="input" value="<?php if ($getInfos !== null) echo htmlspecialchars((string)$getInfos['total_time'])?>">
                        <select class="select" name="total_time_length" id="total_time_length" aria-placeholder="temps">
                            <option value="min" <?= $getInfos !== null && $getInfos['total_time_length'] === 'min' ? htmlspecialchars('selected') : null ?>>min</option>
                            <option value="heures" <?= $getInfos !== null && $getInfos['total_time_length'] === 'heures' ? htmlspecialchars('selected') : null ?>>heures</option>
                        </select>
                    </div>
                    <div class="time">
                        <label for="resting_time" class="label">Temps de repos</label>
                        <input id="resting_time" type="text" name="resting_time" class="input" value="<?php if ($getInfos !== null) echo htmlspecialchars((string)$getInfos['resting_time'])?>">
                        <select class="select" name="resting_time_length" id="resting_time_length" aria-placeholder="test">
                            <option value="min" <?= $getInfos !== null && $getInfos['resting_time_length'] === 'min' ? htmlspecialchars('selected') : null ?>>min</option>
                            <option value="heures" <?= $getInfos !== null && $getInfos['resting_time_length'] === 'heures' ? htmlspecialchars('selected') : null ?>>heures</option>
                        </select>
                    </div>
                    <div class="time">
                        <label for="oven_time" class="label">Temps de cuisson</label>
                        <input id="oven_time" type="text" name="oven_time" class="input" value="<?php if ($getInfos !== null) echo htmlspecialchars((string)$getInfos['oven_time'])?>">
                        <select class="select" name="oven_time_length" id="oven_time_length" aria-placeholder="test">
                            <option value="min" <?= $getInfos !== null && $getInfos['oven_time_length'] === 'min' ? htmlspecialchars('selected') : null ?>>min</option>
                            <option value="heures" <?= $getInfos !== null && $getInfos['oven_time_length'] === 'heures' ? htmlspecialchars('selected') : null ?>>heures</option>
                        </select>
                    </div>
                    <div class="persons time">
                        <label for="persons" class="label">Nombre de personnes</label>
                        <input id="persons" type="text" name="persons" class="input" value="<?= $getInfos !== null && $getInfos['persons'] ? htmlspecialchars((string)$getInfos['persons']) : null ?>">
                    </div>
                </div>
            </div>
            <!-- FIN DE LA CARTE PREPARATION -->
                
            
            
            <!-- DEBUT DE LA CARTE INGREDIENTS -->
            <div class="show_drawer">

                <div class="card-header-section">
                    <div class="icon form-logo">
                        <img src="../img/food.svg" alt="icône représentant un panier d'ingrédients" srcset="">
                    </div>
                    <div class="card-header title">
                        <h3 class="contact-section">Ingrédients</h3>
                    </div>
                </div>
                <div class="ingredients js-ingredients-list">
                    <div class="">
                    <template id="ingredient-template">
                        <div contenteditable="false" type="text" class="custom-ingredient">
                            <p contenteditable="false" class="js-value"></p>
                        </div>
                    </template>
                        <p class="label">Vos ingrédients</p>
                        <div class="ingredient-stack js-ingredient-group-all-resolution js-ingredient-group js-modal-stop"></div>
                        <div class="ingredient">
                            <select class="select js-select1" name="ingredient" id="ingredient1" aria-placeholder="test">
                                <option type="text" class="test"></input>
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_1'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select2" name="ingredient2" id="ingredient2" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_2'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select3" name="ingredient3" id="ingredient3" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_3'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select4" name="ingredient4" id="ingredient4" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_4'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select5" name="ingredient5" id="ingredient5" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_5'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                            <select class="select js-select6" name="ingredient6" id="ingredient6" aria-placeholder="test">
                                <option value="oeuf" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'oeuf' ? htmlspecialchars('selected') : null ?>>Oeuf</option>
                                <option value="sel" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'sel' ? htmlspecialchars('selected') : null ?>>Sel</option>
                                <option value="sucre" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'sucre' ? htmlspecialchars('selected') : null ?>>Sucre</option>
                                <option value="beurre" <?= $getInfos !== null && $getInfos['ingredient_6'] === 'beurre' ? htmlspecialchars('selected') : null ?>>Beurre</option>
                            </select>
                        </div>
                    </div>
                    <div class="add_ingredient">
                        <label for="custom_ingredient_all_resolutions" class="label">Votre ingrédient particulier</label>
                        <input id="custom_ingredient_all_resolutions" type="text" name="custom_ingredient_all_resolutions" class="input js-ingredient-input" placeholder="Votre ingrédient...">
                        <button name="add_custom_all_resolution" id="add_custom_all_resolution" type="button" class="btn js-add-custom">Ajouter un nouvel ingrédient</button>
                    </div>
                    
                </div>
                
            </div>
            <!-- FIN DE LA CARTE INGREDIENTS -->

            <!-- File -->
            <div class="img_preview">
                <label id="add_image" for="file" class="label"> Ajouter une image</label>
                <div class="profile-picture" style="background-image: url('<?php if ($getInfos !== null) echo $getInfos['img_path']?>');">
                    <h1 class="upload-icon">
                        <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                    </h1>
                    <input class="file-uploader" type="file" id="file" name="file" class="form"/>
                </div>
                <!-- <hr> -->
            </div>
            
            <!-- Send Button -->
            <div class="add_ingredient">
                <hr>
                <button name="add_preparation" id="submit" type="submit" class="btn">Valider vos modifications</button>
            </div>
        </section>
    </form>
</section>