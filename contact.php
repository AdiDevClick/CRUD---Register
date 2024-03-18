<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<?php $title = "Clic'Répare - Contact?"?>
    <?php ob_start()?>
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
    <?php $content = ob_get_clean()?>
    <?php require('templates/layout.php')?>