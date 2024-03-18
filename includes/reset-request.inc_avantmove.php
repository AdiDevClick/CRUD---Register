<?php declare(strict_types=1)?>

<?php

$data = $_SERVER['REQUEST_METHOD'] == $_POST;

if ($data && isset($_POST['submit'])) {
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = 'adi.ezaya.fr/create-new-password.php?selector=' . $selector . '&validator=' . bin2hex($token);

    $expires = date('U') + 1800;

    $userEmail = $_POST['email'];

    // db connexion delete
    $reset = new PwdRecovery;
    $reset->deletePwd($userEmail);

    $reset->pwdInsert($userEmail, $selector, $token, $expires);

    $to = $userEmail;

    $subjet = 'Réinitialiser votre mot de passe pour Clic\'Répare';

    $message = '<p>Nous avons reçu une demande de réinitialisation de votre mot de passe. 
    Veuillez cliquer sur le lien pour réinitialiser le mot de passe.
    Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer cet email.</p>';
    $message .= '<p>Voici votre lien de réinitialisation : </br>';
    $message .= '<a href="' . $url . '">'. $url .'</a></p>';

    $headers = "From: Clic'Répare <contact@adi.ezaya.fr>\r\n";
    $headers .= "Reply-To: contact@adi.ezaya.fr\r\n";
    $headers .= "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);

    header('Location: ../reset-password.php?reset=success');

} else {
    header('Location: ../index.php');
}