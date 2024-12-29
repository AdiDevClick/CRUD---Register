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
    include dirname(__DIR__) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "variables.inc.php";

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

    if ($menuType === 'mobile registered') {
        $list_Items['contact.php'] = ['value' => 'Contact'];
        // $list_Items['register.php'] = ['value' => 'S\'enregistrer'];
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
        $menuItems .= '<li><a class="' . $class . '" href=" ' . $rootUrl . $clicServer . '/' . $key . '">' . $value['value'] . '</a></li>';
    }
    // return var_dump($list_Items);
    return $menuItems;
}

/**
 * Renvoi une DIV contenant un LABEL, une INPUT et un SELECT avec OPTION (min / heures)
 * @param array $data Un array correspondant à :
 * - La clé : ID de l'input.
 * - La valeur : Le texte du label visible pour l'utilisateur.
 * @param array $getInfos Si existant, l'array d'informations à récupérer du serveur
 * @return string
 */
function createDivWithSelectAndInputs(array $data, array $getInfos = null): string
{
    $divItems = '';
    foreach ($data as $key => $value) {
        $inputValue = $getInfos !== null ? htmlspecialchars((string)$getInfos[$key]) : '';
        $inputLength = strip_tags($key) . '_length';

        $selectedMin = ($getInfos !== null && $getInfos[$inputLength] === 'min') ? 'selected' : '';
        $selectedHours = ($getInfos !== null && $getInfos[$inputLength] === 'heures') ? 'selected' : '';

        $divItems .= '<div class="time">';
        $divItems .= '<label for="' . strip_tags($key) . '" class="label first-column-bottom-border">' . strip_tags($value) . '</label>';
        $divItems .= '<input id="' . strip_tags($key) . '" type="text" name="' . strip_tags($key) . '" class="input" value="' . strip_tags($inputValue) . '">';
        $divItems .= '<select class="select" name="' . strip_tags($inputLength) . '" id="' . strip_tags($inputLength) . '" aria-placeholder="temps">';
        $divItems .= '<option value="min" ' . strip_tags($selectedMin) . '>min</option>';
        $divItems .= '<option value="heures" ' .  strip_tags($selectedHours) . '>heures</option>';
        $divItems .= '</select>';
        $divItems .= '</div>';
    }
    return $divItems;
}

/**
 * Renvoi une DIV contenant un TEXTAREA et son LABEL.
 * En dessous de 6 étapes, le bouton d'ajout d'étapes sera créé.
 * Au dessus, il ne sera pas inclus.
 * Par défaut : 2 étapes de créées.
 * Nombre maximum d'étapes : 6
 * @param array $getInfos Si existant, l'array d'informations à récupérer du serveur
 * @return string
 */
function createDivWithTextArea(array $getInfos = null): string
{
    $insertAddButton = true;
    $divItems = '';
    if (isset($getInfos)) {
        // If we can retrieve data from Database
        foreach ($getInfos as $key => $value) {
            // Insert steps depending on the data from the TABLE
            if (str_starts_with($key, 'step_') && !empty($value)) {
                $textareaValue = htmlspecialchars((string)$getInfos[$key]);
                // Extraire le chiffre de la chaîne $value
                preg_match('/step_(\d+)/', $key, $matches);
                $stepNumber = $matches[1] ?? '';
                $step = '';
                if ($stepNumber == '1') {
                    $step = 'première';
                } elseif ($stepNumber == '2') {
                    $step = 'deuxième';
                } elseif ($stepNumber == '3') {
                    $step = 'troisième';
                } elseif ($stepNumber == '4') {
                    $step = 'quatrième';
                } elseif ($stepNumber == '5') {
                    $step = 'cinquième';
                } elseif ($stepNumber == '6') {
                    $step = 'sixième';
                    $insertAddButton = false;
                }

                $divItems .= '<div class="js-form-recipe">';
                $divItems .= '<label for="' . strip_tags($key) . '" class="label">Etape ' . $stepNumber . '</label>';
                $divItems .= '<textarea id="' . strip_tags($key) . '" cols="60" rows="3" name="' . strip_tags($key) . '" placeholder="Renseignez votre ' . $step . ' étape">' . strip_tags($textareaValue) . '</textarea>';
                $divItems .= '</div>';
            }
        }
        // Under 6 steps, create the add button
        if ($insertAddButton) {
            $divItems .= insertAddButton();
        }
    } else {
        // Default steps
        $step = '';
        for ($i = 1; $i < 3; $i++) {
            if ($i == '1') {
                $step = 'première';
            } elseif ($i == '2') {
                $step = 'deuxième';
            }
            $divItems .= '<div class="js-form-recipe">';
            $divItems .= '<label for="step_' . strip_tags($i) . '" class="label">Etape ' . strip_tags($i) . '</label>';
            $divItems .= '<textarea id="step_' . strip_tags($i) . '" cols="60" rows="3" name="step_' . strip_tags($i) . '" placeholder="Renseignez votre ' . $step . ' étape"></textarea>';
            $divItems .= '</div>';
        }
        $divItems .= insertAddButton();
    }
    return $divItems;
}

