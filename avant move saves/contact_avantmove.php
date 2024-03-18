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
    <title>Site de recettes - Formulaire de Contact </title>

</head>
<body>
    <!-- Le Header -->

    <?php include_once('includes/header.inc.php')?>

    <!-- Fin du Header -->

    <!--  Le Main -->
    <h1> Contactez-nous </h1>
    <form action="submit_contact.php" method="post" enctype="multipart/form-data">
        <label for="email">Email</label>        
        <input type="email" class="input" id="email" name="email" placeholder="Votre email...">
        
        <label for="message">Votre Message</label>
        <textarea class="textarea" id="message" name="message" placeholder="Saisissez votre message..."></textarea>
        
        <label for="file"> Votre fichier</label>
        <input type="file" id="file" name="file"/>
        
        <button type="submit" class="bTn" name="submit">Envoyez</button>
    </form>

    <!-- Fin du Main -->
    
    <!-- Le Footer -->

    <?php include_once('includes/footer.inc.php'); ?>

    <!--  Fin du Footer -->
</body>
</body>
</html>