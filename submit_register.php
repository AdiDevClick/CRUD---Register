<?php
declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("includes/class-autoloader.inc.php");
include_once('config/mysql.php');
//include_once("config/user.php");
include_once("includes/variables.inc.php");






$password = '';
$username = '';

$data = $_SERVER['REQUEST_METHOD'] == 'POST';
$getData = $_POST;
$nom = $_POST['nom'];
$email = $_POST['email'];
$password = $_POST['password'];
$age = $_POST['age'];
$errorName = '';
$errorRecipe = '';

$valid = new Validate($data);


$checkInput = new CheckInput(
    $data,
    $getData
);

try {
    if ($checkInput->checkInputs()) 
    echo 'okay';
    {    
        $sqlQuery = 'INSERT INTO users(full_name, email, password, age) 
                    VALUES (:full_name, :email, :password, :age)';
        $insertUsers = $db->prepare($sqlQuery);
        $insertUsers->execute([
            'full_name' => $nom,
            'email' => $email,
            'password' => $password,
            'age' => $age
        ]);
    }
} catch (Error $e) {
    die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas...') ;
}
/* try {
    if ($check = $checkInput->checkInputs())
    {
        $db = new PDO('mysql:host=localhost;dbname=my_recipes;charset=utf8', 'root', '');
    }
} catch (TypeError $e) {
    die ('Error ! : '. $e->getMessage() .'Something went wrong...');
} */
// On affiche chaque recette une à une


/* if (
    !isset($_POST['title'])
    || !isset($_POST['recipe'])
    )
{
    echo 'Il faut un titre et une recette pour soumettre le formulaire.';
    return;
} */

?>   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Love Food - S'enregistrer</title>
</head>
<body>
    <!-- Le Header -->

    <?php include_once('includes/header.inc.php')?>

<!-- Fin du Header -->

<!--  Le Main -->

    <section class="container">
        <div class="form-flex">
            <h1>Votre profil à bien été enregistré !</h1>
            <div class="card">
                <div class="card-body">
                    <h5>Rappel de vos informations :</h5>
                    <p><b>Votre nom</b> : <?php echo strip_tags($nom) ?></p>
                    <p><b>Votre email</b> : <?php echo strip_tags($email) ?></p>
                    <p><b>Votre âge</b> : <?php echo strip_tags($age) ?></p>
                    <p><b>Votre password </b> : <?php echo strip_tags($password) ?></p>
                </div>
            </div>  
        </div>
    </section>

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('includes/footer.inc.php'); ?>

    <!--  Fin du Footer -->
</body>
</html>