<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once("includes/class-autoloader.inc.php");


//ob_start();
$data = $_SERVER['REQUEST_METHOD'] == 'POST';

if ($data && isset($_POST["submit"])) {
    $username = '';
    $password = '';
    // We grab the data
    $getDatas = [
        'password' => $_POST['password'],
        'username' => $_POST['username'],
    ];
    // We instanciate the datas using the LoginController
    $login = new LoginView($getDatas);
    //$login = new LoginView($password, $username, $data, $getData);
    $login->displayLogin();
    //throw new Error("C'est ok pour le login");
    //header('Location: index.php');
    header('Location: index.php?login=success');
    //header('refresh:1, index.php?error=none');
    //exit();
}

// $loggedUser = LoginController::checkLoggedStatus();
// echo("cookie email : " . $loggedUser["email"] . "<br>". "cookie non enregistré : " .  $_COOKIE['LOGGED_USER'] . "<br>". "session non enregistrée : " .  $_SESSION['LOGGED_USER'] . "<br>". "session enregistrée : " . $loggedUser["user"] . "user enregistré :" . $_SESSION['USER_ID'] .  ' Ceci est un logged');
// if (isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
//     $loggedUser = [
//         'email' => $_COOKIE['LOGGED_USER'],
//         'user_id' => $_SESSION['LOGGED_USER'],
//     ];
// }
$loggedUser = LoginController::checkLoggedStatus();
// echo 'utilisateur cookie => ' . $loggedUser['user'] .'';
// echo 'utilisateur cookie => ' . $loggedUser;
foreach ($loggedUser as $user) {

    // print_r('test user => '. $user[1]);
    // print()
    // return $loggerUser = $user;
    // echo('pourquoi ca saffiche => ' . $loggedUser['email']);
    // print_r($user['email']);
    // print_r($loggedUser);
}
?>

<?php //ob_start()?>
<?php //ob_get_status()?>
<?php //ob_get_contents()?>

<?php //$loggedUser = LoginController::checkLoggedStatus()?>
<?php //include_once('index.php')?>
<?php //if (!isset($loggedUser['email']) || !isset($loggedUser['user'])): ?> 
<?php if (!isset($loggedUser['email'])): ?> 
<?php //if (!isset($loggedUser[0]['user_id'][0])):?> 
<?php //echo('pourquoi ca saffiche => ' . $loggedUser['user'])?> 
    <?php //echo("<br> cookie show 2 : cookie email : " . $loggedUser["email"] . "<br>". "cookie non enregistré : " .  $_COOKIE['LOGGED_USER'] . "<br>". "session non enregistrée : " .  $_SESSION['LOGGED_USER'] . "<br>". "session enregistrée : " . $loggedUser["user"] . "<br> user non enregistré :". $_SESSION['USER_ID'] . "<br> user id enregistré :" . $loggedUser["user"][0] . ' Ceci est un logged')?>
    <div class="form-index">
        <form action="login.php" method="post">
            <!-- Si il y a erreur on affiche le message -->
            <?php if (isset($errorMessage)): ?>
                <div class="alert-error">
                    <?php echo $errorMessage; ?> 
                    <?php //exit()?>   
                </div>
            <?php endif ?>
            <div class="form form-hidden">
                <label for="username">Votre identifiant :</label>
                <input type="text" id="username" name="username" placeholder="exemple@exemple.com"/>
            </div>
            <div class="form form-hidden">
                <label for="password"> Votre mot de passe :</label>
                <input type="password" id="password" name="password" placeholder="****">
            </div>
            <div class="form form-hidden">
                <button type="submit" name="submit" class="btn" id="btn"> S'identifier</button>
            </div>
        </form>        
    </div>      
<a href="reset-password.php">Mot de passe oublié ?</a>
<!-- 
    Si l'utilisateur est bien loggé, on affiche le message de succès
-->
<?php else: ?>
    <?php //require_once('login.php')?>
    <div class="alert-success">
        Bonjour <?php echo strip_tags($loggedUser['name']) ?> et bienvenue sur le site !
    </div>
<?php endif ?>

<?php if (isset($_GET['newpwd'])): ?>
        <?php if ($_GET['newpwd'] == 'passwordupdated'): ?>
            <div class="alert-success">
                <p>Votre mot de passe a été mise à jour !</p>
            </div>
        <?php endif ?>
<?php endif ?>


<?php //$contents = ob_end_flush()?>
<?php //$contents = ob_get_clean()?>

<?php //ob_start()?>
<!-- <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    > -->
<?php //$css = ob_get_clean()?>
<?php //ob_get_clean()?>

<?php //require('templates/layout.php')?>

<!-- $content = ob_get_clean();
require('templates/layout.php')
include_once('includes/header.inc.php')
?> -->
