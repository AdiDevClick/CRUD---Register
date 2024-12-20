<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

// require_once(__DIR__ . "/includes/class-autoloader.inc.php");
require_once __DIR__ . "/includes/common.php";

$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];
$loggedUser = [];
if ($data && isset($_POST["submit"])) {
    // We grab the data
    $getDatas = [
        'password' => $_POST['password'],
        'username' => $_POST['username'],
    ];
    // We instanciate the datas using the LoginController
    $login = new LoginView($getDatas);
    $login->displayLogin();
    $err = CheckInput::getErrorMessages();

    if (count($err) > 0) {
        // session_unset();
        session_destroy();
    }

    if (!isset($loggedUser['user'])) {
        $loggedUser = LoginController::checkLoggedStatus();
    }

    if (isset($loggedUser['user'])) {
        header('Location: index.php?login=success', true);
    }
}

$loggedUser = LoginController::checkLoggedStatus();

?>

<?php if (!isset($loggedUser['email'])): ?>
    <div class="contact">
        <?php if (!empty($err) && (isset($err['userError']) || isset($err['userTaken']))): ?>
            <?php $errorMessage = $err['userError'] ?? $err['emailTaken'] ?? $err['invalidID'] ?>
            <div>
                <p class="alert-error"><?php echo strip_tags($errorMessage) ?></p>
            </div>
        <?php endif ?>
        <form class="form-contact form-index js-form" action="index.php" method="post">
            <!-- <form class="form-contact js-form" action="index.php" method="post"> -->
            <!-- Si il y a erreur on affiche le message -->
            <?php //if (!empty($err) && (isset($err['userError']) || isset($err['userTaken']))):
            ?>
            <?php //$errorMessage = $err['userError'] ?? $err['emailTaken']
            ?>
            <!-- <div>
                        <p class="alert-error"><?php //echo strip_tags($errorMessage)
                                                ?></p>
                    </div> -->
            <?php //endif
            ?>
            <!-- Username -->
            <div class="form splash-login form-hidden">
                <!-- <div class="form splash-login form-hidden"> -->
                <label for="username">Votre identifiant</label>
                <?php if (!empty($getDatas['username']) || array_key_exists('errorUsername', $err)) : ?>
                    <input class="input_error" type="text" id="username" name="username" placeholder="<?php echo strip_tags($err['errorUsername'] ?? 'Votre identifiant...') ?>" autocomplete="username" value="<?php echo strip_tags($getDatas['username']) ?>" />
                <?php else: ?>
                    <input type="text" id="username" name="username" placeholder="exemple@exemple.com" autocomplete="username" />
                <?php endif ?>
            </div>
            <!-- Password -->
            <div class="form splash-login form-hidden">
                <!-- <div class="form splash-login form-hidden"> -->
                <label for="password"> Votre mot de passe</label>
                <?php if (array_key_exists('errorPassword', $err) || array_key_exists('userError', $err)) : ?>
                    <input class="input_error" type="password" id="password" name="password" placeholder="<?php echo strip_tags($err['errorPassword'] ?? '') ?>" autocomplete="current-password">
                <?php else: ?>
                    <input type="password" id="password" name="password" placeholder="****" autocomplete="current-password">
                <?php endif ?>
            </div>
            <!-- Submit -->
            <div class="form splash-login form-hidden">
                <!-- <div class="form splash-login form-hidden"> -->
                <button type="submit" name="submit" class="btn" id="btn">S'identifier</button>
            </div>
            <!-- Password Recovery link -->
            <div class="form splash-login pw_reset">
                <!-- <div class="pw_reset"> -->
                <a href="reset-password.php">Mot de passe oublié ?</a>
            </div>
        </form>
    </div>
    <!--
    Si l'utilisateur est bien loggé, on affiche le message de succès
-->
<?php else: ?>
    <div class="">
        <p>Bonjour <span class="bold"><?php echo strip_tags(ucfirst($loggedUser['name'])) ?></span> et bienvenue sur le site !</p>
    </div>
<?php endif ?>

<?php if (isset($_GET['newpwd'])): ?>
    <?php if ($_GET['newpwd'] == 'passwordupdated'): ?>
        <div class="alert-success">
            <p>Votre mot de passe a été mise à jour !</p>
        </div>
    <?php endif ?>
<?php endif ?>