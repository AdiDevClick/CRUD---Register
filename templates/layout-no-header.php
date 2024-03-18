<?php declare(strict_types=1);


if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
    <title>
        <?= $title ?>
    </title>
</head>
<body>
    <!-- Start Header Content -->  
    <header> 
        <div class="navbar">
            <div class="logo"><a href="#" >Adi Dev Click </a></div>
                <ul class="links">
                    <li><a href="<?php echo($rootUrl). './index.php' ?>">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Ma ToDo list</a></li>
                    <li><a href="<?php echo($rootUrl). './contact.php' ?>">Contact</a></li>
                    <?php //$setLoggedStatus ?>
                    <?php if(!isset($_SESSION['LOGGED_USER'])): ?>
                        <li><a href="<?php echo($rootUrl). './login.php' ?>">Se connecter</a></li>  
                        <li><a href="<?php echo($rootUrl). './register.php' ?>">S'enregistrer</a></li>   
                    <?php endif?>                 
                    <?php if(isset($_SESSION['LOGGED_USER'])): ?>
                    <li><a href="<?php echo($rootUrl). './recipes/create_recipes.php' ?>">Créer une recette</a></li>
                    <li><a href="<?php echo($rootUrl). './deconnexion.php' ?>">Se déconnecter</a></li>
                    <li><a href="<?php echo($rootUrl). './comments/comments.php' ?>">Commentaires</a></li>
                    <?php endif?>
                </ul>
                <a href="#" class="action_btn">Get Started</a>
                <div class="toggle_btn">
                    <i class="fa-regular fa-bars"></i>
                </div>
            
        </div>
    </header>     
    <!-- End Header Content -->

    <!-- Start Body Content -->    
    <?= $content ?>
    <!-- End Body Content -->

    <!-- footer.php -->
    <footer class="">
        <div class="">
            © 2023 Copyright:
            <a class="" href="https://github.com/AdiDevClick/">Adi Dev Click</a>
        </div>
    </footer>
    <!-- end of footer -->
</body>
</html>