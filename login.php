<?php

declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("includes/class-autoloader.inc.php");
include_once("config/mysql.php");
//include_once("config/user.php");
//display_erreurLogin();
include_once('includes/variables.inc.php');

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




if (isset($data) && isset($_POST["submit"]) )
{    
    $submit = $_POST['submit'];
    
    $valid = new Validate('');

    $password = $valid->test_input($_POST['password']);
    $username = $valid->test_input($_POST['username']);
    $errorUsername = "Nom d'utilisateur";
    $errorPassword = 'Mot de passe';
    

    $checkInput = new CheckInputs(
        $data,
        $submit,
        $username,
        $password,    
        $errorUsername,
        $errorPassword
    );

    ($checkInput->checkInputs()); 



    //if (isset($_POST['password']) && isset($_POST['username']))
    //{
    //checkLoginInputs($username, $password, $login);

    foreach ($users as $user) {
        if ($user['email'] === $username &&
            $user['password'] === $password) {

            $loggedUser = [
                'email' => $user['email'],
                'username' => $user['full_name'],
            ];


            /****
             * Création d'un cookie qui expire dans 1 an
             */
            setcookie(
                'LOGGED_USER',
                $user['full_name'],
                [
                    'expires' => time() + 365 * 24 * 3600,
                    'secure' => true,
                    'httponly' => true,
                ]
            );
            $_SESSION['LOGGED_USER'] = $user['full_name'];

        } else {
            $errorMessage = sprintf(
                'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                $username,
                $password
            );
        }

    }
}
if (isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
    $loggedUser = [
        'email' => $_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER'],
    ];
}


/* function checkLoginInputs($username, $password, $login)
{
    $status = true;
    switch ($login) {
        case (!isset($username) || empty($username)):
            echo"Vous n'avez pas saisit d'username";
            return false;
        case (!isset($password) || empty($password)):
            echo"Vous n'avez pas saisit de mot de passe";
            return false;
    }
    return $status;
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
    <?php endif; ?>
        <label for="username">Votre identifiant :</label>
        <input type="text" id="username" name="username" placeholder="exemple@exemple.com"/>

        <label for="password"> Votre mot de passe :</label>
        <input type="password" id="password" name="password" placeholder="****">

        <button type="submit" class="bTn" id="btn" name="submit"> S'identifier</button>
</form>
<!-- 
    Si l'utilisateur est bien loggé, on affiche le message de succès
-->
<?php else: ?>
    <div class="alert-success">
        Bonjour <?php echo $loggedUser['email']; ?> et bienvenue sur le site !
    </div>
<?php endif ?>