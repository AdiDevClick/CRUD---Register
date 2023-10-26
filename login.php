<?php declare(strict_types=1) ?>

<?php

require_once("includes/class-autoloader.inc.php");
/* if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
} */

//include_once("includes/class-autoloader.inc.php");
//include_once("config/mysql.php");
//include_once("config/user.php");
//display_erreurLogin();
//include_once('includes/variables.inc.php');

$data = $_SERVER['REQUEST_METHOD'] == 'POST';

/* $password = '';
$username = '';
$errorUsername = '';
$errorPassword = '';
$valid = new Validate($password);

$checkInput = new CheckInputs(
    $data,
    $submit,
    $username,
    $password,
    $errorUsername,
    $errorPassword
); */

if ($data && isset($_POST["submit"])) {
    $username = '';
    $password = '';


    
    // We grab the data

    $getDatas = [
        'password' => $_POST['password'],
        'username' => $_POST['username'],      
    ];
    
    /* $password = $_POST['password'];
    $username = $_POST['username'];
    $getData = $_POST;  */

    // We instanciate the datas using the LoginController
    $login = new LoginView($getDatas);
    //$login = new LoginView($password, $username, $data, $getData);
    $login->displayLogin();
    /* $getData = $_POST;
    $checkInput = new CheckInput(
        $getData
    );
    $password = $checkInput->test_input($_POST['password']);
    $username = $checkInput->test_input($_POST['username']); */


    
    /*$errorUsername = "Nom d'utilisateur";
    $errorPassword = 'Mot de passe';*/

    //$checkInput->checkInputs();

    //$login = new Logincontroller($password, $username);
    /* $login = new Logincontroller($password, $username, $data, $getData);
    $login->index(); */

    
}
$loggedUser = LoginController::checkLoggedStatus();
/* if (isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
    $loggedUser = [
        'email' => $_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER'],
    ];
} */

?>

<!-- 
    Si l'utilisateur n'est pas logged, on affiche le formulaire 
-->
<?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
<form action="index.php" method="post">
    <!-- Si il y a erreur on affiche le message -->
    <?php if (isset($errorMessage)): ?>
        <div class="alert-error">
            <?php echo $errorMessage; ?>    
        </div>
    <?php endif ?>
        <label for="username">Votre identifiant :</label>
        <input type="text" id="username" name="username" placeholder="exemple@exemple.com"/>

        <label for="password"> Votre mot de passe :</label>
        <input type="password" id="password" name="password" placeholder="****">

        <button type="submit" name="submit" class="bTn" id="btn"> S'identifier</button>
</form>
<!-- 
    Si l'utilisateur est bien loggé, on affiche le message de succès
-->
<?php else: ?>
    <div class="alert-success">
        Bonjour <?php echo strip_tags($loggedUser['email']) ?> et bienvenue sur le site !
    </div>
<?php endif ?>