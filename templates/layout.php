<?php declare(strict_types=1);

require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR ."Functions.class.php");
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."variables.inc.php");

header('Cache-Control: private, must-revalidate');

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

$dev = true;
// $dev = false;
$vite = false;

?>

<!DOCTYPE html>
<html id="html" lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta content="text/javascript; charset=UTF-8" http-equiv="content-type"> -->
    <!-- <meta cache-control="private"> -->
    <!-- <meta http-equiv="Cache-Control" content="private, must-revalidate"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo($rootUrl). $clicServer?>/css/resetfirefox.css">
    <!-- <link rel="stylesheet" href="<?php //echo($rootUrl). $clicServer?>/css/resetchromium.css"> -->
    <link rel="stylesheet" href="<?php echo($rootUrl). $clicServer?>/css/reset.css">
    <!-- <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous"
    referrerpolicy="no-referrer"/>
    <!-- <script type="module" src="<?php //echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script> -->
    <script src="<?php echo($rootUrl). $clicServer?>/scripts/script.js" defer></script>
    <script src="<?php echo($rootUrl). $clicServer?>/scripts/searchApp.js" type="module" defer></script>

    <!-- <link rel="stylesheet" href="<?php //echo($rootUrl). $clicServer?>/resources/css/main.css"/> -->
    <!-- <link rel="stylesheet" href="<?php //echo($rootUrl). $clicServer?>/resources/css/index.css"/> -->
    <link 
    <?= $css ?? '' ?>
    />
    <?php

// $manifest = json_decode(file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR ."public" . DIRECTORY_SEPARATOR ."assets" . DIRECTORY_SEPARATOR .".vite". DIRECTORY_SEPARATOR ."manifest.json"), true);
// print_r($manifest);
// echo $manifest;
// var_dump($manifest);
if (!$dev) {
    $manifest = json_decode(file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR ."public" . DIRECTORY_SEPARATOR ."assets" . DIRECTORY_SEPARATOR .".vite". DIRECTORY_SEPARATOR ."manifest.json"), true);

    //     $manifest = json_decode(file_get_contents('./public/assets/.vite/manifest.json'), true);
    //     // var_dump($manifest);
    ?>
        <script src="<?php echo($rootUrl) . $clicServer?>/public/assets/<?= $manifest['resources/main.js']['file']?>" type="module"></script>
        <!-- <script src="./public/assets/<?php //$manifest['resources/main.js']['file']?>" type="module"></script> -->
        <script src="<?php echo($rootUrl) . $clicServer?>/public/assets/<?= $manifest['scripts/toaster.js']['file']?>" type="module"></script>
        <!-- <link rel="stylesheet" href="./public/assets/<?php //$manifest['resources/main.js']['css'][0]?>"> -->
        <link rel="stylesheet" href="<?php echo($rootUrl) . $clicServer?>/public/assets/<?= $manifest['resources/main.js']['css'][0]?>">
<?php
} elseif ($dev && $vite) {
    ?>
    <script type="module" src="http://localhost:5173/assets/@vite/client"></script>
    <script type="module" src="http://localhost:5173/assets/resources/main.js"></script>
    <script type="module" src="<?php echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script>
<?php
}
?>

<?php if (!$vite) : ?>
        <link rel="stylesheet" href="<?php echo($rootUrl). $clicServer?>/resources/css/main.css"/>
        <link rel="stylesheet" href="<?php echo($rootUrl). $clicServer?>/resources/css/index.css"/>
        <script type="module" src="<?php echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script>
<?php endif ?>

    <!-- <script type="module" src="<?php //echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script> -->
    <script <?= $script ?? '' ?>></script>
    <script <?= $script2 ?? '' ?>></script>
    <title>
        <?= $title ?? "Clic'Répare" ?>
    </title>
