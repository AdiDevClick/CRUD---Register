<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
//ob_start();
require_once("includes/class-autoloader.inc.php");

$data = $_SERVER['REQUEST_METHOD'] == 'POST';

if (($data && isset($_POST['submit']))) {

    $getDatas = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'pwdRepeat' => $_POST['pwdRepeat'],
        'age' => $_POST['age'],
    ];
    $signup = new SignupView(
        $getDatas
    );
    $signup->setUsers();
    //ob_start();

    header('refresh:10, index.php?register=success');
    session_destroy();
    //$content = ob_end_flush();
    //header('refresh:10, index.php');
    //exit();
    //header('refresh:5, '.Functions::getUrl().'?error=none');
}

/* if (isset($_COOKIE['REGISTERED_USER']) || isset($_SESSION['REGISTERED_USER'])) {
    $registeredUser = [
        'email' => $_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER'],
    ];
} */
// On affiche chaque recette une à une
//$content = ob_get_clean();
?>

<!-- Register form for non registered visitor -->
<?php //ob_start()?>
<?php //$registeredUser = LoginController::checkLoggedStatus()?>
<?php if (!isset($_SESSION['LOGGED_USER']) && !isset($_SESSION['REGISTERED_USER'])): ?>
    <form action="submit_register.php" method="post">
    <?php if (isset($e)):?>
        <div class="alert-error"> 
            <?php //echo $e?>    
        </div>
        <?php endif ?>        
        <label for="username" class="label">Votre prénom et nom :</label>
        <input name="username" class="input" type="text" id="username" placeholder="Votre nom et prénom..."/>

        <label for="email" class="label">Votre email :</label>
        <input name="email" class="input" type="email" id="email" placeholder="Votre email..."/>

        <label for="password" class="label">Votre mot de passe :</label>
        <input name="password" class="input" type="password" id="password" placeholder="*****" />

        <label for="pwdRepeat" class="label">Confirmez votre mot de passe :</label>
        <input name="pwdRepeat" class="input" type="password" id="pwdRepeat" placeholder="*****"/>

        <label for="age" class="label">Votre âge :</label>
        <input name="age" type="number" class="input" id="age" placeholder="Votre âge..."/>

        <button type="submit" name="submit" class="btn">S'enregistrer</button>
    </form>
    <?php //endif?> 
    <?php //session_destroy()?>
    <?php //$content = ob_get_clean()?>
    <?php //$content = ob_end_flush()?>
<!-- End of the form for non registered visitor -->

<!--  Display success message  -->
<?php //ob_start()?>
<?php //$content = ob_get_contents()?>
<?php elseif (isset($_SESSION['REGISTERED_USER'])):?>
    <?php //require_once('signup_success.php')?>
    <?php $signup->displaySignupSuccess($getDatas) ?>
    <?php unset($_SESSION['REGISTERED_USER']) ?>
        <?php else:?>
            <?php session_destroy()?>
            <?php header('Location, index.php')?>
            <?php exit()?> 

<?php endif ?>

<?php //$content = ob_end_flush()?>
<?php //$content = ob_get_clean()?>
<!-- End of display success message  -->
<?php //require('templates/layout.php')?>