<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once("includes/class-autoloader.inc.php");
// include_once("templates/toaster_template.html");



//ob_start();
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];
$loggedUser = [];

// if (isset($errorMessages) && !empty($errorMessages)) {
//     echo('je suis dans le premier error check =>   <br>');
//     print_r($errorMessages);
//     echo('<br>');
// }
// $loggedUser = LoginController::checkLoggedStatus();


if ($data && isset($_POST["submit"])) {
    // $username = '';
    // $password = '';
    // We grab the data
    $getDatas = [
        'password' => $_POST['password'],
        'username' => $_POST['username'],
    ];
    // We instanciate the datas using the LoginController
    $login = new LoginView($getDatas);
    //$login = new LoginView($password, $username, $data, $getData);
    $login->displayLogin();
    // $errorMessages = CheckInput::getErrorsArray();
    $err = CheckInput::getErrorMessages($getDatas);
    // echo 'test' . $err;
    // print_r($err);
    //throw new Error("C'est ok pour le login");
    //header('Location: index.php');
    // $loggedUser = LoginController::checkLoggedStatus();

    if (count($err) > 0) {
        // print_r($errorMessages);
        session_destroy();
        // die('je veux success mais cest pas bon <br>');
    
        // } else {
    //     // $loggedUser = LoginController::checkLoggedStatus();
    //     if (isset($loggedUser['email'])) {
    //         header('Location: index.php?login=success');
    //     } else {
    //         echo 'erreur';
    //         $loggedUser = LoginController::checkLoggedStatus();
    //         header('Location: index.php?login=success');
    //         // session_destroy();
    //     }
        // die("c'est ok je peux success <br>");
    }
    if (!isset($loggedUser['user'])) { 
        $loggedUser = LoginController::checkLoggedStatus();
    }
    if (isset($loggedUser['user'])) {
        header('Location: index.php?login=success');
    }
    //header('refresh:1, index.php?error=none');
    //exit();
}

$loggedUser = LoginController::checkLoggedStatus();
// print_r($loggedUser) ;
// echo 'deuxieme print';
// echo("cookie email : " . $loggedUser["email"] . "<br>". "cookie non enregistré : " .  $_COOKIE['LOGGED_USER'] . "<br>". "session non enregistrée : " .  $_SESSION['LOGGED_USER'] . "<br>". "session enregistrée : " . $loggedUser["user"] . "user enregistré :" . $_SESSION['USER_ID'] .  ' Ceci est un logged');
// if (isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
//     $loggedUser = [
//         'email' => $_COOKIE['LOGGED_USER'],
//         'user_id' => $_SESSION['LOGGED_USER'],
//     ];
// }
// $errorMessage = '';
// $errorPassword = '';
// $errorUsername = '';
// $errorMessages = CheckInput::getErrorsArray();
// print_r($errorMessages);
// foreach ($errorMessages as $key => $value) {
//     echo 'la clé => ' . $key . '<br>';
//     $errorMessage[$key] = $value;
//     echo 'la value => ' .$value . '<br>';
//     $errorMessage = $errorMessages[$key];
//     echo 'message derreur =>'. $errorMessage . '<br>';
// }
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
<?php //if (!isset($loggedUser['email']) || !isset($loggedUser['user'])):?> 
<?php if (!isset($loggedUser['email'])): ?> 
<?php //if (!isset($loggedUser[0]['user_id'][0])):?> 
<?php //echo('pourquoi ca saffiche => ' . $loggedUser['user'])?> 
    <?php //echo("<br> cookie show 2 : cookie email : " . $loggedUser["email"] . "<br>". "cookie non enregistré : " .  $_COOKIE['LOGGED_USER'] . "<br>". "session non enregistrée : " .  $_SESSION['LOGGED_USER'] . "<br>". "session enregistrée : " . $loggedUser["user"] . "<br> user non enregistré :". $_SESSION['USER_ID'] . "<br> user id enregistré :" . $loggedUser["user"][0] . ' Ceci est un logged')?>
    <div class="form-index">
        <form action="index.php" method="post">
            <!-- Si il y a erreur on affiche le message -->
            <?php //if (!empty($errorMessages)):?>
            <?php if (!empty($err) && (isset($err['userError']) || isset($err['userTaken']))):?>
                <?php //foreach ($errorMessages as $key => $value):?>
                    <?php //if (str_contains($value, 'password')):?>
                        <?php //$errorPassword = $value?>
                    <?php //elseif (str_contains($value, 'username')):?>
                        <?php $errorMessage = $err['userError'] ?? $err['emailTaken'] ?>
                    <?php //else :?>
                        <?php //$errorMessage = $value?>
                    <?php //endif?>
                    
                    <?php //$errorMessage = $value?>
                    <?php //$errorMessage = "placeholder=$value"?>
                    <div>
                        <?php //echo $errorMessage?> 
                        <?php //echo CheckInput::getErrorMessage() . '<br>';?> 
                        <p class="alert-error"><?php echo strip_tags($errorMessage) ?></p>
                        <?php //exit()?>
                    </div>
                <?php //endforeach?>
            <?php endif?>
            <!-- Username -->
            <div class="splash-login form-hidden">
                <label for="username">Votre identifiant :</label>
                <?php //if (!empty($errorMessages)) :?>
                <?php if (array_key_exists('errorUsername', $err)) : ?>
                <?php //if ($err['errorUsername']) :?>
                <?php //if ($errorUsername) :?>
                    <input class="input_error" type="text" id="username" name="username" placeholder="<?php echo strip_tags($err['errorUsername'])?>" autocomplete="username"/>
                    <!-- <input class="input_error" type="text" id="username" name="username" placeholder="<?php //echo strip_tags($errorUsername)?>" autocomplete="username"/> -->
                <?php else: ?>
                    <input type="text" id="username" name="username" placeholder="exemple@exemple.com" autocomplete="username"/>
                <?php endif ?>
            </div>
            <!-- Password -->
            <div class="splash-login form-hidden">
                <label for="password"> Votre mot de passe :</label>
                <?php //if ($errorPassword) :?>
                <?php if (array_key_exists('errorPassword', $err)) : ?>
                <?php //if ($err['errorPassword']) :?>
                <?php //if (empty($errorMessages)) :?>
                    <input class="input_error" type="password" id="password" name="password" placeholder="<?php echo strip_tags($err['errorPassword']) ?>" autocomplete="current-password">
                    <!-- <input class="input_error" type="password" id="password" name="password" placeholder="<?php //echo strip_tags($errorPassword)?>" autocomplete="current-password"> -->
                <?php else: ?>
                    <input type="password" id="password" name="password" placeholder="****" autocomplete="current-password">
                <?php endif ?>
            </div>
            <!-- Submit -->
            <div class="splash-login form-hidden">
                <button type="submit" name="submit" class="btn" id="btn"> S'identifier</button>
            </div>
        </form>
    </div>
<a class="pw_reset" href="reset-password.php">Mot de passe oublié ?</a>
<!-- 
    Si l'utilisateur est bien loggé, on affiche le message de succès
-->
<?php else: ?>
    <?php //require_once('login.php')?>
    <div class="alert-success">
        Bonjour <?php echo strip_tags(ucfirst($loggedUser['name'])) ?> et bienvenue sur le site !
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
<!-- <?php //require("templates/toaster_template.html")?> -->

<!-- $content = ob_get_clean();
require('templates/layout.php')
include_once('includes/header.inc.php')
?> -->
