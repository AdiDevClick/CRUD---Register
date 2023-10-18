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
    <title>We Love Food - Partagez votre recette</title>
</head>
<body>
    <!-- Le Header -->

    <?php include_once('../includes/header.inc.php')?>

    <!-- Fin du Header -->

    <!--  Le Main -->
    <section class="container">
        <div class="form-flex">
            <h1>Partagez votre recette !</h1>
            <div class="form">
                <form action="submit_recipes.php" method="post">
                    <label for="title" class="label">Titre de la recette :</label>
                    <input name="title" type="text" id="title" placeholder="Votre titre..." class="input">

                    <label for="recipe" class="label">Votre recette :</label>
                    <textarea name="recipe" id="recipe" cols="60" rows="10" placeholder="Renseignez votre recette..."></textarea>

                    <button type="submit" name="submit" class="btn">Envoyer</button>
                </form>
            </div>    
        </div>
    </section>

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('../includes/footer.inc.php') ?>

    <!--  Fin du Footer -->
</body>
</html>