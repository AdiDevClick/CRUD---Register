<?php

declare(strict_types=1);

/* if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
} */
// echo "test, le blanc";
if (isset($_POST["submit"])) {
    $getDatas = [
        "selector" => $_POST["selector"],
        "validator" => $_POST["validator"],
        "password" => $_POST["password"],
        "passwordRepeat" => $_POST["pwdRepeat"]
    ];
    // echo "test 0, le post";
    if (empty($getDatas['selector']) || empty($getDatas['validator'])) {
        throw new Error((string)header("Location: ../index.php?newpwd=empty"));
    } elseif ($getDatas['password'] != $getDatas['passwordRepeat']) {
        throw new Error((string)header("Location: ../index.php?newpwd=pwdnotsame"));
    }

    $currentDate = date('U');

    require_once('../includes/class-autoloader.inc.php');
    // connexion db fetch selector
    // echo "test 1, le create";
    $reset = new PwdRecovery();
    $row = $reset->getPwdSelector($currentDate, $getDatas['selector']);
    //echo $row[0]['pwdReset_email'] . 'test du email row';
    $tokenBin = hex2bin($getDatas['validator']);
    $tokenCheck = password_verify($tokenBin, $row[0]['pwdReset_token']);

    if (!$tokenCheck) {
        //echo 'Vous devez refaire une demande de mot de passe oublié.';
        throw new Error((string)header('Location: ../index.php?newpwd=wrongtoken'));
    } elseif ($tokenCheck === true) {
        $tokenEmail = $row[0]['pwdReset_email'];
        //echo $tokenEmail . 'test du email';
        // connexion db fetch users where emailusers
        $reset->getUserEmail($tokenEmail);
        //echo "c'est true sur le token check";
        // connexion db update pwd updatePwd
        $reset->updatePwd($getDatas['password'], $tokenEmail);
        $reset->deletePwd($tokenEmail);
        header('Location: ../index.php?newpwd=passwordupdated');
    } else {
        header("Location: ../index.php");
    }

} else {
    header("Location: ../index.php");
}
