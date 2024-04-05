<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clic'Répare - Nouveau mot de passe</title>
</head>
<body>
    <?php
    require_once("includes/header.php");
    ?>

    <main>
        <div class="wrapper-main">
            <section class="section-default">
                <?php
                    $selector = $_GET["selector"];
                    $validator = $_GET["validator"];

                    if (empty($selector) || empty($validator)) {
                        throw new Error((string)header("Location: ".Functions::getUrl()."?error=validator-not-found"));
                    } 
                    
                    if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
                        ?>

                        <form action="includes/reset-request.inc.php" method="post">
                            <input type="hidden" name="selector" value="<?php echo $selector?>">
                            <input type="hidden" name="validator" value="<?php echo $validator?>">
                            <input type="password" name="password" id="password" placeholder="Votre mot de passe...">
                            <input type="password" name="pwdRepeat" id="pwdRepeat" placeholder="Confirmez votre mot de passe...">
                            <button type="submit" name="submit">Réinitialiser mon mot de passe</button>
                        </form>
                        <?php
                    }
                
                ?>
            </section>
        </div>
    </main>

    <?php
    require_once("includes/footer.php");
    ?>
</body>
</html>
