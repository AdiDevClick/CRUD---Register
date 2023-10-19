<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Love Food - S'enregistrer</title>
</head>
<body>
    <!-- Le Header -->

    <?php include_once('includes/header.inc.php')?>

    <!-- Fin du Header -->

    <!--  Le Main -->
    <section class="container">
        <div class="form-flex">
            <h1>S'enregistrer</h1>
            <div class="form">
                <form action="submit_register.php" method="post">
                    <label for="nom" class="label">Votre prénom et nom :</label>
                    <input name="nom" type="text" id="nom" placeholder="Votre nom et prénom..." class="input">

                    <label for="email" class="label">Votre email :</label>
                    <input name="email" type="email" id="email" placeholder="Votre email..." class="input">

                    <label for="password" class="label">Votre mot de passe :</label>
                    <input name="password" type="password" id="password" placeholder="*****" class="input">

                    <label for="confirm-password" class="label">Confirmez votre mot de passe :</label>
                    <input name="confirm-password" type="confirm-password" id="confirm-password" placeholder="*****" class="input">

                    <label for="age" class="label">Votre âge :</label>
                    <input name="age" type="number" id="age" placeholder="Votre âge..." class="input">

                    <button type="submit" name="submit" class="btn">S'enregistrer</button>
                </form>
            </div>    
        </div>
    </section>

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('includes/footer.inc.php') ?>

    <!--  Fin du Footer -->
</body>
</html>