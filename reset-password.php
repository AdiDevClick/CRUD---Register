<?php

require_once(__DIR__ . "/includes/class-autoloader.inc.php");
require_once(__DIR__ . "/logs/customErrorHandlers.php");

$script = 'type="module" src="scripts/errorApp.js" defer';
$title = "We Love Food - Réinitialisation de mot de passe";

require_once('includes/reset-request.inc.php');

$errorMessage = CheckInput::showErrorMessage();

ob_start()

?>
<section class="contact-section">
    <div class="contact-grid" id="recovery-page">
        <div class="contact-img">
            <img src="https://booking.webestica.com/assets/images/element/forgot-pass.svg" alt="" srcset="">
        </div>
        <div class="card" id="recovery-input" style="width: 80%">
            <!-- Header Section -->
            <div class="card-header-section">
                <!-- Logo -->
                <div class="form-logo">
                    <img src="img/logoicon.svg" alt="Logo du site web" srcset="">
                </div>
                <!-- Form Title -->
                <div class="card-header" style="width: 80%">
                    <?php if (isset($_GET["reset"])) : ?>
                        <?php if ($_GET["reset"] == "success") : ?>
                            <h3 class="contact-section">Nous vous avons envoyé un email</h3>
                        <?php endif ?>
                    <?php else : ?>
                        <h3 class="contact-section">Réinitialiser votre mot de passe</h3>
                    <?php endif ?>
                    <!-- <p class="contact-section">Saisissez l'adresse e-mail associée à votre compte.</p> -->
                    <?php
                        if (isset($_GET["reset"])) {
                            if ($_GET["reset"] == "success") {
                                echo '<p style="width: 80%" class="contact-section signupsuccess">Un email vous a été envoyé avec les instructions pour réinitialiser votre mot de passe à l\'adresse fournie </p>';
                            }
                        }
                    ?>
                    <?php //if (isset($_GET["reset"])) : ?>
                        <?php //if ($_GET["reset"] == "success") : ?>
                            <!-- <p style="width: 80%" class="contact-section signupsuccess">Veuillez vérifier votre boîte mail, un email vous a été envoyé avec les instructions pour réinitialiser votre mot de passe </p> -->
                        <?php //endif; ?>
                    <?php //endif ?>
                </div>
            </div>
            <div class="contact">
                <!-- <h1>Réinitialiser votre mot de passe</h1> -->
                <!-- <p>Un email vous sera envoyé avec les instructions pour réinitialiser votre mot de passe.</p>                 -->
                <form class="form-contact js-form" action="reset-password.php" method="post">
                    <?php if (!empty($err)) : ?>
                        <div>
                            <p class="alert-error"><?php echo(strip_tags($errorMessage)) ?></p>
                        </div>
                    <?php endif ?>
                <!-- <form class="form-contact js-form" action="includes/reset-request.inc.php" method="post"> -->
                    <!-- <label for="email">Votre Email</label> -->
                    <!-- Email -->
                    <div class="form form__group">
                        <?php if (array_key_exists('errorEmail', $err) || array_key_exists('emailTaken', $err)) : ?>
                            <input required class="input_error form__field" type="text" id="email" name="email" placeholder="<?php echo strip_tags($err['errorEmail'] ?? "Votre email...")?>" value="<?php echo strip_tags($getDatas['email']) ?>">
                        <?php else: ?>
                            <input required class="input form__field" type="text" id="email" name="email" placeholder="Entrez votre adresse email...">
                        <?php endif ?>
                        <label for="email" name="email" class="form__label">Entrez votre adresse email...</label>
                    </div>
                    <!-- Submit Button -->
                    <div class="form">
                        <button id="register-btn" class="btn" type="submit" name="submit">Recevoir un nouveau mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
    $content = ob_get_clean();
    require("templates/layout.php")
?>
