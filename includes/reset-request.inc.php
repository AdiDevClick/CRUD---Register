<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR ."common.php";

// include_once("includes/class-autoloader.inc.php");
// include_once("../vendor/class-autoloader.inc.php");

$data = $_SERVER['REQUEST_METHOD'] === 'POST';
$err = [];

if ($data && isset($_POST['submit'])) {
    $userEmail = $_POST['email'];
    $getDatas = [
        'email' => $_POST['email'],
    ];
    $email = new CheckInput($getDatas);
    $email->checkInputs();
    $err = CheckInput::getErrorMessages();
    if (count($err) > 0) {
        return;
        // session_destroy();
    }

    $expires = date('U') + 1800;
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = 'adi.ezaya.fr/ClicRepare/create-new-password.php?selector=' . $selector . '&validator=' . bin2hex($token);

    // echo $userEmail . "L'email de l'user";
    // db connexion delete
    $reset = new PwdRecovery();
    // var_dump($userEmail);
    $reset->deletePwd($userEmail);
    $reset->pwdInsert($userEmail, $selector, $token, $expires);
    // echo "test 2, le insert pwd";
    // echo "test 1, le delete pwd";
    $to = $userEmail;

    $subject = 'Réinitialiser votre mot de passe pour Clic\'Répare';

    $message = '<p>Nous avons reçu une demande de réinitialisation de votre mot de passe. 
    Veuillez cliquer sur le lien pour réinitialiser le mot de passe.
    Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer cet email.</p>';
    $message .= '<p>Voici votre lien de réinitialisation : </br>';
    $message .= '<a href="' . $url . '">'. $url .'</a></p>';

    $headers = "From: Clic'Répare <contact@adi.ezaya.fr>\r\n";
    $headers .= "Reply-To: contact@adi.ezaya.fr\r\n";
    $headers .= "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);

    // header('Location: ../reset-password.php?reset=success');
    header('Location: reset-password.php?reset=success');

} else {
    // header('Location: index.php');
    // header('Location: ../index.php');
    return;
}
