<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
//ob_start();
require_once("includes/class-autoloader.inc.php");
// require_once("templates/toaster_template.html");


$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];
$loggedUser = [];

if (($data && isset($_POST['submit']))) {

    $getDatas = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'pwdRepeat' => $_POST['pwdRepeat'],
        'age' => $_POST['age'],
    ];
    $signup = new SignupView($getDatas);
    $signup->setUsers();
    // $errorMessages = CheckInput::getErrorsArray();
    // print_r($errorMessages);
    $err = CheckInput::getErrorMessages();
    // print_r($err) .  ' <==  array test ';
    //ob_start();
    if (count($err) > 0) {
        // print_r($errorMessages);
        session_destroy();
        // header('Location: register.php?register=failed');
        // die('je veux success mais cest pas bon <br>');
    } else {
        // print_r($errorMessages);
        header('refresh:10, index.php?register=success');
        session_destroy();
    }
    //$content = ob_end_flush();
    //header('refresh:10, index.php');
    //exit();
    //header('refresh:5, '.Functions::getUrl().'?error=none');
}
$loggedUser = LoginController::checkLoggedStatus();

// $errorMessage = '';
// $errorPassword = '';
// $errorUsername = '';
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
<?php //if (!isset($loggedUser['email'])):?> 

<?php if (!isset($_SESSION['LOGGED_USER']) || !isset($_SESSION['REGISTERED_USER'])): ?>
    <section class="contact-section">
        <div class="contact-grid">
            <div class="img">
                <img src="https://booking.webestica.com/assets/images/element/signin.svg" alt="" srcset="">
            </div>
            <div class="card">
            <!-- Form Title -->
                <div class="card-header">
                    <h3 class="contact-section"> S'enregistrer </h3>
                </div>
                <p class="contact-section">Déjà membre ? <a href="<?php echo strip_tags(Functions::getRootUrl()). 'recettes'.'/index.php'?>">Se connecter</a></p>
                <div class="contact">
                    <!-- <form action="register.php" method="post"> -->
                    <form class="form-contact" action="register.php" method="post">
                    <!-- <form action="submit_register.php" method="post"> -->
                    <?php //if (!empty($err)):?>
                        <!-- <div class="alert-error">  -->
                            <?php //print_r($err)?>
                        <!-- </div> -->
                        <?php // endif?>

                        <!-- Username -->
                        <div class="form form-hidden">
                            <label for="username" class="label">Votre prénom et nom :</label>
                            <?php if (array_key_exists('errorUsername', $err)) : ?>
                        <?php //print_r($err['errorUsername']) . '<= array'?>
                        <?php //if ($errorMessages) :?>
                        <?php //if ($err['errorUsername']) :?>
                                <input class="input_error" type="text" id="username" name="username" placeholder="<?php echo strip_tags($err['errorUsername'])?>" autocomplete="username">
                            <?php else: ?>
                                <input name="username" class="input" type="text" id="username" placeholder="Votre nom et prénom..."  autocomplete="username"/>
                            <?php endif ?>
                        </div>

                        <!-- Email -->
                        <div class="form form-hidden">
                            <label for="email" class="label">Votre email :</label>
                            <?php if (array_key_exists('errorEmail', $err)) : ?>
                                <input class="input_error" name="email" type="email" id="email" placeholder="<?php echo strip_tags($err['errorEmail'])?>"/>
                            <?php else: ?>
                                <input name="email" class="input" type="email" id="email" placeholder="Votre email..." autocomplete="email"/>
                            <?php endif ?>
                        </div>
                        
                        <!-- Password -->
                        <div class="form form-hidden">
                            <label for="password" class="label">Votre mot de passe :</label>
                            <?php if (array_key_exists('errorPassword', $err)) : ?>
                                <input class="input_error" name="password" type="password" id="password" placeholder="<?php echo strip_tags($err['errorPassword'])?>" autocomplete="new-password"/>
                            <?php else: ?>
                                <input name="password" class="input" type="password" id="password" placeholder="*****" autocomplete="new-password"/>
                            <?php endif ?>
                        </div>

                        <!-- Password Repeat -->
                        <div class="form form-hidden">
                            <label for="pwdRepeat" class="label">Confirmez votre mot de passe :</label>
                            <?php if (array_key_exists('errorPwdRepeat', $err)) : ?>
                                <input class="input_error" name="pwdRepeat" type="password" id="pwdRepeat" placeholder="<?php echo strip_tags($err['errorPwdRepeat'])?>" autocomplete="new-password"/>
                            <?php else: ?>
                                <input name="pwdRepeat" class="input" type="password" id="pwdRepeat" placeholder="*****" autocomplete="new-password"/>
                            <?php endif ?>
                        </div>

                        <!-- Age -->
                        <div class="form form-hidden">
                            <label for="age" class="label">Votre âge :</label>
                            <?php if (array_key_exists('age', $err)) : ?>
                                <input class="input_error" name="age" type="number" id="age" placeholder="<?php echo strip_tags($err['age'])?>" autocomplete="off"/>
                            <?php else: ?>
                                <input name="age" type="number" class="input" id="age" placeholder="Votre âge..." autocomplete="off"/>
                            <?php endif ?>
                        </div>

                        <!-- Submit -->
                        <div id="register-btn" class="form form-hidden">
                            <button type="submit" name="submit" class="btn">S'enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php //endif?> 
    <?php //session_destroy()?>
    <?php //$content = ob_get_clean()?>
    <?php //$content = ob_end_flush()?>
<!-- End of the form for non registered visitor -->

<!--  Display success message  -->
<?php //ob_start()?>
<?php //$content = ob_get_contents()?>

<?php //elseif (isset($_SESSION['REGISTERED_USER'])):?>
    <?php //require_once('signup_success.php')?>
    <?php //$signup->displaySignupSuccess($getDatas)?>
    <?php //unset($_SESSION['REGISTERED_USER'])?>
        <?php //else:?>
            <?php //session_destroy()?>
            <?php //header('Location, index.php')?>
            <?php //exit()?>
<!-- End of display success message  -->
<?php endif ?>

<?php //$content = ob_end_flush()?>
<?php //$content = ob_get_clean()?>
<?php //require('templates/layout.php')?>