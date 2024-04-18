<?php 
    $title = "We Love Food - Réinitialisation de mot de passe";
    ob_start()
?>
<main>
    <section class="wrapper-main">
        <div class="pw-recovery">
            <div class="card">
                <!-- <div class="section-default"> -->
                <div class="card-header">
                    <h3 class="contact-section">Vous avez oublié votre mot de passe ?</h3>
                </div>
                <!-- <h1>Réinitialiser votre mot de passe</h1> -->
                <p class="contact-section">Saisissez l'adresse e-mail associé à votre compte.</p>
                <!-- <p>Un email vous sera envoyé avec les instructions pour réinitialiser votre mot de passe.</p>                 -->
                <form class="form-index" action="includes/reset-request.inc.php" method="post">
                    <!-- <label for="email">Votre Email</label> -->
                    <input class="input" type="text" id="email" name="email" placeholder="Entrez votre adresse email...">
                    <button class="btn" type="submit" name="submit">Recevoir un nouveau mot de passe</button>
                </form>
                <?php
                    if (isset($_GET["reset"])) {
                        if ($_GET["reset"] == "success") {
                            echo '<p class="signupsuccess">Veuillez vérifier votre boîte mail, un email vous a été envoyé avec les instructions pour réinitialiser votre mot de passe </p>';
                        }
                    }
                ?>
                <!-- </div> -->
            </div>
        </div>
    </section>
</main>

<?php 
    $content = ob_get_clean();
    require("templates/layout.php")
?>
