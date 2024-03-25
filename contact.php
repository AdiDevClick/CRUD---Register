<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<?php $script = 'src="scripts/fadeInScroller.js" defer' ?>
<?php $title = "Clic'Répare - Contact?"?>
    <?php ob_start()?>
    <!--  Le Main -->
    <h1> Contactez-nous </h1>

    <div class="contact">
        <form class="form-contact" action="submit_contact.php" method="post" enctype="multipart/form-data">
            <div class="form form-hidden">
            <label for="email">Email</label>
            <input type="email" class="input" id="email" name="email" placeholder="Votre email...">
            </div>
            
            <div class="form form-hidden">
            <label for="message">Votre Message</label>
            <textarea class="textarea" id="message" name="message" placeholder="Saisissez votre message..."></textarea>
            </div>
            
            <div class="form form-hidden">
            <label for="file"> Votre fichier</label>
            <input type="file" id="file" name="file"/>
            </div>
            
            <button type="submit" class="btn form-hidden" name="submit">Envoyez</button>
        </form>
    </div>
    
    <!-- Fin du Main -->
    <?php $content = ob_get_clean()?>
    <?php require('templates/layout.php')?>