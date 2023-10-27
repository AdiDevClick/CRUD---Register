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

} catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête le script
    die('Erreur'. $e->getMessage());
}


/* $sqlQuery = 'SELECT * FROM `recipes`';
$recipesStatement = $db->prepare($sqlQuery);
$recipesStatement->execute();
$recipes = $recipesStatement->fetchAll();

// Ecriture de la requête
/* $sqlQuery = 'INSERT INTO recipes(title, recipe, author, is_enabled) VALUES (:title, :recipe, :author, :is_enabled)';

// Préparation
$insertRecipe = $db->prepare($sqlQuery); */

// Exécution ! La recette est maintenant en base de données
/* $insertRecipe->execute([
    'title' => 'ma recette',
    'recipe' => 'Etape 1 : Des flageolets ! Etape 2 : Euh ...',
    'author' => 'test@exemple.com',
    'is_enabled' => 1, // 1 = true, 0 = false
]); */


foreach ($recipes as $recipe) {
    ?>
        <p><?php echo $recipe['author']; ?></p>
    <?php
    }