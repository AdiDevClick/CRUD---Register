<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger la configuration
// require_once dirname(__DIR__, 2) . '/config/config.php';
// require_once ROOT_PATH . "includes" . DIRECTORY_SEPARATOR ."class-autoloader.inc.php";
// Charger le fichier commun
require_once __DIR__ . "/includes/common.php";
require_once __DIR__ . "/includes/functions.inc.php";

// if(session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// include_once('includes/functions.inc.php');
// include_once('logs/customErrorHandlers.php');
// require_once("vendor/class-autoloader.inc.php");
// require_once(__DIR__ . "/vendor/class-autoloader.inc.php");
// require_once(__DIR__ . "/logs/customErrorHandlers.php");

// $day = date("d");
// $month = date("m");
// $year = date("Y");
// $hour = date("H");
// $minut = date("i");
// $seconds = date("s");


//echo 'Bonjour ! Nous sommes le ' . $day . '/' . $month . '/' . $year . ' et il est ' . $hour. ' h ' . $minut .  ' et ' .  $seconds . ' secondes';
//print_r($_COOKIE);

//$css = 'rel="stylesheet" href="css/index.css"';
$scripts = [
    'data-name="typewritter" src="scripts/typeWriter.js" type="module" defer',
    'src="scripts/fadeInScroller.js" defer',
    'src="scripts/carouselApp.js" type="module" async'
];

$title = "Affichage de recettes";

ob_start()
?>

<!-- Héro Section -->
<section class="hero">
    <!-- <div class="type-writter"> -->
    <img src="img/img3.jpeg" alt="">
    <p>Une recette <span>Love</span></p>
    <!-- </div> -->
</section>

<!-- Insertion du login form pour les non-connectés -->
<section class="container">
    <div class="form-hidden">
        <!-- <div class="form-index form-hidden"> -->
        <h1>Profitez de nos recettes !</h1>
    </div>

    <!-- Insertion du login form pour les non-connectés -->
    <?php require_once 'login.php' ?>
    <!-- Fin du Form -->

    <!-- Si l'utilisateur est bien connecté il peut voir les recettes -->
    <?php if (isset($loggedUser['user'][0]) || isset($loggedUser['email'])): ?>
        <?php //header_remove('Location: index.php?login=success')
        ?>
        <?php // require_once("vendor/class-autoloader.inc.php");
        ?>
        <?php $recipes = new LoginView([]); ?>
        <!-- <article class="article"> -->
        <?php // print_r($recipe) 
        ?>
        <!-- <h3><a href="./recipes/read.php?id=<?php // echo ($recipe['recipe_id']) 
                                                ?>"><?php // echo ($recipe['title']) 
                                                    ?></a></h3> -->
        <!-- <div><?php // $recipe['recipe'] 
                    ?></div> -->
        <!-- <i><?php // echo displayAuthor($recipe["author"]) 
                ?></i> -->
        <section class="carousel_container">
            <div id="carousel1" class="full-width">
                <?php foreach ($recipes->displayRecipes() as $recipe) : ?>
                    <article class="item_container">
                        <div class="item__image">
                            <img class="js-img" src="<?= $recipe['img_path'] ?>" alt="">
                            <div class="js-youtube-player" id="UzRY3BsWFYg"></div>
                        </div>
                        <div class="item__body">
                            <div class="item__title js-title">
                                <?= $recipe['title'] ?>
                            </div>
                            <div class="item__description">
                                <?= $recipe['description'] ?>
                            </div>
                            <div class="item__author">
                                Partagée par <span class="js-author"><?= displayAuthor($recipe["author"]) ?></span>
                            </div>
                        </div>
                        <?php if (isset($loggedUser['email']) && $recipe['author'] === $loggedUser['email']) : ?>
                            <ul class="list-group">
                                <li class="list-group-item"><a class="link-warning" href="./recipes/update_recipes.php?id=<?= $recipe['recipe_id'] ?>">Editer l'article</a></li>
                                <li class="list-group-item"><a class="link-danger" href="./recipes/delete_recipes.php?id=<?= $recipe['recipe_id'] ?>">Supprimer l'article</a></li>
                            </ul>
                        <?php endif ?>
                        <a class="file-uploader js-href" href="./recipes/read.php?id=<?= $recipe['recipe_id'] ?>"></a>
                    </article>
                <?php endforeach ?>
            </div>
        </section>

        <hr />
        <!-- </article> -->
        <!-- <div class="searched-recipes"></div> -->

        <!-- <template id="search-template">
            <article class="mb-4" id="1">
                <div class="mb-1"><strong class="js-title">John Doe</strong></div>
                <p class="js-author">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem vel temporibus quos, 
                    accusantium culpa, praesentium delectus architecto quaerat animi magni explicabo debitis, 
                    velit quod libero. Quas nesciunt ut repellendus praesentium.</p>
            </article>
        </template> -->

        <!-- <div 
            data-endpoint ="<?php // $rootUrl . $clicServer . '/recipes/Process_PreparationList.php'
                            ?>"
            data-template="#search-template"
            data-target=".searched-recipes"
            data-limit="10"
            data-form=".search-form"
            data-id='{"recipe_id": "#id"}'
            data-elements='{"title": ".js-title", "author": ".js-author"}'
            class="text-center js-infinite-pagination">
            <div class="spinner-border" role="status"></div>
        </div> -->
</section>
<?php else: ?>
    <?php session_unset() ?>
<?php endif ?>
<section class="counter">
    <div class="div">
        <span class="number">350</span>
        <p class="text">Recettes partagées</p>
    </div>
    <div class="div">
        <span class="number">100</span>
        <p class="text">Utilisateurs</p>
    </div>
    <div class="div">
        <span class="number">5000</span>
        <p class="text">Vues</p>
    </div>
    <div class="div">
        <span class="number">200</span>
        <p class="text">Utilisateurs quotidiens</p>
    </div>
</section>
<section class="hero small">
    <div class="">
        <div class="">
            <p>La newsletter pour les gourmands !</p>
        </div>
        <button type="button" class="btn">S'abonner</button>
        <div class="">
            <img src="" alt="">
            <p>Ne manquez plus d'inspiration</p>
        </div>
        <div class="">
            <img src="" alt="">
            <p>Surprenez vos convives !</p>
        </div>
        <div class="">
            <img src="" alt="">
            <p>Laissez-vous guider chaques semaine</p>
        </div>
    </div>

</section>

<?php
$content = ob_get_clean();
require('templates/layout.php')
?>