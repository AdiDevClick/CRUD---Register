<?php


/***
 *
 */

/* function display_mailReception($_GET)
{
    $mail_content = '';

    if (isset($_GET['email']) && isset($_GET['message'])) {
        $mail_content = '<article>';
        $mail_content .= '<h3> Nous avons bien reçu votre message </h3>';
        $mail_content .= '<h5> Rappel de vos informations : </>';
        $mail_content .= '<p>' . '<span>Email</span> : ' . $_GET['email'] . '</p>';
        $mail_content .= '<p>' . '<span>Message</span> : ' . $_GET['message'] . '</p>';
        $mail_content .= '</article>';
    }
    return $mail_content;
}
 */

/* function displayAuthor($authorEmail, $users)
{
    for ($i = 0;
        $i < count($users);
        $i++) {
        $author = $users[$i];
        if ($authorEmail === $author["email"]) {
            return $author["full_name"] . '(' . $author["age"] . 'ans)';
        } else {
            return $authorEmail . ' Annonyme';
        }
    }
    return $users;
} */

/**
 * Crer les items du menu.
 * - Retourne un <li> element avec un <a> à l'intérieur.
 * - Le lien aura un href avec l'url du serveur.
 * @param string $page - Le nom de la page à afficher avec son extension.
 * - ex : index.php
 * @param array $items - Un array multidimensionnel.
 * - ex : ['planning/planningType.php' => [
 *  'value' => 'Planning',
 *  'page' => 'planningType.php'
 * ],
 * @param string $menuType - Default = 'mobile'.
 * - optionnal = 'desktop', 'footer', 'submenu'
 * @return string
 */
function createMenuItems(string $page, array $items = null, string $menuType = 'mobile'): string
{
    include dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."variables.inc.php";

    $menuItems = '';
    $active = strip_tags("active");
    $list_Items = [
        'index.php' => ['value' => 'Accueil'],
        'planning/planningType.php' => [
            'value' => 'Planning',
            'page' => 'planningType.php'
        ],
        'todo.html' => ['value' => 'Ma ToDo list'],
        'carousel.html' => ['value' => 'Carousel Exemple']
    ];

    if ($menuType === 'mobile') {
        $list_Items['contact.php'] = ['value' => 'Contact'];
        $list_Items['register.php'] = ['value' => 'S\'enregistrer'];
    }

    if ($menuType === 'footer') {
        $list_Items = [
            'contact.php' => ['value' => 'Contact'],
            '#' => ['value' => 'About']
        ];
    }

    if ($menuType === 'submenu') {
        $list_Items = [];
    }

    if ($items) {
        $list_Items = array_merge($list_Items, $items);
    }

    foreach ($list_Items as $key => $value) {
        $class = ((isset($value['page']) && $page === $value['page']) || $page === $key ? $active . ' ' : '') . ($value['class'] ?? '');
        $menuItems .= '<li><a class="' . $class . '" href=" '. $rootUrl . $clicServer . '/' . $key . '">' . $value['value'] . '</a></li>';
    }
    return $menuItems;
}

/**
 * Permet de créer un dossier en prenant en compte le path en param
 * @param mixed $path
 * @return bool
 */
function makeDir($path)
{
    return is_dir($path) || mkdir($path, 0777, true);
}
function displayAuthor(string $authorEmail)
{
    //require_once("includes/class-autoloader.inc.php");
    $users = new LoginView('');
    // $users = new LoginView('', '', '', '');
    foreach ($users->displayUsers($authorEmail) as $user) {
        if ($authorEmail === $user['email']) {
            return $user["full_name"];
            // return $user["full_name"] . '(' . $user["age"] . 'ans)';
        } else {
            return $authorEmail . ' Annonyme';
        }
    }
}

function display_recipe(array $recipe): string
{
    $recipe_content = '';
    try {
        if (array_key_exists('is_enabled', $recipe) && $recipe['is_enabled']) {
            $recipe_content = '<article>';
            $recipe_content .= '<h3>' . $recipe['title'] . '</h3>';
            $recipe_content .= '<div>' . $recipe['recipe'] . '</div>';
            $recipe_content .= '<i>' . $recipe['author'] . '</i>';
            $recipe_content .= '</article>';
        }
        return $recipe_content;
    } catch (Exception $e) {
        return "Y'a erreur". $e->getMessage() .'...PHP_EOL' ;

    }
}

function isRecipeEnabled(array $recipes): bool
{

    if (array_key_exists('is_enabled', $recipes)) { //Si la clé existe dans recipes
        $isEnabled = $recipes['is_enabled']; // et qu'elle est enabled
    } else {
        $isEnabled = false;
    }
    return $isEnabled; // on retourne enabled
}

function getRecipes(array $recipes): array
{
    $validrecipes = [];

    foreach ($recipes as $recipe) {
        if (isRecipeEnabled($recipe)) {
            $validrecipes[] = $recipe;
        }
    }
    return $validrecipes;
}

function display_erreurMessageContact()
{
    $email = $_POST['email'];
    $message = $_POST['message'];
    $erreurMessage = 'Il faut un email et un message pour soumettre le formulaire.';
    $isDisplayed = false;

    if (!isset($_POST['submit']) || !isset($email) ||
        !isset($message) || empty($message) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo $erreurMessage;
        $isDisplayed = true;
        // Arrête l'exécution de PHP
        return  $isDisplayed;
    }
}





function retrieve_id_from_user_mail(string $userEmail, array $users): int
{
    for ($i = 0; $i < count($users); $i++) {
        $user = $users[$i];
        if ($userEmail === $user['email']) {
            return $user['user_id'];
        }
    }

    return 0;
}

/* function display_erreurLogin()
{
    $password = $_POST['password'];
    $username = $_POST['username'];
    $erreurMessage = 'Il faut un username et un password pour soumettre le formulaire.';
    $isDisplayed = false;

    if (isset($_POST['login']) || !isset($username) ||
        !isset($password) || empty($username) ||
        empty($password))
        {
            echo $erreurMessage;
            $isDisplayed = true;
            // Arrête l'exécution de PHP
            return  $isDisplayed;
        } elseif (!isset($_POST['login'])) {
            echo '';
        }
            echo "Vous êtes connecté !";
        }

        if (!isset($_POST['login']))
        {

        }  */


/*  function get_recipes(array $recipes, int $limit) : array
 {
     $valid_recipes = [];
     $counter = 0;

     foreach($recipes as $recipe) {
         if ($counter == $limit) {
             return $valid_recipes;
         }

         if ($recipe['is_enabled']) {
             $valid_recipes[] = $recipe;
             $counter++;
         }
     }

     return $valid_recipes;
 }
 */



/* function display_user(int $userId, array $users): string
{
    foreach ($users as $key => $value) {
        if ($value['user_id'] === $userId) {
            return $value['full_name'] . '(' . $value['age'] . ' ans)';
        }
    }
    return $value;
} */

function display_user(int $userId, array $users): string
{
    for ($i = 0;
        $i < count($users);
        $i++) {
        $user = $users[$i];
        if ($userId === $user["user_id"]) {
            return $user['full_name'] . '(' . $user['age'] . ' ans)';
        } else {
            return $user . ' Annonyme';
        }
    }
}
