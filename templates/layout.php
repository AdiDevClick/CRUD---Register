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

// if ($url === 'about.php' || 'planningType.php' || 'todo.html' || 'carousel.html' || 'contact.php' || 'index.php') {
//     $active = strip_tags('class="active"');
// }
$active = strip_tags('class="active"');
$list_Items = [
    'index.php' => 'Accueil',
    'about.php' => 'About',
    'planningType.php' => 'Planning',
    'todo.html' => 'Ma ToDo list',
    'carousel.html' => 'Carousel Exemple',
    'contac.php' => 'Contact'
];
$dev = true;
// $dev = false;
$vite = false;
// $vite = true;

?>

<!DOCTYPE html>
<html id="html" lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta content="text/javascript; charset=UTF-8" http-equiv="content-type"> -->
    <!-- <meta cache-control="private"> -->
    <!-- <meta http-equiv="Cache-Control" content="private, must-revalidate"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $rootUrl . $clicServer?>/css/resetfirefox.css">
    <!-- <link rel="stylesheet" href="<?php //echo($rootUrl). $clicServer?>/css/resetchromium.css"> -->
    <link rel="stylesheet" href="<?= $rootUrl . $clicServer?>/css/reset.css">
    <!-- <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous"
    referrerpolicy="no-referrer"/>
    <!-- <script type="module" src="<?php //echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script> -->
    <script src="<?= $rootUrl . $clicServer?>/scripts/script.js" type="module" defer></script>
    <script src="<?= $rootUrl . $clicServer?>/scripts/searchApp.js" type="module" defer></script>

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
        <script src="<?= $rootUrl . $clicServer?>/public/assets/<?= $manifest['resources/main.js']['file']?>" type="module"></script>
        <!-- <script src="./public/assets/<?php //$manifest['resources/main.js']['file']?>" type="module"></script> -->
        <script src="<?= $rootUrl . $clicServer?>/public/assets/<?= $manifest['scripts/toaster.js']['file']?>" type="module"></script>
        <!-- <link rel="stylesheet" href="./public/assets/<?php //$manifest['resources/main.js']['css'][0]?>"> -->
        <link rel="stylesheet" href="<?= $rootUrl . $clicServer?>/public/assets/<?= $manifest['resources/main.js']['css'][0]?>">
<?php
} elseif ($dev && $vite) {
    ?>
    <script type="module" src="http://localhost:5173/assets/@vite/client"></script>
    <script type="module" src="http://localhost:5173/assets/resources/main.js"></script>
    <script type="module" src="<?= $rootUrl . $clicServer?>/scripts/toaster.js" defer></script>
    <script src="<?= $rootUrl . $clicServer?>/scripts/toggleLightMode.js" defer></script>
<?php
}
?>

<?php if (!$vite) : ?>
        <link rel="stylesheet" href="<?= $rootUrl . $clicServer?>/resources/css/main.css"/>
        <link rel="stylesheet" href="<?= $rootUrl . $clicServer?>/resources/css/index.css"/>
        <script type="module" src="<?= $rootUrl . $clicServer?>/scripts/toaster.js" defer></script>
        <script src="<?= $rootUrl . $clicServer?>/scripts/toggleLightMode.js" defer></script>
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
    <!-- <div class="dropdown-menu-background"></div> -->

    <header class="header-main">
    <div class="dropdown-menu-background"></div>

        <!-- <nav class="nav"> -->
        <nav class="navbar-grid">
            <!-- <div class="dropdown-menu-background"> -->
                <div class="dropdown-menu">
                    <?php
                        foreach ($list_Items as $key => $value) {
                            echo $key . $value;
                            echo "<li><a"
                                . $url === $key ? $active : null .
                                "href=". $rootUrl . $clicServer . '/' . $key . ">" . $value ."
                                </a></li>";
                        }
                    ?>
                    <li><a
                    <?php
                        if ($url === 'index.php') {
                            echo $active;
                        } else {
                            null;
                        }
?>
                    href="<?= $rootUrl . $clicServer.'/index.php' ?>">Accueil</a></li>
                    <li><a
                    <?php
    if ($url === 'about.php') {
        echo $active;
    } else {
        null;
    }
?>
                        href="#">About</a></li>
                        <li><a
                        <?php
    if ($url === 'planningType.php') {
        echo $active;
    } else {
        null;
    }
