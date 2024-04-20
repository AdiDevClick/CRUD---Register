<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/includes/class-autoloader.inc.php");
require_once(__DIR__ . "/includes/variables.inc.php");
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
    $err = CheckInput::getErrorMessages();
    if (count($err) > 0) {
        session_destroy();
    } else {
        header('Location: index.php?register=success');
        session_destroy();
    }
}
$loggedUser = LoginController::checkLoggedStatus();
$errorMessage = CheckInput::showErrorMessage()

?>

<!-- Register form for non registered visitor -->
<?php //if (!isset($loggedUser['email'])):?> 

<?php if (!isset($_SESSION['LOGGED_USER']) || !isset($_SESSION['REGISTERED_USER'])): ?>
    <section class="contact-section">
        <div class="contact-grid">
            <div class="contact-img">
                <img src="https://booking.webestica.com/assets/images/element/signin.svg" alt="" srcset="">
            </div>
            <div class="card">
                <!-- Header Section -->
                <div class="card-header-section">
                    <!-- Logo -->
                    <div class="form-logo">
                        <img src="img/logoicon.svg" alt="Logo du site web" srcset="">
                    </div>
                    <!-- Form Title -->
                    <div class="card-header">
                        <h3 class="contact-section"> S'enregistrer </h3>
                        <p class="contact-section">Déjà membre ? <a href="<?php echo strip_tags($rootUrl). $clicServer.'/index.php'?>">Se connecter</a></p>
                    </div>
                </div>
                <div class="contact">
                    <!-- <form action="register.php" method="post"> -->
                    <form class="form-contact js-form" action="register.php" method="post">
                    <!-- <form action="submit_register.php" method="post"> -->
                        <?php if (!empty($err)) : ?>
                            <div>
                                <p class="alert-error"><?php echo(strip_tags($errorMessage)) ?></p>
                            </div>
                        <?php endif ?>
                        <!-- Username -->
                        <div class="form form-hidden">
                            <label for="username" class="label">Votre nom d'utilisateur </label>
                            <?php if (!empty($getDatas['username']) || array_key_exists('errorUsername', $err) || array_key_exists('userTaken', $err)) : ?>
                        <?php //print_r($err['errorUsername']) . '<= array'?>
                        <?php //if ($errorMessages) :?>
                        <?php //if ($err['errorUsername']) :?>
                                <input class="input_error" type="text" id="username" name="username" placeholder="<?php echo strip_tags($err['errorUsername'] ?? 'Votre nom d\'utilisateur...') ?>" autocomplete="username" value="<?php echo strip_tags($getDatas['username'])?>" >
                            <?php else: ?>
                                <input name="username" class="input" type="text" id="username" placeholder="Votre nom d'utilisateur..."  autocomplete="username"/>
                            <?php endif ?>
                        </div>

                        <!-- Email -->
                        <div class="form form-hidden">
                            <label for="email" class="label">Votre email </label>
                            <?php if (!empty($getDatas['email']) || array_key_exists('errorEmail', $err) || array_key_exists('emailTaken', $err)) : ?>
                                <input class="input_error" name="email" type="email" id="email" placeholder="<?php echo strip_tags($err['errorEmail'] ?? "Votre email...")?>" value="<?php echo strip_tags($getDatas['email']) ?>"/>
                            <?php else: ?>
                                <input name="email" class="input" type="email" id="email" placeholder="Votre email..." autocomplete="email"/>
                            <?php endif ?>
                        </div>
                        
                        <!-- Password -->
                        <div class="form form-hidden">
                            <label for="password" class="label">Votre mot de passe </label>
                            <?php if (array_key_exists('errorPassword', $err) || array_key_exists('pwMatch', $err)) : ?>
                                <?php echo CheckInput::showInputError('password', 'password', array_key_exists('errorPassword', $err) ? 'errorPassword' : 'pwMatch', 'password', 'new-password') ?>
                                <!-- <input class="input_error" name="password" type="password" id="password" placeholder="<?php //echo strip_tags($err['errorPassword'] ?: "*****")?>" autocomplete="new-password"/> -->
                            <?php else: ?>
                                <input name="password" class="input" type="password" id="password" placeholder="*****" autocomplete="new-password"/>
                            <?php endif ?>
                        </div>

                        <!-- Password Repeat -->
                        <div class="form form-hidden">
                            <label for="pwdRepeat" class="label">Confirmez votre mot de passe </label>
                            <?php if (array_key_exists('errorPwdRepeat', $err) || array_key_exists('pwMatch', $err)) : ?>
                                <?php echo CheckInput::showInputError('pwdRepeat', 'password', array_key_exists('errorPwdRepeat', $err) ? 'errorPwdRepeat' : 'pwMatch', 'pwdRepeat', 'new-password') ?>
                                <!-- <input class="input_error" name="pwdRepeat" type="password" id="pwdRepeat" placeholder="<?php //echo strip_tags($err['errorPwdRepeat'] ?: "*****")?>" autocomplete="new-password"/> -->
                            <?php else: ?>
                                <input name="pwdRepeat" class="input" type="password" id="pwdRepeat" placeholder="*****" autocomplete="new-password"/>
                            <?php endif ?>
                        </div>

                        <!-- Age -->
                        <div class="form form-hidden">
                            <label for="age" class="label">Votre âge </label>
                            <?php //if (!empty($getDatas['age']) || array_key_exists('age', $err)) :?>
                            <?php if (array_key_exists('age', $err)) : ?>
                                <!-- <input class="input_error" name="age" type="number" id="age" placeholder="<?php //echo strip_tags($err['age'])?>" autocomplete="off" /> -->
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