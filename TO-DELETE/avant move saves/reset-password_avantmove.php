<?php 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Header part -->
    <?php include_once("includes/header.inc.php") ?>
    <!-- End of Header -->
    <main>
        <div class="wrapper-main">
            <section class="section-default">
                <h1>Réinitialiser votre mot de passe</h1>
                <p>Un email vous sera envoyé avec les instructions pour réinitialiser votre mot de passe.</p>
                <form action="includes/reset-request.inc.php" method="post">
                    <input class="input" type="text" name="email" placeholder="Entrez votre adresse email...">
                    <button type="submit" name="submit">Recevoir un nouveau mot de passe</button>
                </form>
                    <?php
                    if (isset($_GET["reset"])) {
                        if ($_GET["reset"] == "success") {
                            echo '<p class="signupsucess">Vérifiez votre boîte mail </p>';
                        }
                    }
                    ?>
            </section>
        </div>
    </main>

    <!-- Footer part -->
    <?php include_once("includes/footer.inc.php") ?>
    <!-- End of Footer -->
</body>
</html>