?>
                        href="<?= $rootUrl . $clicServer.'/planning/planningType.php' ?>">Planning</a></li>
                        <li><a
                        <?php
    if ($url === 'todo.html') {
        echo $active;
    } else {
        null;
    }
?>
    href="<?= $rootUrl . $clicServer.'/todo.html' ?>">Ma ToDo list</a></li>
    <li><a
    <?php
        if ($url === 'carousel.html') {
            echo $active;
        } else {
            null;
        }
?>
                        href="<?= $rootUrl . $clicServer.'/carousel.html' ?>">Carousel Exemple</a></li>
                        <li><a
                        <?php
if ($url === 'contact.php') {
    echo $active;
} else {
    null;
}
?>
                        href="<?= $rootUrl . $clicServer.'/contact.php' ?>">Contact</a></li>
                    <?php //$setLoggedStatus?>
                    <?php //if(!isset($_SESSION['LOGGED_USER'])):?>
                    <?php if(!isset($_COOKIE['EMAIL'])): ?>
                    <?php //if(!isset($loggedUser['email'][0])):?>
                        <li><a class="" href="<?= $rootUrl . $clicServer.'/index.php#username' ?>">Se connecter</a></li>
                        <li><a class="action_btn" href="<?= $rootUrl . $clicServer.'/register.php' ?>">S'enregistrer</a></li>
                    <?php endif?>
                    <?php if(isset($_COOKIE['EMAIL'])): ?>
                    <?php //if(isset($_SESSION['LOGGED_USER'])):?>
                    <?php //if(isset($loggedUser['email'][0])):?>
                        <li><a <?php if ($url === 'create_recipes.php') {
                            echo $active;
                        }?> class="" href="<?= $rootUrl . $clicServer.'/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                        <li><a class="" href="<?= $rootUrl . $clicServer.'/deconnexion.php' ?>">Se déconnecter</a></li>
                    <?php endif?>
                </div>
            <!-- </div> -->
            <div class="logo">
                <img src="<?= $rootUrl. $clicServer ?>/img/logoicon.svg" class="form-logo"></img>
                <a class="img-link" href="<?php echo strip_tags($rootUrl). $clicServer.'/index.php'?>"></a>
            </div>
            <div class="navbar">
                <!-- default -->
                 <!-- <div class="logo form-logo">
                    <img src="img/logoicon.svg" alt="Logo du site web">
                    <a href="<?php // echo strip_tags($rootUrl). $clicServer.'/index.php'?>"></a>
                </div> -->
                <!-- <div class="logo"><a href="<?php // echo strip_tags($rootUrl). $clicServer.'/index.php'?>">AdiDevClick</a></div> -->
                <!-- end of default -->
                <!-- <div class="nav"> -->

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
                        echo $active;
                        // echo strip_tags('class="active"');
                    } else {
                        null;
                    }?>
                    href="<?= $rootUrl . $clicServer.'/index.php' ?>">Accueil</a></li>
                    
                    <li><a
                    <?php if ($url === 'planningType.php') {
                        // echo strip_tags('class="active"');
                        echo $active;
                    } else {
                        null;
                    } ?>
                    href="<?= $rootUrl . $clicServer.'/planning/planningType.php' ?>">Planning</a></li>
                    <li><a
                    <?php if ($url === 'carousel.html') {
                        // echo strip_tags('class="active"');
                        echo $active;
                    } else {
                        null;
                    } ?>
                    href="<?= $rootUrl . $clicServer.'/carousel.html' ?>">Carousel Exemple</a></li>
                    <li><a
                    <?php if ($url === 'todo.html') {
                        // echo strip_tags('class="active"');
                        echo $active;
                    } else {
                        null;
                    } ?>
                    href="<?php echo($rootUrl). $clicServer.'/todo.html' ?>">Ma ToDo list</a></li>
                    
                    <?php //$setLoggedStatus?>
                    <?php if(!isset($_COOKIE['EMAIL'])): ?>
                        <li><a class="" href="<?= $rootUrl . $clicServer.'/index.php#username' ?>">Se connecter</a></li>
                        <!-- <li><a class="" href="<?php //echo($rootUrl). 'recettes/register.php'?>">S'enregistrer</a></li> -->
                    <?php endif?>
                    <?php if(isset($_COOKIE['EMAIL'])): ?>
                    <?php //if(isset($_SESSION['LOGGED_USER'])):?>
                    <?php //if(isset($loggedUser['email'][0])):?>
                        <li><a <?php if ($url === 'create_recipes.php') {
                            // echo strip_tags('class="active"');
                            echo $active;
                        }?> class="" href="<?= $rootUrl . $clicServer.'/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                        <li><a class="" href="<?= $rootUrl . $clicServer.'/deconnexion.php' ?>">Se déconnecter</a></li>
                    <?php endif?>
                    
                </ul>

                <?php if(!isset($_COOKIE['EMAIL'])): ?>
                <?php //if(!isset($_SESSION['LOGGED_USER'])):?>
                <?php //if(!isset($loggedUser['email'][0])):?>
                    <a href="<?php echo($rootUrl). $clicServer.'/register.php' ?>" class="action-btn">S'enregistrer</a>
                <?php endif?>

                <?php if(isset($_COOKIE['EMAIL'])): ?>
                    
                    <section class="account" id="account">
                        <div class="icon-search-input">
                            <img src="<?= $rootUrl. $clicServer ?>/img/logoicon.svg" class=""></img>
                            <a class="img-link" href="<?php echo strip_tags($rootUrl). $clicServer.'/account.php'?>"></a>
                        </div>
                        <ul class="sub-menu">
                            <li><a <?php if ($url === 'create_recipes.php') {
                                // echo strip_tags('class="active"');
                                echo $active;
                            }?> class="" href="<?= $rootUrl. $clicServer.'/recipes/create_recipes.php' ?>">Créer une recette</a></li>
                            <li><a class="" href="<?= $rootUrl . $clicServer.'/deconnexion.php' ?>">Se déconnecter</a></li>
                        </ul>
                    </section>

                <?php endif?>

                <!-- <section id="burger"> -->
                    <div class="toggle_btn-box"> 
                        <div class="toggle_btn">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <i></i>
                            <i></i> 
                            <i></i>
                        </div>
                    </div>
                <!-- </section> -->
                <!-- </div> -->

            <!-- </div> -->
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
            data-elements='{
            "title": ".js-title",
            "author": ".js-author",
            "img_path": ".js-img",
            "href": ".js-href",
            "youtubeID": ".js-youtube-player"
            }'
            class="align-center hidden js-infinite-pagination">
            <div class="loader" role="status"></div>
        </div>
    <!-- End Main Content -->
    </main>

    <!-- footer.php -->
    <footer class="">
        <div class="">
            <p>© 2023 Copyright <a class="" href="https://github.com/AdiDevClick/">Adi Dev Click</a></p>
            <li><a
                <?php if ($url === 'contact.php') {
                    // echo strip_tags('class="active"');
                    echo $active;
                } else {
                    null;
                } ?>
                href="<?= $rootUrl . $clicServer.'/contact.php' ?>">Contact
            </a></li>
            <li><a
                <?php if ($url === 'about.php') {
                    // echo strip_tags('class="active"');
                    echo $active;
                } else {
                    null;
                } ?>
                href="#">About
            </a></li>
            <div class="toggle">
                <input type="checkbox" id="lightmode-toggle">
                <label for="lightmode-toggle">
                    <svg style="fill:currentColor;" class="sun" version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                        width="800px" height="800px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                    <g>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M32,14.002c-9.941,0-18,8.059-18,18s8.059,18,18,18
                            s18-8.059,18-18S41.941,14.002,32,14.002z M32,48.002c-8.837,0-16-7.164-16-16s7.163-16,16-16s16,7.164,16,16
                            S40.837,48.002,32,48.002z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M32,20.002c-0.553,0-1,0.447-1,1s0.447,1,1,1
                            c5.522,0,10,4.477,10,10c0,0.553,0.447,1,1,1s1-0.447,1-1C44,25.375,38.627,20.002,32,20.002z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M63,31H53c-0.553,0-1,0.447-1,1s0.447,1,1,1h10
                            c0.553,0,1-0.447,1-1S63.553,31,63,31z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M11.457,36.47l-3.863,1.035c-0.534,0.144-0.851,0.692-0.707,1.226
                            c0.143,0.533,0.69,0.85,1.225,0.706l3.863-1.035c0.533-0.143,0.85-0.69,0.707-1.225C12.539,36.644,11.99,36.327,11.457,36.47z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M49.32,22c0.277,0.479,0.888,0.643,1.367,0.366l8.66-5
                            c0.479-0.276,0.643-0.888,0.365-1.366c-0.275-0.479-0.887-0.642-1.365-0.365l-8.66,5C49.208,20.912,49.045,21.521,49.32,22z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M17.858,46.143c-0.39-0.391-1.023-0.389-1.414,0l-2.828,2.828
                            c-0.391,0.391-0.39,1.025,0.001,1.415c0.39,0.391,1.022,0.39,1.413-0.001l2.828-2.828C18.249,47.168,18.249,46.534,17.858,46.143z"
                            />
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M42,14.68c0.479,0.276,1.09,0.113,1.367-0.366l5-8.66
                            C48.644,5.175,48.48,4.563,48,4.287c-0.478-0.276-1.088-0.112-1.365,0.366l-4.999,8.661C41.358,13.793,41.522,14.403,42,14.68z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M26.824,51.318c-0.532-0.143-1.08,0.176-1.225,0.707l-1.035,3.863
                            c-0.143,0.535,0.176,1.083,0.709,1.226c0.533,0.144,1.08-0.173,1.223-0.708l1.035-3.863C27.676,52.012,27.359,51.463,26.824,51.318
                            z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M32,12c0.554,0,1.001-0.446,1.002-1V1c0-0.553-0.447-1-1.002-1
                            c-0.551,0-0.998,0.447-0.999,1l0.001,10C31.002,11.553,31.449,12,32,12z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M38.402,52.025c-0.141-0.532-0.689-0.85-1.225-0.707
                            c-0.533,0.143-0.848,0.692-0.707,1.225l1.035,3.863c0.144,0.535,0.693,0.85,1.227,0.707s0.849-0.689,0.705-1.225L38.402,52.025z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M20.637,14.312c0.275,0.479,0.887,0.643,1.363,0.367
                            c0.48-0.277,0.645-0.887,0.368-1.367l-5-8.66C17.092,4.174,16.48,4.01,16,4.287c-0.477,0.275-0.641,0.887-0.365,1.365
                            L20.637,14.312z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M47.558,46.142c-0.388-0.39-1.022-0.39-1.414,0
                            c-0.391,0.39-0.388,1.024,0,1.414l2.828,2.828c0.392,0.392,1.025,0.389,1.415-0.001c0.391-0.39,0.391-1.021-0.001-1.413
                            L47.558,46.142z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M4.654,17.365l8.662,4.999c0.477,0.276,1.088,0.113,1.363-0.364
                            c0.277-0.479,0.115-1.09-0.364-1.367l-8.661-5C5.176,15.356,4.564,15.52,4.287,16C4.013,16.477,4.176,17.089,4.654,17.365z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M52.027,38.4l3.863,1.035c0.535,0.145,1.082-0.176,1.225-0.709
                            c0.144-0.532-0.172-1.079-0.707-1.223l-3.863-1.035c-0.531-0.145-1.081,0.173-1.225,0.707C51.176,37.709,51.496,38.256,52.027,38.4
                            z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M12,32c0.001-0.554-0.445-1-0.998-1.002L1,31
                            c-0.552,0-1,0.445-1,1c0.001,0.551,0.448,1,1.001,1l10.001-0.002C11.553,32.998,12.001,32.552,12,32z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M52.545,27.529l3.863-1.035c0.535-0.143,0.85-0.693,0.706-1.227
                            c-0.142-0.531-0.688-0.848-1.224-0.705l-3.863,1.035c-0.533,0.141-0.85,0.691-0.707,1.225
                            C51.461,27.356,52.012,27.67,52.545,27.529z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M14.68,42c-0.275-0.48-0.886-0.644-1.365-0.368l-8.661,5.002
                            C4.176,46.91,4.01,47.52,4.287,48c0.277,0.477,0.889,0.641,1.367,0.365l8.66-5.002C14.791,43.088,14.957,42.479,14.68,42z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M46.144,17.856c0.389,0.392,1.022,0.388,1.414,0l2.828-2.828
                            c0.392-0.392,0.39-1.024-0.002-1.415c-0.388-0.39-1.021-0.391-1.412,0.001l-2.828,2.828C45.752,16.83,45.754,17.466,46.144,17.856z
                            "/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M22,49.32c-0.479-0.277-1.088-0.113-1.365,0.364l-5,8.663
                            c-0.275,0.478-0.115,1.088,0.365,1.365c0.479,0.274,1.09,0.11,1.367-0.367l4.998-8.662C22.641,50.207,22.48,49.597,22,49.32z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M37.178,12.68c0.531,0.145,1.078-0.176,1.225-0.707l1.035-3.863
                            c0.143-0.535-0.176-1.083-0.709-1.225c-0.531-0.144-1.08,0.172-1.223,0.707l-1.035,3.863C36.324,11.986,36.645,12.536,37.178,12.68
                            z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M32,52c-0.553-0.002-0.998,0.446-1,0.998l0.002,10.004
                            C31.002,63.552,31.445,64,32,64c0.553,0,1-0.449,1.001-1l-0.003-10.002C32.998,52.447,32.555,52,32,52z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M25.6,11.973c0.139,0.533,0.691,0.85,1.225,0.707
                            c0.532-0.141,0.846-0.691,0.707-1.225l-1.035-3.863c-0.145-0.535-0.693-0.851-1.227-0.706c-0.531,0.142-0.85,0.688-0.705,1.224
                            L25.6,11.973z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M43.363,49.687c-0.275-0.478-0.883-0.644-1.363-0.365
                            c-0.479,0.274-0.641,0.885-0.367,1.364l5.004,8.661c0.275,0.478,0.883,0.644,1.363,0.366c0.479-0.277,0.642-0.889,0.367-1.367
                            L43.363,49.687z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M16.443,17.856c0.387,0.394,1.023,0.39,1.414,0
                            c0.391-0.388,0.387-1.021,0-1.414l-2.828-2.828c-0.393-0.392-1.025-0.39-1.415,0.002c-0.39,0.388-0.392,1.021,0.001,1.412
                            L16.443,17.856z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M59.348,46.633l-8.663-4.997
                            c-0.478-0.276-1.087-0.116-1.363,0.366c-0.278,0.477-0.112,1.086,0.364,1.364l8.664,4.999c0.477,0.275,1.086,0.115,1.363-0.365
                            C59.988,47.521,59.824,46.91,59.348,46.633z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#231F20" d="M11.974,25.599L8.11,24.563c-0.536-0.144-1.083,0.175-1.225,0.708
                            c-0.144,0.531,0.171,1.08,0.707,1.225l3.863,1.034c0.531,0.146,1.081-0.175,1.225-0.707C12.825,26.293,12.505,25.746,11.974,25.599
                            z"/>
                    </g>
                    </svg>
                    <svg class="moon" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0174 2.80157C6.37072 3.29221 2.75 7.22328 2.75 12C2.75 17.1086 6.89137 21.25 12 21.25C16.7767 21.25 20.7078 17.6293 21.1984 12.9826C19.8717 14.6669 17.8126 15.75 15.5 15.75C11.4959 15.75 8.25 12.5041 8.25 8.5C8.25 6.18738 9.33315 4.1283 11.0174 2.80157ZM1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C12.7166 1.25 13.0754 1.82126 13.1368 2.27627C13.196 2.71398 13.0342 3.27065 12.531 3.57467C10.8627 4.5828 9.75 6.41182 9.75 8.5C9.75 11.6756 12.3244 14.25 15.5 14.25C17.5882 14.25 19.4172 13.1373 20.4253 11.469C20.7293 10.9658 21.286 10.804 21.7237 10.8632C22.1787 10.9246 22.75 11.2834 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12Z" fill="#1C274C"/>
                    </svg>
                </label>
            </div>
            
        </div>
    </footer>
    <!-- end of footer -->
</body>
<!-- <template id="search-template"> -->
    <!-- <article class="title">Carousel 1 -->
        <!-- <div id="carousel1"> -->
            <!-- <article class="item_container"> -->
                <!-- <div class="item__image">
                    <img class="js-img"  src="" alt="">
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
            </article> -->
        <!-- </div> -->
    <!-- </article> -->
<!-- </template> -->
</html>

<?php require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "toaster_template.html") ?>
<?php require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "search_result_template.html") ?>
