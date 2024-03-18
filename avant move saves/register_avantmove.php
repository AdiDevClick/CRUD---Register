<?php declare(strict_types=1) ?>

<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once("includes/class-autoloader.inc.php");
//include_once('config/mysql.php');
//include_once("config/user.php");
//include_once("includes/variables.inc.php");


//$data = $_SERVER['REQUEST_METHOD'] == 'POST';


/* $getData = $_POST;
$nom = $_POST['nom'];
$email = $_POST['email'];
$password = $_POST['password'];
$age = $_POST['age'];
$errorName = '';
$errorRecipe = ''; */

//$valid = new Validate($data);


/* $checkInput = new CheckInput(
    $data,
    $getData
); */


/* if ($data && isset($_POST['submit'])) {
    $password = '';
    $nom = '';
    $email = '';
    $age = '';

    require_once("includes/class-autoloader.inc.php");
    $getData = $_POST;

    $checkInput = new CheckInput(
        $getData
    );

    $nom = $checkInput->test_input($_POST['nom']);
    $email = $checkInput->test_input($_POST['email']);
    $password = $checkInput->test_input($_POST['password']);
    $pwdRepeat = $checkInput->test_input($_POST['pwdRepeat']);
    $age = $checkInput->test_input($_POST['age']);

    if ($checkInput->checkInputs()) {
        try {
            $signup = new SignupController($nom, $email, $password, $pwdRepeat, $age);
            $signup->insertUser($nom, $email, $password, $age); */
            //include_once('config/mysql.php');
            //require_once("includes/variables.inc.php");
            /* $db = new Mysql();
            $sqlQuery = 'INSERT INTO users(full_name, email, password, age)
                        VALUES (:full_name, :email, :password, :age)';
            $insertUsers = $db->connect()->prepare($sqlQuery);
            $insertUsers->execute([
                'full_name' => $nom,
                'email' => $email,
                'password' => $password,
                'age' => $age
            ]);  */

            //$db = null;
            //$signup = null;
            //header('Location: index.php');
            //die();

      /*   } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas dans la connexion DB...') ;
        }
    } else {
        echo 'Ca va pas....';
        header('Location: register.php');
        die();
    }
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
    <!-- <section class="container">
        <div class="form-flex">
            <h1>S'enregistrer</h1>
            <div class="form">
                <form action="submit_register.php" method="post">
                    <label for="nom" class="label">Votre prénom et nom :</label>
                    <input name="nom" type="text" id="nom" placeholder="Votre nom et prénom..." class="input">

                    <label for="email" class="label">Votre email :</label>
                    <input name="email" type="email" id="email" placeholder="Votre email..." class="input">

                    <label for="password" class="label">Votre mot de passe :</label>
                    <input name="password" type="password" id="password" placeholder="*****" class="input">

                    <label for="pwdRepeat" class="label">Confirmez votre mot de passe :</label>
                    <input name="pwdRepeat" type="password" id="pwdRepeat" placeholder="*****" class="input">

                    <label for="age" class="label">Votre âge :</label>
                    <input name="age" type="number" id="age" placeholder="Votre âge..." class="input">

                    <button type="submit" class="btn">S'enregistrer</button>
                </form>
            </div>    
        </div>
    </section> -->

    <!-- Fin du Main -->

    <!-- If register went well  -->
    <?php include_once('submit_register.php')?>

    <!-- End of the success -->

    <!-- Le Footer -->

    <?php include_once('includes/footer.inc.php') ?>

    <!--  Fin du Footer -->
</body>
</html>