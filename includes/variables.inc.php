<?php

// On récupère tout le contenu de la table recipes
$sqlRecipesQuery = 'SELECT * FROM `recipes`';
$recipesStatement = $db->prepare($sqlRecipesQuery);
$recipesStatement->execute();
$recipes = $recipesStatement->fetchAll();

// On récupère tout le contenu de la table users
$sqlUsersQuery = 'SELECT * FROM `users`';
$usersStatement = $db->prepare($sqlUsersQuery);
$usersStatement->execute();
$users = $usersStatement->fetchAll();

// On affiche chaque recette une à une
/* foreach ($recipes as $recipe) {
?>
    <p><?php echo $recipe['title'] ?> </p>
<?php
} */




if(isset($_GET['limit']) && is_numeric($_GET['limit'])) {
    $limit = (int) $_GET['limit'];
} else {
    $limit = 100;
}


$rootPath = $_SERVER['DOCUMENT_ROOT'];
$rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
