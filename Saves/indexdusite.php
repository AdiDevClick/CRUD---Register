<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

// if(session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

include_once('includes/functions.inc.php');
include_once('logs/customErrorHandlers.php');
require_once("vendor/class-autoloader.inc.php");

$day = date("d");
$month = date("m");
$year = date("Y");
$hour = date("H");
$minut = date("i");
$seconds = date("s");

//echo 'Bonjour ! Nous sommes le ' . $day . '/' . $month . '/' . $year . ' et il est ' . $hour. ' h ' . $minut .  ' et ' .  $seconds . ' secondes';
//print_r($_COOKIE);

//$css = 'rel="stylesheet" href="css/index.css";
$scripts = [
    'src="scripts/typeWriter.js" defer',
    'src="scripts/fadeInScroller.js" defer'
];

$title = "Affichage de recettes";

// <?php //$css = 'rel="stylesheet" href="css/index.css"'
// <?php //$script = 'src="scripts/typeWriter.js" defer'
// <?php //$script2 = 'src="scripts/fadeInScroller.js" defer'
// <?php //$title = "Affichage de recettes"
$url = Functions::getUrl();
?>

<?php ob_start() ?>
<section class="hero">
    <div class="type-writter">
        <p>Une recette <span>Love</span></p>
    </div>
</section>

<!-- Insertion du login form pour les non-connectés -->

<section class="container">
    <div class="form-hidden">
        <!-- <div class="form-index form-hidden"> -->
        <h1>Profitez de nos recettes !</h1>
    </div>

    <?php //require_once("vendor/class-autoloader.inc.php");
    ?>

    <?php //ob_start()
    ?>
    <?php //header_remove()
    ?>
    <?php //print_r($loggedUser)
    ?>
    <?php //if (!isset($loggedUser)):
    ?>
    <?php require_once('login.php') ?>

    <!--
    Début du Form
-->
    <?php if (!isset($loggedUser['email'])): ?>
        <?php print_r('index form :'); ?>
        <div class="form-index">
            <form action="login.php" method="post">
                <!-- Affichage du message d'erreur -->
                <?php if (!empty($err) && (isset($err['userError']) || isset($err['userTaken']))): ?>
                    <?php $errorMessage = $err['userError'] ?? $err['emailTaken'] ?>
                    <div>
                        <p class="alert-error"><?php echo strip_tags($errorMessage) ?></p>
                    </div>
                <?php endif ?>
                <!-- Username -->
                <div class="splash-login form-hidden">
                    <label for="username">Votre identifiant :</label>
                    <?php if (array_key_exists('errorUsername', $err)) : ?>
                        <input class="input_error" type="text" id="username" name="username" placeholder="<?php echo strip_tags($err['errorUsername']) ?>" autocomplete="username" />
                    <?php else: ?>
                        <input type="text" id="username" name="username" placeholder="exemple@exemple.com" autocomplete="username" />
                    <?php endif ?>
                </div>
                <!-- Password -->
                <div class="splash-login form-hidden">
                    <label for="password"> Votre mot de passe :</label>
                    <?php if (array_key_exists('errorPassword', $err)) : ?>
                        <input class="input_error" type="password" id="password" name="password" placeholder="<?php echo strip_tags($err['errorPassword']) ?>" autocomplete="current-password">
                    <?php else: ?>
                        <input type="password" id="password" name="password" placeholder="****" autocomplete="current-password">
                    <?php endif ?>
                </div>
                <!-- Submit -->
                <div class="splash-login form-hidden">
                    <button type="submit" name="submit" class="btn" id="btn"> S'identifier</button>
                </div>
            </form>
        </div>
        <a class="pw_reset" href="reset-password.php">Mot de passe oublié ?</a>
        <!-- 
    Si l'utilisateur est bien loggé, on affiche le message de succès
-->
    <?php else: ?>
        <?php //require_once('login.php')
        ?>
        <div class="alert-success">
            Bonjour <?php echo strip_tags(ucfirst($loggedUser['name'])) ?> et bienvenue sur le site !
        </div>
    <?php endif ?>
    <?php //print_r($loggedUser)
    ?>
    <?php //endif;
    ?>
    <?php //header_remove()
    ?>
    <?php //ob_get_status()
    ?>
    <?php //ob_get_contents()
    ?>
    <!--
    Fin du Form
-->
    <!--
    Si l'utilisateur est bien connecté il peut voir les recettes
-->
    <?php if (isset($loggedUser['user']) || isset($loggedUser['email'])): ?>
        <?php require_once("vendor/class-autoloader.inc.php"); ?>
        <?php $recipes = new LoginView([]); ?>
        <?php foreach ($recipes->displayRecipes() as $recipe) : ?>
            <?php echo display_recipe($recipe); ?>
            <article class="article">
                <h3><a href="./recipes/read.php?id=<?php echo ($recipe['recipe_id']) ?>"><?php echo ($recipe['title']) ?></a></h3>
                <h3><?php echo $recipe['title']; ?></h3>
                <div><?php echo $recipe['recipe']; ?></div>
                <i><?php echo displayAuthor($recipe["author"]) ?></i>
                <?php //print_r($loggedUser)
                ?>
                <?php if (isset($loggedUser['email']) && $recipe['author'] === $loggedUser['email']) : ?>
                    <!-- <?php //if (isset($loggedUser) && $recipe['author'] === $loggedUser['email'][0]) :
                            ?> -->
                    <ul class="list-group">
                        <li class="list-group-item"><a class="link-warning" href="./recipes/update_recipes.php?id=<?php echo ($recipe['recipe_id']) ?>">Editer l'article</a></li>
                        <li class="list-group-item"><a class="link-danger" href="./recipes/delete_recipes.php?id=<?php echo ($recipe['recipe_id']) ?>">Supprimer l'article</a></li>
                    </ul>
                <?php endif ?>
                <hr />
            </article>
        <?php endforeach ?>
</section>
<?php else: ?>
    <?php //session_unset()
    ?>
<?php endif ?>
<?php $content = ob_get_clean() ?>
<?php require('templates/layout.php') ?>