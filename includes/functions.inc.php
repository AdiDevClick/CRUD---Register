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

 
function display_recipe(array $recipe) : string
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
        !filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            echo $erreurMessage;
            $isDisplayed = true;
            // Arrête l'exécution de PHP
        return  $isDisplayed;
    }
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


function displayAuthor(string $authorEmail, array $users) : string
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
} 

?>