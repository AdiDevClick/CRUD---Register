<?php

$selector = $_GET['selector'];
$validator = $_GET['validator'];

if (empty($selector) || empty($validator)) {
    throw new Error((string)header("Location: ".Functions::getUrl()."?error=validator-not-found"));
}

$title = "Clic'Répare - Nouveau mot de passe";
ob_start()
?>
    <main>
        <div class="wrapper-main">
            <section class="section-default">
                <?php if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false):?>
                    <form action="includes/reset-password.inc.php" method="post">
                        <input type="hidden" name="selector" value="<?php echo $selector?>">
                        <input type="hidden" name="validator" value="<?php echo $validator?>">
                        <input type="password" name="password" id="password" placeholder="Votre mot de passe...">
                        <input type="password" name="pwdRepeat" id="pwdRepeat" placeholder="Confirmez votre mot de passe...">
                        <button type="submit" name="submit">Réinitialiser mon mot de passe</button>
                    </form>
                <?php endif?>
            </section>
        </div>
    </main>
<?php
    $content = ob_get_clean();
require("templates/layout.php")
?>
