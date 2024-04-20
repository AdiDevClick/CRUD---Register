<?php

require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR ."Functions.class.php");
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."variables.inc.php");
// $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
// $url = array_pop($url);


// $url = Functions::getUrl();
// $rootPath = $_SERVER['DOCUMENT_ROOT'];
// $rootUrl = Functions::getRootUrl();
// // $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
// if ($rootUrl === 'https://adi.ezaya.fr/') {
//     $clicServer = 'ClicRepare';
// } else {
//     $clicServer = 'recettes';
// }

if ($url === 'about.php' || 'planningType.php' || 'todo.html' || 'carousel.html' || 'contact.php' || 'index.php') {
    $active = strip_tags('class="active"');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="public">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo($rootUrl). $clicServer?>/css/reset.css">
    <!-- <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo($rootUrl). $clicServer?>/css/main.css"/>
    <link rel="stylesheet" href="<?php echo($rootUrl). $clicServer?>/css/index.css"/>
    <link 
    <?= $css ?? '' ?>
    />
    <script src="<?php echo($rootUrl). $clicServer?>/scripts/script.js" defer></script>
    <script type="module" src="<?php echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script>
    <script <?= $script ?? '' ?>></script>
    <script <?= $script2 ?? '' ?>></script>
    <title>
        <?= $title ?? "Clic'Répare" ?>
    </title>
</head>
<body>
    <!-- Start Header Content -->
    <header class="header-main wrapper">
        <nav>
            <div class="dropdown-menu-background">
                <div class="dropdown-menu">
                        <li><a
                        <?php if ($url === 'index.php') {
                            echo strip_tags('class="active"');
                        } else {
                            null;
                        }?>
                        href="<?php echo($rootUrl). $clicServer.'/index.php' ?>">Accueil</a></li>
                        <li><a
                        <?php if ($url === 'about.php') {
                            echo strip_tags('class="active"');
                        } else {
                            null;
                        }?>
                        href="#">About</a></li>
                        <li><a
                        <?php if ($url === 'planningType.php') {
                            echo strip_tags('class="active"');
                        } else {
                            null;
                        }?>
                        href="<?php echo($rootUrl). $clicServer.'/planning/planningType.php' ?>">Planning</a></li>
                        <li><a
                        <?php if ($url === 'todo.html') {
                            echo strip_tags('class="active"');
                        } else {
                            null;
                        }?>
                        href="<?php echo($rootUrl). $clicServer.'/todo.html' ?>">Ma ToDo list</a></li>
                        <li><a
                        <?php if ($url === 'carousel.html') {
                            echo strip_tags('class="active"');
                        } else {
                            null;
                        }?>
                        href="<?php echo($rootUrl). $clicServer.'/carousel.html' ?>">Carousel Exemple</a></li>
                        <li><a
                        <?php if ($url === 'contact.php') {
                            echo strip_tags('class="active"');
                        }?>
                        href="<?php echo($rootUrl). $clicServer.'/contact.php' ?>">Contact</a></li>
                    <?php //$setLoggedStatus?>
                    <?php //if(!isset($_SESSION['LOGGED_USER'])):?>
                    <?php if(!isset($_COOKIE['EMAIL'])): ?>
                    <?php //if(!isset($loggedUser['email'][0])):?>
                        <li><a class="" href="<?php echo($rootUrl). $clicServer.'/login.php' ?>">Se connecter</a></li>
                        <li><a class="action_btn" href="<?php echo($rootUrl). $clicServer.'/register.php' ?>">S'enregistrer</a></li>
                    <?php endif?>
                    <?php if(isset($_COOKIE['EMAIL'])): ?>
                    <?php //if(isset($_SESSION['LOGGED_USER'])):?>
                    <?php //if(isset($loggedUser['email'][0])):?>
                        <li><a <?php if ($url === 'create_recipes.php') {
                            echo strip_tags('class="active"');
                        }?> class="" href="<?php echo($rootUrl). $clicServer.'/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                        <li><a class="" href="<?php echo($rootUrl). $clicServer.'/deconnexion.php' ?>">Se déconnecter</a></li>
                    <?php endif?>
                </div>
            </div class="dropdown-menu-background">
        
            <div class="navbar">
                <div class="logo"><a href="<?php echo strip_tags($rootUrl). $clicServer.'/index.php' ?>">Adi Dev Click </a></div>
                <ul class="links">
                    <li><a
                    <?php if ($url === 'index.php') {
                        echo strip_tags('class="active"');
                    } else {
                        null;
                    }?>
                    href="<?php echo($rootUrl). $clicServer.'/index.php' ?>">Accueil</a></li>
                    <li><a
                    <?php if ($url === 'about.php') {
                        echo strip_tags('class="active"');
                    } else {
                        null;
                    } ?>
                    href="#">About</a></li>
                    <li><a
                    <?php if ($url === 'planningType.php') {
                        echo strip_tags('class="active"');
                    } else {
                        null;
                    } ?>
                    href="<?php echo($rootUrl). $clicServer.'/planning/planningType.php' ?>">Planning</a></li>
                    <li><a
                    <?php if ($url === 'carousel.html') {
                        echo strip_tags('class="active"');
                    } else {
                        null;
                    } ?>
                    href="<?php echo($rootUrl). $clicServer.'/carousel.html' ?>">Carousel Exemple</a></li>
                    <li><a
                    <?php if ($url === 'todo.html') {
                        echo strip_tags('class="active"');
                    } else {
                        null;
                    } ?>
                    href="<?php echo($rootUrl). $clicServer.'/todo.html' ?>">Ma ToDo list</a></li>
                    <li><a
                    <?php if ($url === 'contact.php') {
                        echo strip_tags('class="active"');
                    } else {
                        null;
                    } ?>
                    href="<?php echo($rootUrl). $clicServer.'/contact.php' ?>">Contact</a></li>
                    <?php //$setLoggedStatus?>
                    <?php if(!isset($_COOKIE['EMAIL'])): ?>
                    <?php //if(!isset($_SESSION['LOGGED_USER'])):?>
                    <?php //if(!isset($loggedUser['email'][0])):?>
                        <li><a class="" href="<?php echo($rootUrl). $clicServer.'/login.php' ?>">Se connecter</a></li>  
                        <!-- <li><a class="" href="<?php //echo($rootUrl). 'recettes/register.php'?>">S'enregistrer</a></li> -->
                    <?php endif?>
                    <?php if(isset($_COOKIE['EMAIL'])): ?>
                    <?php //if(isset($_SESSION['LOGGED_USER'])):?>
                    <?php //if(isset($loggedUser['email'][0])):?>
                        <li><a <?php if ($url === 'create_recipes.php') {
                            echo strip_tags('class="active"');
                        }?> class="" href="<?php echo($rootUrl). $clicServer.'/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                        <li><a class="" href="<?php echo($rootUrl). $clicServer.'/deconnexion.php' ?>">Se déconnecter</a></li>
                    <?php endif?>
                </ul>
                <?php if(!isset($_COOKIE['EMAIL'])): ?>
                <?php //if(!isset($_SESSION['LOGGED_USER'])):?>
                <?php //if(!isset($loggedUser['email'][0])):?>
                    <a href="<?php echo($rootUrl). $clicServer.'/register.php' ?>" class="action_btn">S'enregistrer</a>
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
        <!-- </div class="dropdown-menu-background"> -->
        </nav>

    </header>
    <!-- End Header Content -->
    <main class="wrapper">
    <!-- Start Main Content -->
        <?= $content ?? ''?>
    <!-- End Main Content -->
    </main>
    <!-- footer.php -->
    <footer class="wrapper">
        <div class="">
            <p>© 2023 Copyright <a class="" href="https://github.com/AdiDevClick/">Adi Dev Click</a></p>
        </div>
    </footer>
    <!-- end of footer -->
</body>

</html>

<?php require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "toaster_template.html") ?>
