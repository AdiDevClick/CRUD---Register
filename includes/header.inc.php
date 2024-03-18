<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once("variables.inc.php");
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';


?>
    <div class="navbar">
        <div class="logo"><a href="#" >Adi Dev Click </a></div>
            <ul class="links">
                <li><a href="<?php echo($rootUrl). 'recettes/index.php' ?>">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Ma ToDo list</a></li>
                <li><a href="<?php echo($rootUrl). 'recettes/contact.php' ?>">Contact</a></li>
                <?php if(!isset($_SESSION['LOGGED_USER'])): ?>
                    <li><a href="<?php echo($rootUrl). 'recettes/login.php' ?>">Se connecter</a></li>  
                    <li><a href="<?php echo($rootUrl). 'recettes/register.php' ?>">S'enregistrer</a></li>   
                <?php endif?>
                
                <?php if(isset($_SESSION['LOGGED_USER'])): ?>
                    <li><a href="<?php echo($rootUrl). 'recettes/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                    <li><a href="<?php echo($rootUrl). 'recettes/deconnexion.php' ?>">Se déconnecter</a></li>
                    <li><a href="<?php echo($rootUrl). 'recettes/comments/comments.php' ?>">Commentaires</a></li>
                <?php endif?>
            </ul>
                <a href="#" class="action_btn">Get Started</a>
            <div class="toggle_btn">
                <i class="fa-regular fa-bars"></i>
            </div>
        
    </div>
