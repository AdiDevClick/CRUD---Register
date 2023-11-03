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

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<header>
    <div class="navbar">
        <div class="logo"><a href="#" >Adi Dev Click </a></div>
            <ul class="links">
                <li><a href="<?php echo($rootUrl). 'recettes/index.php' ?>">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Ma ToDo list</a></li>
                <li><a href="<?php echo($rootUrl). 'recettes/contact.php' ?>">Contact</a></li>
                <?php if(!isset($_SESSION['LOGGED_USER'])) : ?>
                    <li><a href="<?php echo($rootUrl). 'recettes/login.php' ?>">Se connecter</a></li>  
                    <li><a href="<?php echo($rootUrl). 'recettes/register.php' ?>">S'enregistrer</a></li>   
                <?php endif ?>    
                
                <?php if(isset($_SESSION['LOGGED_USER'])) : ?>
                    <li><a href="<?php echo($rootUrl). 'recettes/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                    <li><a href="<?php echo($rootUrl). 'recettes/deconnexion.php' ?>">Se déconnecter</a></li>
                    <li><a href="<?php echo($rootUrl). 'recettes/comments/comments.php' ?>">Commentaires</a></li>
                <?php endif ?>
            </ul>
            <a href="#" class="action_btn">Get Started</a>
            <div class="toggle_btn">
                <i class="fa-regular fa-bars"></i>
            </div>
        </div>
</header>