/**
 * Retourne un lien contenant un span.
 * Ce lien servira à rajouter des étapes pour le partage de recettes.
 * @return
 */
function insertAddButton()
{
    $link = '';
    $link .= '<a href="#step_2" class="plus three-columns">';
    $link .= '<span></span>';
    $link .= '</a>';
    return $link;
}

/**
 * Permet de créer un dossier en prenant en compte le path en paramètre
 * @param mixed $path Destination du fichier
 * @return bool
 */
function makeDir($path)
{
    return is_dir($path) || mkdir($path, 0777, true);
}
function displayAuthor(string $authorEmail)
{
    //require_once("vendor/class-autoloader.inc.php");
    $users = new LoginView('');
    // $users = new LoginView('', '', '', '');
    $user = $users->displayUsers($authorEmail);
    // var_dump($test['email']);

    // foreach ($users->displayUsers($authorEmail) as $user) {
    // var_dump($user);
    if ($authorEmail === $user['email']) {
        return  ucfirst($user["full_name"]);
        // return $user["full_name"] . '(' . $user["age"] . 'ans)';
    } else {
        return ' Annonyme';
    }
    // }
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
        return "Y'a erreur" . $e->getMessage() . '...PHP_EOL';
    }
}

/**
 * Ajoute des étoiles et remplit le centre en fonction de la review
 * utilisateur
 * @param string $review La note utilisateur sous forme de chaîne de caractères (ex. '4.5')
 * @param int|null $id (Optionnel) L'identifiant unique de l'élément pour lequel les étoiles sont générées
 * @return string Le code HTML généré pour afficher les étoiles
 */
function display_5_stars(string $review, ?int $id = null): string
{
    $decimal = str_split($review, 2);
    $width = '0%';
    $done = false;
    $starHtml = '';

    for ($i = 1; $i <= 5; $i++) {
        if ($review >= $i) {
            $width = '100%';
        } else if ($review < $i && !$done) {
            $width = ($decimal[1] ?? 0) * 10 . '%';
            $done = true;
        } else {
            $width = '0%';
        }

        $itemId = $id ? $id . '-' . $i : (string)$i;

        // Capture le contenu de l'inclusion
        ob_start();
        include '../templates/stars_template.php';
        $starHtml .= ob_get_clean();
    }

    return $starHtml;
}

/**
 * Affiche un template en remplaçant les placeholders avec les données actuelles.
 *
 * This function takes a template string containing placeholders in the format {{key}}
 * and an associative array of data. It replaces each placeholder in the template with
 * the corresponding value from the data array.
 *
 * @param string $template The template string containing placeholders in the format {{key}}.
 * @param array $data An associative array where the keys correspond to placeholder names
 *                    and the values are the values to be inserted into the template.
 * @return string The template with placeholders replaced by actual data.
 */
function renderTemplate($template, $data)
{
    foreach ($data as $key => $value) {
        if (!empty($value)) {
            $template = str_replace("{{" . $key . "}}", $value, $template);
        }
    }
    return $template;
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

    if (
        !isset($_POST['submit']) || !isset($email) ||
        !isset($message) || empty($message) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
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
    for (
        $i = 0;
        $i < count($users);
        $i++
    ) {
        $user = $users[$i];
        if ($userId === $user["user_id"]) {
            return $user['full_name'] . '(' . $user['age'] . ' ans)';
        } else {
            return $user . ' Annonyme';
        }
    }
}

/**
 * Récupère les données d'une table de la base de données en fonction de l'ID de la recette.
 *
 * Cette méthode génère une requête SQL pour obtenir des données à partir de la table spécifiée,
 * utilise une instance de la classe `Database` pour exécuter la requête et retourne les résultats.
 *
 * @param array $params Tableau contenant les paramètres de la requête, y compris les champs et les tables.
 * @param int|string $recipeId L'identifiant de la recette.
 * @param bool $silentMode Mode silencieux pour la récupération des données (par défaut : false).
 * @return array Les données SQL récupérées.
 * @throws Error Si la recette n'existe pas.
 */
// function getFromTable(array $params, int|string $recipeId)
// {
//     // Ajoute un message d'erreur aux paramètres si la recette n'existe pas
//     // $params['error'] = ["Cette recette n'existe pas"];
//     // die(var_dump($params));

//     $options = [
//         "fetchAll" => $params["fetchAll"] ?? false,
//         "searchMode" => $params["searchMode"] ?? false,
//         "silentMode" => $params["silentMode"] ?? false,
//         "silentExecute" => $params["silentExecute"] ?? false
//     ];
//     // Crée une instance de la classe Database avec des données optionnelles
//     $Fetch = new Database($this->optionnalData(), $options);

//     // Génère et exécute la requête SQL pour récupérer les données
//     $SQLData = $Fetch->__createGetQuery($params, $recipeId, $this->connect());

//     // Retourne les données SQL récupérées
//     return $SQLData;
// }
