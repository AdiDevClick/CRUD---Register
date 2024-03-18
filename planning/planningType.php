<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer" /> 

    <!-- <link rel="stylesheet" href="../css/main.css"/> --> 
    <link rel="stylesheet" href="../css/index.css"/> 
    <link rel="stylesheet" href="../css/planning.css">
     
    <script src="../scripts/dragBox.js" defer></script>
    <script src="../scripts/script.js" defer></script>
    <title>Document</title>
</head>
<body>
<?php $rootPath = $_SERVER['DOCUMENT_ROOT']?>
<?php $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/'?>

<header class="leftside-menu-background">
    <div class="dropdown-menu-background">
        <div class="dropdown-menu">
                <li><a class="" href="<?php echo($rootUrl). 'recettes/index.php' ?>">Home</a></li>
                <li><a class="" href="#">About</a></li>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/planning/planningType.php' ?>">Planning</a></li>
                <li><a class="" href="#">Ma ToDo list</a></li>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/contact.php' ?>">Contact</a></li>
            <?php //$setLoggedStatus?>
            <?php if(!isset($_SESSION['LOGGED_USER'])): ?>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/login.php' ?>">Se connecter</a></li>
                <li><a class="action_btn" href="<?php echo($rootUrl). 'recette/register.php' ?>">S'enregistrer</a></li>
            <?php endif?>
            <?php if(isset($_SESSION['LOGGED_USER'])): ?>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/deconnexion.php' ?>">Se déconnecter</a></li>
            <?php endif?>
        </div>
    </div class="dropdown-menu-background">
    
    <div class="leftside-menu">
        <div class="logo"><a href="<?php echo($rootUrl). 'recettes/index.php' ?>">Adi Dev Click </a></div>
        <ul class="links">
            <li><a class="" href="<?php echo($rootUrl). 'recettes/index.php' ?>">Home</a></li>
            <li><a class="" href="#">About</a></li>
            <li><a class="" href="<?php echo($rootUrl). 'recettes/planning/planningType.php' ?>">Planning</a></li>
            <li><a class="" href="#">Ma ToDo list</a></li>
            <li><a class="" href="<?php echo($rootUrl). 'recettes/contact.php' ?>">Contact</a></li>
            <?php //$setLoggedStatus?>
            <?php if(!isset($_SESSION['LOGGED_USER'])): ?>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/login.php' ?>">Se connecter</a></li>
                <!-- <li><a class="" href="<?php //echo($rootUrl). 'recettes/register.php'?>">S'enregistrer</a></li> -->
            <?php endif?>
            <?php if(isset($_SESSION['LOGGED_USER'])): ?>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                <li><a class="" href="<?php echo($rootUrl). 'recettes/deconnexion.php' ?>">Se déconnecter</a></li>
            <?php endif?>
        </ul>
        <?php if(!isset($_SESSION['LOGGED_USER'])): ?>
            <a href="<?php echo($rootUrl). 'recettes/register.php' ?>" class="action_btn">S'enregistrer</a>
        <?php endif?>
        <section class="toggle_btn-box">
            <div class="toggle_btn">
                <!-- <i class="fa-solid fa-bars"></i> -->
                <i></i>
                <i></i>
                <i></i>
            </div>
        </section>
    </div> 
      
</header>
    <section class="main-container">
         <header>
            <div class="title">
                Planning
            </div>
            <div class="fas fa-box-open">
                <span></span>
            </div>
        </header>
    </section>
    <section class="container">
        <div>
            
        </div>
    </section>
    <section class="tool-selection">
        <div class="box" draggable="true">Lundi</div>
        <div class="box" draggable="true">Mardi</div>
        <div class="box" draggable="true">Mercredi</div>
        <div class="box" draggable="true">Jeudi</div>
        <div class="box" draggable="true">Vendredi</div>
    </section>
</body>
</html>