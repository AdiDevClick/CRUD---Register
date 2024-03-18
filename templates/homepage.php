<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once('includes/functions.inc.php');
include_once('logs/customErrorHandlers.php');

$day = date("d");
$month = date("m");
$year = date("Y");
$hour = date("H");
$minut = date("i");
$seconds = date("s");

echo 'Bonjour ! Nous sommes le ' . $day . '/' . $month . '/' . $year . ' et il est ' . $hour. ' h ' . $minut .  ' et ' .  $seconds . ' secondes';

?>

<?php $title = "Affichage de recettes"; ?>

<?php ob_start()?>
<!-- Insertion du login form pour les non-connectés -->
<?php include_once('login.php')?>
    <div class="container">
        <h1>Site de recettes !</h1>
    <?php //require_once("includes/class-autoloader.inc.php");?>
    
<!-- Si l'utilisateur est bien connecté il peut voir les recettes -->  
<?php if (isset($loggedUser)):?> 
        <?php require_once("includes/class-autoloader.inc.php"); ?>
        <?php $recipes = new LoginView([]); ?>
        <!-- <?php //$recipes = new LoginView()?> -->
        <?php foreach ($recipes->displayRecipes() as $recipe) : ?>
            <?php echo display_recipe($recipe); ?>
                <article class="article">
                <h3><a href="./recipes/read.php?id=<?php echo($recipe['recipe_id']) ?>"><?php echo($recipe['title']) ?></a></h3>
                    <h3><?php echo $recipe['title']; ?></h3>
                    <div><?php echo $recipe['recipe']; ?></div>                         
                    <i><?php echo displayAuthor($recipe["author"]) ?></i>                    
                    <?php if (isset($loggedUser) && $recipe['author'] === $loggedUser['email']) : ?>
                        <ul class="list-group">
                            <li class="list-group-item"><a class="link-warning" href="./recipes/update_recipes.php?id=<?php echo($recipe['recipe_id']) ?>">Editer l'article</a></li>
                            <li class="list-group-item"><a class="link-danger" href="./recipes/delete_recipes.php?id=<?php echo($recipe['recipe_id']) ?>">Supprimer l'article</a></li>
                        </ul>
                    <?php endif ?>
                    <hr />
                </article>
                
        <?php endforeach ?>
        
    </div>
    <?php endif ?>
<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>