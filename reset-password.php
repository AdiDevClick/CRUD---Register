<?php $title = "We Love Food - Réinitialisation de mot de passe"?>
<?php ob_start()?>
    <main>
        <div class="wrapper-main">
            <section class="section-default">
                <h1>Réinitialiser votre mot de passe</h1>
                    <!--<p>Un email vous sera envoyé avec les instructions pour réinitialiser votre mot de passe.</p>-->                
                    <form action="includes/reset-request.inc.php" method="post">
                    <!-- <label for="email">Votre Email</label> -->
                    <input class="input" type="text" id="email" name="email" placeholder="Entrez votre adresse email...">
                    <button type="submit" name="submit">Recevoir un nouveau mot de passe</button>
                </form>
                    <?php
                    if (isset($_GET["reset"])) {
                        if ($_GET["reset"] == "success") {
                            echo '<p class="signupsuccess">Veuillez vérifier votre boîte mail, un email vous a été envoyé avec les instructions pour réinitialiser votre mot de passe </p>';
                        }
                    }
?>
            </section>
        </div>
    </main>

    <?php $content = ob_get_clean()?>

    <?php require("templates/layout.php") ?>
