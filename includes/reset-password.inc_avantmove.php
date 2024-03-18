<?php

if (isset($_POST["submit"])) {
    $getDatas = [
        "selector"=> $_POST["selector"],
        "validator"=> $_POST["validator"],
        "password"=> $_POST["password"],
        "passwordRepeat"=> $_POST["pwdRepeat"],
    ];

    if (empty($selector) || empty($validator)) {
        throw new Error((string)header("Location: ../index.php?newpwd=empty"));
    } else if ($password != $passwordRepeat) {
        throw new Error((string)header("Location: ../index.php?newpwd=pwdnotsame"));
    }

    $currentDate = date('U');

    require_once('../includes/class-autoloader.inc.php');
    // connexion db fetch selector
    $reset = new PwdRecovery;
    $row = $reset->getPwdSelector($currentDate, $getDatas['selector']);
    
    $tokenBin = hex2bin($validator);
    $tokenCheck = password_verify($tokenBin, $row['pwdReset_token']);

    if (!$tokenCheck) {
        echo 'Vous devez refaire une demande de mot de passe oublié.';
        throw new Error((string)header('Location: ../index.php?newpwd=wrongtoken'));
    } elseif ($tokenCheck === true) {
        $tokenEmail = $row['pwdReset_email'];

    // connexion db fetch users where emailusers
    $reset->getUserEmail($tokenEmail);
        } else {

    // connexion db update pwd updatePwd
    $reset->updatePwd($getDatas['password'], $tokenEmail);

    $reset->deletePwd($tokenEmail);

    header ('Location: ../index.php?newpwd=passwordupdated');
    }

} else {
    header("Location: ../index.php");
}

?>