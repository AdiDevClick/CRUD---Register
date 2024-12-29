<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/includes/common.php";
require_once __DIR__ . "/includes/variables.inc.php";
require_once __DIR__ . "/includes/functions.inc.php";

$loggedUser = LoginController::checkLoggedStatus();

if (!isset($loggedUser['user'])) {
    header('Location: index.php?login=failed', true);
} else {
    $sessionName = 'ACCOUNT_RECIPES';
    $params = [
        'fields' => ['r.title', 'i.img_path', 'r.recipe_id'],
        'table' => ['recipes r'],
        'join' => [
            'images i' => 'r.recipe_id = i.recipe_id',
            'users u' => 'u.user_id = :recipe_id',
        ],
        "where" => [
            "conditions" => [
                "r.author" => "= u.email"
            ],
        ],
        'error' => ["Erreur dans la récupération de la recette"],
        'fetchAll' => true
    ];

    $id = new RecipeView((int) $loggedUser["userId"]);

    try {
        // Retrieve all recipe infos
        $response = $id->retrieveFromTable($params, $sessionName);
        if (isset($_SESSION[$sessionName])) {
            // foreach ($getInfos as $key => $value) {
            //     if (!in_array($key, $filterKeysToRemove)) {
            //         $recipe[$key] = $value;
            //     }
            // }
            unset($_SESSION[$sessionName]);
        } else {
            throw new Error("Quelque chose d'anormal s'est produit lors de la récupération de la recette");
        }
    } catch (\Throwable $error) {
        echo $error->getMessage();
    }

    // var_dump($response, $loggedUser["userId"]);
    // die(var_dump($response));
}

$css = "rel='stylesheet' href='$rootUrl/$clicServer/css/settings.css'";
$scripts = [
    'src="scripts/carouselApp.js" type="module" async'
];
$title = "Votre compte";

ob_start();
?>
<h1>Votre profil</h1>

<div class="settings-grid">

    <aside class="settings">
        <h2 class="hidden">Menu Icon List</h2>

        <div class="picture">
            <?php include "./templates/profile_picture.html" ?>
            <img class="edit_img" src="img/edit.png" alt="" srcset="">
            <p>Adi</p>
        </div>

        <ul class="settings-icons">
            <li><?php include "./templates/profile_picture.html" ?></li>
            <li><?php include_once "./templates/settings_icon_template.html" ?></li>
            <li><?php include_once "./templates/book_icon.html" ?></li>
            <li><?php include_once "./templates/lock_icon.html" ?></li>
            <li><?php include_once "./templates/favorite_icon.html" ?></li>
        </ul>
    </aside>

    <div class="card">
        <div class="parameter">
            <article class="item_container display-list">
                <div class="item__image">
                    <img class="js-img" src="http://localhost/recettes/uploads/ptitbarba@hotmail.com/recipes_images/976/1733940472.png" alt="">
                    <div class="js-youtube-player" id="UzRY3BsWFYg"></div>
                </div>
                <div class="item__body no-fade">
                    <div class="item__title js-title">
                        {{ Mon titre est tr_s tr_s tr_s grand }}
                    </div>
                </div>
                <div class="item__buttons">
                    <img src="img/edit.svg" alt="" class="item__modify" name="modify" id="modify-{{id}}">
                    <!-- <img src="img/bin.svg" alt="" class="item__delete" name="delete" id="delete-{{id}}"> -->
                    <?php include "img/bin.svg" ?>
                </div>
            </article>

        </div>
        <div class="parameter">
            <!-- <div class="theme-selection"> -->
            <p>Activer les notifications</p>
            <!-- <img src="" alt=""> -->
            <!-- </div> -->
            <div class="toggle">
                <input type="checkbox" id="notifications-toggle">
                <label for="notifications-toggle">
            </div>
        </div>
        <div class="parameter">
            <!-- <div class="theme-selection"> -->
            <p>Recevoir des newsletters</p>
            <!-- <img src="" alt=""> -->
            <!-- </div> -->
            <div class="toggle">
                <input type="checkbox" id="newsletters-toggle">
                <label for="newsletters-toggle">
            </div>
        </div>
        <div class="parameter">
            <!-- <div class="theme-selection"> -->
            <p>Mes recettes</p>

            <!-- <img src="" alt=""> -->
            <!-- </div> -->

        </div>
        <div class="parameter">
            <div id="user-recipes">
                <?php
                foreach ($response as $data) {
                    // Récupérer le contenu du template des tooltips delete/edit
                    $template = file_get_contents("./templates/user_recipes_template.php");
                    preg_match('/<template id="user-recipes-template">(.*)<\/template>/s', $template, $matches);
                    $templateContent = $matches[1];
                    //Utiliser le comment_id pour modifier la variable 'id' du template
                    // $comment['id'] = $comment['comment_id'];
                    echo renderTemplate($templateContent, $data);
                }
                ?>
            </div>
            <!-- <img src="" alt="">
            <p>Titre</p>
            <button></button>
            <button></button> -->
        </div>

    </div>

</div>


<?php
$content = ob_get_clean();
require 'templates/layout.php';
?>