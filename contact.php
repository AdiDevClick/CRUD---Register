<?php

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "variables.inc.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "functions.inc.php";

$scripts = ['src="' . strip_tags($rootUrl) . $clicServer . '/scripts/fadeInScroller.js" defer'];
$title = "Clic'Répare - Contact?";

ob_start()
?>
<!--  Le Main -->
<!-- <h1> Contactez-nous </h1> -->
<section class="card_container">
    <div class="contact-grid">
        <div class="contact-img">
            <img src="https://booking.webestica.com/assets/images/element/contact.svg" alt="" srcset="">
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="contact-section"> Contactez-nous </h3>
            </div>
            <div class="contact">
                <form class="form-contact" action="submit_contact.php" method="post" enctype="multipart/form-data">
                    <!-- Email -->
                    <div class="form form-hidden">
                        <label for="email">Email</label>
                        <input type="email" class="input" id="email" name="email" placeholder="Votre email...">
                    </div>
                    <!-- Message -->
                    <div class="form form-hidden">
                        <label for="message">Votre Message</label>
                        <textarea class="textarea" id="message" name="message" placeholder="Saisissez votre message..."></textarea>
                    </div>
                    <!-- File -->
                    <div class="form form-hidden">
                        <label for="file"> Votre fichier</label>
                        <input type="file" id="file" name="file" />
                    </div>
                    <!-- Send Button -->
                    <div class="form form-hidden">
                        <button type="submit" class="btn" name="submit">Envoyez</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Fin du Main -->
<?php $content = ob_get_clean();
require 'templates/layout.php'
?>