</head>
<body>
    <!-- Start Header Content -->
    <!-- <header class="header-main wrapper"> -->
    <header class="header-main">
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
                        <li><a class="" href="<?php echo($rootUrl). $clicServer.'/index.php#username' ?>">Se connecter</a></li>
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
            </div>
        
            <div class="navbar">
                <div class="logo"><a href="<?php echo strip_tags($rootUrl). $clicServer.'/index.php' ?>">Adi Dev Click </a></div>
                <form
                    id="search-form"
                    class="search-form"
                    action="<?= $rootUrl. $clicServer.'/index.php' ?>"
                    method="get"
                    role="search"
                    data-endpoint ="<?= $rootUrl . $clicServer . '/recipes/Process_PreparationList.php'?>"
                >
                
                <!-- <meta itemprop="target" content="https://www.instant-gaming.com/fr/rechercher/?q={q}"> -->
                    <div class="search" id="search">
                        <!-- <form id="search-form" class="search-form" action="<?php // $rootUrl. $clicServer.'/index.php'?>" method="get" role="search"> -->
                            <input
                                accesskey="s"
                                class="search-input"
                                id="header-search-box-input"
                                name="query"
                                autocomplete="off"
                                spellcheck="false"
                                autocapitalize="none"
                                itemprop="query-input"
                                placeholder="Recherchez votre recette..."
                            >
                        <!-- </form> -->
                        <div class="icon-backspace icon-s">
                            <i></i>
                        </div>
                        <div class="close-search">+</div>
                        <div class="icon-search-input">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                </form>
                
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
                        <li><a class="" href="<?php echo($rootUrl). $clicServer.'/index.php#username' ?>">Se connecter</a></li>
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
                    <a href="<?php echo($rootUrl). $clicServer.'/register.php' ?>" class="action-btn">S'enregistrer</a>
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
    <main id="wrapper" class="wrapper">
    <!-- Start Main Content -->
        <?= $content ?? ''?>
        <div 
            data-endpoint ="<?php // $rootUrl . $clicServer . '/recipes/Process_PreparationList.php'?>"
            data-template="#search-template"
            data-target=".searched-recipes"
            data-limit="5"
            data-form=".search-form"
            data-id='{"recipe_id": "#id"}'
            data-elements='{"title": ".js-title", "author": ".js-author", "img_path": ".js-img", "href": ".js-href"}'
            class="align-center hidden js-infinite-pagination">
            <div class="loader" role="status"></div>
        </div>
    <!-- End Main Content -->
    </main>

    <!-- footer.php -->
    <footer class="">
        <div class="">
            <p>© 2023 Copyright <a class="" href="https://github.com/AdiDevClick/">Adi Dev Click</a></p>
        </div>
    </footer>
    <!-- end of footer -->
</body>
<template id="search-template">
    <!-- <article class="title">Carousel 1 -->
        <!-- <div id="carousel1"> -->
            <article class="item_container">
                <div class="item__image">
                    <img class="js-img"  src=<?= $rootUrl. $clicServer ?> alt="">
                    <div class="player" id="UzRY3BsWFYg"></div>
                </div>
                <div class="item__body">
                    <div class="item__title js-title">
                        <?php //echo($rootUrl). $clicServer?><span></span>
                    </div>
                    <div class="item__description">
                        Ici, une description de mon image
                    </div>
                    <div class="item__author">
                        Partagée par <span class="js-author"></span>
                    </div>
                </div>
                <a class="file-uploader js-href"></button>
            </article>
        <!-- </div> -->
    <!-- </article> -->
    <!-- <article class="mb-4" id="1">
        <div class="mb-1"><strong class="js-title">John Doe</strong></div>
        <p class="js-author">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem vel temporibus quos, 
            accusantium culpa, praesentium delectus architecto quaerat animi magni explicabo debitis, 
            velit quod libero. Quas nesciunt ut repellendus praesentium.</p>
    </article> -->
</template>
</html>

<?php require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "toaster_template.html") ?>
