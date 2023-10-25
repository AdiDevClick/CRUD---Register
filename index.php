<?php
declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once('includes/functions.inc.php');
include_once('logs/customErrorHandlers.php');


/*
$Adi = ['Adi', 'Adi@gmail.com', 'Password 1234', 37];
$Mathieu = ['Mathieu', 'Mathieu@gmail.com', 'Password 1234', 30];
$Jacques = ['Jacques', 'Jacques@gmail.com', 'Password 1234', 17];
$Frip = ['Frip', 'Frip@gmail.com', 'Password 1234', 21];

$recipes = [
    ['Cassoulet','[...]','mickael.andrieu@exemple.com',true,],
    ['Couscous','[...]','mickael.andrieu@exemple.com',false,],
    ['Roti','[...]','mickael.andrieu@exemple.com',true,],
];

foreach ($recipes as $recipe) {
    echo $recipe[0];
}

$recipes = [ $cassoulet => $Adi ];

$recettes = ['Cassoulet', 'Couscous', 'Escalope Milanaise', 'Salade César'];
$recettes[] = 'Test';
$recettes[] = 'Steak';
$isAdult = 18 ;
$lines = 3;
$counter = 0;



if ($users[2][3] >= $isAdult) {
    echo 'Cest ok, t\'es adulte !';
} else {
    echo "C'est pas ok mon pote <br>";
};


while ($counter < $lines) {
    echo $users[$counter][3] .''. $users[$counter][1] .'<br>';
    $counter++;

};

for ($i = 0;
    $i < count($users);
    $i++) {
    echo $users[$i][0] .''. $users[$i][1] .'<br>';
}
$recipe2 =  [

    'title' => 'Cassoulet',
    'recipe' => 'Etape 1 : des flageolets, Etape 2 : ...0',
    'author' => 'mathieu.nebra@exemple.com',
    'is_enabled' => true,
];


*/
//$reciparray = array_keys($recipes);

/* foreach ($recipes as $recipe => $value) {
    //echo $recipe . '=>'. $value . '<br>';
    echo $value['title'] . ' contribué par ' . $value['author'] . ' en ligne : ' . $value['enable'] . '<br>';
} */

/* if (array_key_exists('tro', $value)) {
    echo 'La clé "trop" existe dans la recette <br>';
} else {
    echo 'La clé "tro" existe pas <br>';
}
if (array_key_exists('title', $value)) {
    echo 'La clé "title" existe dans la recette <br>';
} else {
    echo 'La clé "title" existe pas <br>';
}

if (in_array('adi', $users[0])) {
    echo 'Adi fait bien parti de la liste <br>';
} else {
    echo "Adi n'existe pas <br>";
}

$positionAdi = array_search('Adi', $users[2]);
echo 'Adi fait bien parti de la liste' . $positionAdi . '<br>';

echo '<pre>';
print_r($recipes);
echo '</pre>'; */



/* ($i = 0;
     $i < count($recipes);
     $i++) {
    if (isRecipeEnabled($recipes)) {
        echo $recipes . 'ok';

    } else {
        echo $recipes . 'pas ok';
    }
} */

$day = date("d");
$month = date("m");
$year = date("Y");
$hour = date("H");
$minut = date("i");
$seconds = date("s");
echo 'Bonjour ! Nous sommes le ' . $day . '/' . $month . '/' . $year . 'et il est ' . $hour. ' h ' . $minut .  ' et ' .  $seconds . ' secondes';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage de recettes</title>
<!--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 -->    <!-- <link rel="stylesheet" href="css/reset.css"> -->
</head>
<body>
    <!-- Le Header -->

    <?php include_once('includes/header.inc.php')?>

    <!-- Fin du Header -->

    

    <!-- Insertion du login form pour les non-connectés -->
    <?php include_once('login.php')?>
    <div class="container">
        <h1>Site de recettes !</h1>
    <?php require_once("includes/class-autoloader.inc.php"); ?>

    
    <!-- Si l'utilisateur est bien connecté il peut voir les recettes -->  
    <?php if (isset($loggedUser)):?> 
        <?php require_once("includes/class-autoloader.inc.php"); ?>
        <?php $recipes = new LoginView([]); ?>
        <!-- <?php //$recipes = new LoginView() ?> -->
        <?php foreach ($recipes->displayRecipes() as $recipe) : ?>
            <?php echo display_recipe($recipe); ?>
                <article class="article">
                <h3><a href="./recipes/read.php?id=<?php echo($recipe['recipe_id']); ?>"><?php echo($recipe['title']); ?></a></h3>
                    <h3><?php echo $recipe['title']; ?></h3>
                    <div><?php echo $recipe['recipe']; ?></div>                         
                    <i><?php echo displayAuthor($recipe["author"]) ?></i>                    
                    <?php if (isset($loggedUser) && $recipe['author'] === $loggedUser['email']) : ?>
                        <ul class="list-group">
                            <li class="list-group-item"><a class="link-warning" href="./recipes/update_recipes.php?id=<?php echo($recipe['recipe_id']) ?>">Editer l'article</a></li>
                            <li class="list-group-item"><a class="link-danger" href="./recipes/delete_recipes.php?id=<?php echo($recipe['recipe_id']) ?>">Supprimer l'article</a></li>
                        </ul>
                    <?php endif ?>
                </article>
        <?php endforeach ?>
        
    </div>
    <?php endif ?>
    
    <ul>
        <!-- <?php //for ($i = 0;
            //$i < $lines;
            //$i++):?> -->
        <li><?php //echo $recettes[$i].')';?></li>   
        <li><?php // echo $recettes[0]?></li> 
        <?php //endfor;?>
    </ul>
    <!-- Le Footer -->

    <?php include_once('includes/footer.inc.php'); ?>

    <!--  Fin du Footer -->
</body>
</html>