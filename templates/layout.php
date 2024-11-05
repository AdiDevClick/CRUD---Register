<?php declare(strict_types=1);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR ."Functions.class.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."variables.inc.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."functions.inc.php";

header('Cache-Control: private, must-revalidate');

// $dev = true;
$dev = false;
$vite = false;
// $vite = true;

// Menu items for unregistered users
$unregisteredItems = [
    'index.php#username' => [
        'class' => 'action_btn',
        'value' => 'Se connecter'
    ],
];
// Menu items for registered users
$registeredItems = [
    'recipes/create_recipes.php' => [
        'value' => 'Créer une recette',
        'page' => 'create_recipes.php'
    ],
    'deconnexion.php' => [
        'value' => 'Se déconnecter'
    ]
];

?>

<!DOCTYPE html>
<html id="html" lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta content="text/javascript; charset=UTF-8" http-equiv="content-type"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= strip_tags($rootUrl) . $clicServer?>/css/resetfirefox.css">
    <!-- <link rel="stylesheet" href="<?php //echo($rootUrl). $clicServer?>/css/resetchromium.css"> -->
    <link rel="stylesheet" href="<?= strip_tags($rootUrl) . $clicServer?>/css/reset.css">
    <!-- <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous"
    referrerpolicy="no-referrer"/>
    <!-- <script type="module" src="<?php //echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script> -->
    <script src="<?= strip_tags($rootUrl) . $clicServer?>/scripts/script.js" type="module" defer></script>
    <script src="<?= strip_tags($rootUrl) . $clicServer?>/scripts/searchApp.js" type="module" defer></script>

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
        <script src="<?= strip_tags($rootUrl) . $clicServer?>/public/assets/<?= $manifest['resources/main.js']['file']?>" type="module"></script>
        <!-- <script src="./public/assets/<?php //$manifest['resources/main.js']['file']?>" type="module"></script> -->
        <script src="<?= strip_tags($rootUrl) . $clicServer?>/public/assets/<?= $manifest['scripts/toaster.js']['file']?>" type="module"></script>
        <!-- <link rel="stylesheet" href="./public/assets/<?php //$manifest['resources/main.js']['css'][0]?>"> -->
        <link rel="stylesheet" href="<?= strip_tags($rootUrl) . $clicServer?>/public/assets/<?= $manifest['resources/main.js']['css'][0]?>">
<?php
} elseif ($dev && $vite) {
    ?>
    <script type="module" src="http://localhost:5173/assets/@vite/client"></script>
    <script type="module" src="http://localhost:5173/assets/resources/main.js"></script>
    <script type="module" src="<?= strip_tags($rootUrl) . $clicServer?>/scripts/toaster.js" defer></script>
    <script src="<?= strip_tags($rootUrl) . $clicServer?>/scripts/toggleLightMode.js" defer></script>
<?php
}
?>

<?php if (!$vite) : ?>
        <link rel="stylesheet" href="<?= strip_tags($rootUrl) . $clicServer?>/resources/css/main.css"/>
        <link rel="stylesheet" href="<?= strip_tags($rootUrl) . $clicServer?>/resources/css/index.css"/>
        <script type="module" src="<?= strip_tags($rootUrl) . $clicServer?>/scripts/toaster.js" defer></script>
        <script src="<?= strip_tags($rootUrl) . $clicServer?>/scripts/toggleLightMode.js" defer></script>
<?php endif ?>

    <!-- <script type="module" src="<?php //echo($rootUrl). $clicServer?>/scripts/toaster.js" defer></script> -->
    <script <?= $script ?? '' ?>></script>
    <script <?= $script2 ?? '' ?>></script>
    <title>
        <?= 'We Love Food - ' . strip_tags($title) ?? 'We Love Food' ?>
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
                        if(!isset($_COOKIE['EMAIL'])) {
                            // $unregisteredItems['register.php'] = ['value' => 'S\'enregistrer'];
                            // $unregisteredItems['contact.php'] = ['value' => 'Contact'];
                            echo createMenuItems($url, $unregisteredItems);
                        } else {
                            echo createMenuItems($url, $registeredItems, 'mobile registered');
                        }
                    ?>
                </div>
            <!-- </div> -->
            <div class="logo">
                <img src="<?= strip_tags($rootUrl) . $clicServer ?>/img/logoicon.svg" class="form-logo"></img>
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
                    action="<?= strip_tags($rootUrl) . $clicServer.'/index.php' ?>"
                    method="get"
                    role="search"
                    data-endpoint ="<?= strip_tags($rootUrl) . $clicServer . '/recipes/Process_PreparationList.php'?>"
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
                    <?php
    if (!isset($_COOKIE['EMAIL'])) {
        echo createMenuItems($url, $unregisteredItems, 'desktop');
        // echo createMenuItems($url, $unregisteredItems, 'mobile');
    } else {
        echo createMenuItems($url, $registeredItems, 'desktop');
        // echo createMenuItems($url, $registeredItems, 'mobile');
    }
?>
                </ul>

                <?php
if(!isset($_COOKIE['EMAIL'])) {
    echo '<a href="' . strip_tags($rootUrl) . $clicServer .'/register.php" class="action-btn">S\'enregistrer</a>';
} else {
    $menuItems = createMenuItems($url, $registeredItems, 'submenu');

    $accountSection = '';
    $accountSection = '<section class="account" id="account">';
    $accountSection .=  '<div class="icon-search-input">';
    $accountSection .=      '<img src="' . strip_tags($rootUrl) . $clicServer . '/img/logoicon.svg" class="" />';
    $accountSection .=      '<a class="img-link" href="' . strip_tags($rootUrl) . $clicServer . '/account.php"></a>';
    $accountSection .=  '</div>';
    $accountSection .=  '<ul class="sub-menu">';
    $accountSection .=       $menuItems;
    $accountSection .=  '</ul>';
    $accountSection .= '</section>';
    echo $accountSection;
}
?>

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
            <?php
                echo createMenuItems($url, null, 'footer');
                require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "light_dark_theme_button.html"
            ?>
        </div>
    </footer>
    <!-- end of footer -->
</body>
</html>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "toaster_template.html" ?>
<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "search_result_template.html" ?>
