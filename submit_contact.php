<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include_once('includes/functions.inc.php');
require_once(__DIR__ . "includes/functions.inc.php");

function display_mailReception($email, $message)
{
    $mail_content = '';
    $email = '';
    $message = '';

    if (isset($email) && isset($message)) {
        $mail_content = '<article>';
        $mail_content .= '<h3> Nous avons bien reçu votre message </h3>';
        $mail_content .= '<h5> Rappel de vos informations : </>';
        $mail_content .= '<p>' . '<span>Email</span> : ' . $email . '</p>';
        $mail_content .= '<p>' . '<span>Message</span> : ' . $message . '</p>';
        $mail_content .= '</article>';
    }
    return $mail_content;
}

display_mailReception($_POST['email'], $_POST['message']) ;

$email = $_POST['email'];
$message = $_POST['message'];
$timestamp = 10;

/* function display_erreurMessage()
{
    $email = $_POST['email'];
    $message = $_POST['message'];
    $erreurMessage = 'Il faut un email et un message pour soumettre le formulaire.';
    $isDisplayed = false;

    if (!isset($email) || !isset($message) ||
        empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo $erreurMessage;
        $isDisplayed = true;
        // Arrête l'exécution de PHP
        return  $isDisplayed;
    }
} */

function getUrl()
{
    $url = $_SERVER['REQUEST_URI'];
    return $url;
}
/* function redirect(string $url, string $redirectUrl)
{
    if (display_erreurMessage() === $url) {

        header($redirectUrl);
        time_sleep_until(time() + 2);
        echo 'Redirection OK';
    } else  {
        echo 'redirection pas ok';
    }
} */
$redirectUrl = 'Location: contact.php';
$url = '/recettes/submit_contact.php';
display_erreurMessageContact();
$t = time();
if (display_erreurMessageContact() && getUrl() === $url) {
    while ($t < $timestamp) {
        $t++;
        echo $t;
        //header($redirectUrl);
    }
}

/* if (getUrl() == $url) {
    time_sleep_until(time() + 2);
    header($redirectUrl);
    echo 'Redirection OK';
} else  {
    echo 'redirection pas ok';
} */

//redirect($_SERVER["PHP_SELF"], 'Location: contact.php') ;


// Nous testons si le fichier a bien été sélectionné et si il n'y a pas d'erreur
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    // On vérifie que le fichier ne pèse pas plus d'1Mo
    if ($_FILES['file']['size'] < 1000000) {
        // On vérifie les extensions autorisée
        $fileinfo = pathinfo($_FILES['file']['name']);
        $extension = $fileinfo['extension'];
        $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
        if (in_array($extension, $allowedExtensions)) {
            move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . basename($_FILES['file']['name'])) ;
            echo "L'envoi a bien été effectué !";
        } else {
            echo "Votre fichier n'est pas compatible";
        }
    } else {
        echo("Déjà envoyé..");
    }
}
?>

<?php $title = "We Love Food - Contactez-nous"?>
<?php ob_start()?>

<h1> Nous avons bien reçu votre message</h1>

<div class="card">
    <div class="card-body">
        <h5>Rappel de vos informations :</h5>
        <p><b>Email</b> : <?php echo strip_tags($email); ?></p>
        <p><b>Message</b> : <?php echo strip_tags($message); ?></p>

    </div>
</div> 

<?php $content = ob_get_clean()?>
<?php require('templates/layout.php')?>