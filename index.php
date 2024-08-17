<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

// if(session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// include_once('includes/functions.inc.php');
// include_once('logs/customErrorHandlers.php');
// require_once("includes/class-autoloader.inc.php");
require_once(__DIR__ . "/includes/class-autoloader.inc.php");
require_once(__DIR__ . "/logs/customErrorHandlers.php");
require_once(__DIR__ . "/includes/functions.inc.php");

$day = date("d");
$month = date("m");
$year = date("Y");
$hour = date("H");
$minut = date("i");
$seconds = date("s");


//echo 'Bonjour ! Nous sommes le ' . $day . '/' . $month . '/' . $year . ' et il est ' . $hour. ' h ' . $minut .  ' et ' .  $seconds . ' secondes';
//print_r($_COOKIE);

//$css = 'rel="stylesheet" href="css/index.css"';
$script = 'data-name="typewritter" src="scripts/typeWriter.js" type="module" defer';
$script2 = 'src="scripts/fadeInScroller.js" defer';
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
<?php require_once('login.php')?>
<!-- Fin du Form -->

<!-- Si l'utilisateur est bien connecté il peut voir les recettes -->
<?php if (isset($loggedUser['user'][0]) || isset($loggedUser['email'])): ?>
    <?php //header_remove('Location: index.php?login=success')?>
    <?php // require_once("includes/class-autoloader.inc.php");?>
    <?php $recipes = new LoginView([]); ?>
    <?php // print_r($recipes->displayRecipes())?>
    <?php foreach ($recipes->displayRecipes() as $recipe) : ?>
        <?php echo display_recipe($recipe); ?>
            <article class="article">
            <h3><a href="./recipes/read.php?id=<?php echo($recipe['recipe_id']) ?>"><?php echo($recipe['title']) ?></a></h3>
                <h3><?php echo $recipe['title']; ?></h3>
                <div><?php echo $recipe['recipe']; ?></div>
                <i><?php echo displayAuthor($recipe["author"]) ?></i>
                <?php //print_r($loggedUser)?>
                <?php //print_r($recipe)?>
                <?php if (isset($loggedUser['email']) && $recipe['author'] === $loggedUser['email']) : ?>
                <!-- <?php //if (isset($loggedUser) && $recipe['author'] === $loggedUser['email'][0]) :?> -->
                    <ul class="list-group">
                        <li class="list-group-item"><a class="link-warning" href="./recipes/update_recipes.php?id=<?php echo($recipe['recipe_id']) ?>">Editer l'article</a></li>
                        <li class="list-group-item"><a class="link-danger" href="./recipes/delete_recipes.php?id=<?php echo($recipe['recipe_id']) ?>">Supprimer l'article</a></li>
                    </ul>
                <?php endif ?>
                <hr />
            </article>
    <?php endforeach ?>
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
            data-endpoint ="<?php // $rootUrl . $clicServer . '/recipes/Process_PreparationList.php'?>"
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
    <?php session_unset()?>
<?php endif ?>
<section class="hero small">
    <div class="">
        <div class="">
            La newsletter pour les gourmands !
        </div>
        <button type="button" class="btn">S'abonner</button>
        <div class="">
            Ne manquez plus d'inspiration
        </div>
        <div class="">
            Surprenez vos convives !
        </div>
        <div class="">
            Laissez-vous guider chaques semaine
        </div>
    </div>
    
</section>

<?php
$content = ob_get_clean();
require('templates/layout.php')
?>
