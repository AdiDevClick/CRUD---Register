<?php

const MYSQL_HOST = "localhost";
const MYSQL_PORT = "3306";
const MYSQL_USER = "root";
const MYSQL_PASS = "";
const MYSQL_DB = "we_love_food";

try {
    // Souvent on identifie cet objet par la variable $conn ou $db
    $db = new PDO(
        sprintf('mysql:host=%s;dbname=%s;post=%s', MYSQL_HOST, MYSQL_DB, MYSQL_PORT),
        MYSQL_USER,
        MYSQL_PASS
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Error $e) {
    // En cas d'erreur, on affiche un message et on arrête le script
    die('Erreur : '. $e->getMessage());
}


// Si tout va bien, on peut continuer

// On récupère tout le contenu de la table recipes
/* $sqlQuery = 'SELECT * FROM `recipes`';
$recipesStatement = $db->prepare($sqlQuery);
$recipesStatement->execute();
$recipes = $recipesStatement->fetchAll();


$sqlQuery3 ='INSERT INTO recipes(title, recipe, author, is_enabled) 
        VALUES (:title, :recipe, :author, :is_enabled)';
$sqlQuery2 = 'INSERT INTO `recipes`(`title`, `recipe`, `author`, `is_enabled`) 
        VALUES (:title, :recipe, :author, :is_enabled)';

$insertRecipe = $db->prepare($sqlQuery3);

$insertRecipe->execute([
    //'title' => $title,
    'title' => 'ma recette',
    //'recipe' => $recipe,
    'recipe' => 'blabvla',
    //'author' => $loggedUser['email'],
    'author' => 'test@test.com',
    //'is_enable'=> true,
    'is_enabled'=> 1,
]);

    // On affiche chaque recette une à une
foreach ($recipes as $recipe) {
    ?>
        <p><?php echo $recipe['title']; ?></p>
    <?php
    } */