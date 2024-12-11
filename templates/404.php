<?php
$title = "Erreur page 404";
ob_start();
?>

<h1 class="fourofour">404</h1>
<div class="cloak__wrapper">
    <div class="cloak__container">
        <div class="cloak"></div>
    </div>
</div>
<div class="info">
    <h2 class="fourofour">Cette page est introuvable</h2>
    <p class="fourofour">Cette page était là auparavant, peut-être pas d'ailleurs... Mais il semble que tu sois parti à sa recherches !

    </p>
    <p>Le bouton ci-dessous te ramènera en lieux sûrs</p>
    <a class="btn fourofour" href="../index.php" rel="noreferrer noopener">Home</a>
    <!-- <a class="btn fourofour" href="../index.php" target="_blank" rel="noreferrer noopener">Home</a> -->
</div>

<?php
$content = ob_get_clean();
require '../templates/layout.php';
?>