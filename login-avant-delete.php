<?php declare(strict_types=1);

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

if ($data) {
    $username = '';
    $password = '';

    require_once("includes/class-autoloader.inc.php");

    $getData = $_POST;
    $checkInput = new CheckInput(
        $getData
    );

    $username = $checkInput->test_input($_POST['username']);
    $password = $checkInput->test_input($_POST['password']);
    $errorUsername = "Nom d'utilisateur";
    $errorPassword = 'Mot de passe';


    /*  $checkInput = new CheckInputs(
         $data,
         $username,
         $password,
         $errorUsername,
         $errorPassword
     ); */



    //$checkInput->checkInputs();


    $users = new Login();
    //if (isset($_POST['password']) && isset($_POST['username']))
    //{
    //checkLoginInputs($username, $password, $login);



    //checkPassword($password, $user['password']
    foreach ($users->getUsers() as $user) {
        /*  echo $user['password'] . '<br>'. PHP_EOL;
        //if (password_verify($password, $user['password'])) {
        if ($users->checkPassword($password, $user)) {
                echo 'they are the same' .'<br>' . PHP_EOL;
            } else {
                echo 'they are not' .'<br>' . PHP_EOL;
            } */
        if ($user['email'] === $username &&
        //$user->checkPassword($password)  )
        password_verify($password, $user['password'])) {
            //
            $loggedUser = [
            'email' => $user['email'],
            //'username' => $user['full_name'],
            ];
            header('Location: index.php');


            /****
             * Création d'un cookie qui expire dans 1 an
             */
            session_start();
            setcookie(
                'LOGGED_USER',
                $user['email'],
                [
                    'expires' => time() + 365 * 24 * 3600,
                    'secure' => true,
                    'httponly' => true,
                ]
            );
            $_SESSION['USER_ID'] = $user['user_id'];
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

        <button type="submit" class="bTn" id="btn"> S'identifier</button>
</form>
<!-- 
    Si l'utilisateur est bien loggé, on affiche le message de succès
-->
<?php else: ?>
    <div class="alert-success">
        Bonjour <?php echo strip_tags($loggedUser['email']) ?> et bienvenue sur le site !
    </div>
<?php endif ?>