<?php
class PwdRecovery extends Mysql 
{
    public function deletePwd(string $email)
    {
        $sqlQuery = 'DELETE FROM pwdReset WHERE pwdReset_email = :email;';
        $deletePwdStatement = $this->connect()->prepare($sqlQuery);
        if (!$deletePwdStatement->execute([
            'email' => $email
        ])) {
            $deletePwdStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=stmt-failed"));
        }
        if ($deletePwdStatement->rowCount() == 0) {
            $deletePwdStatement = null;
            echo strip_tags("Ce mot de passe ne peut pas être supprimée, l'email n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=pwdreset-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        //header('Location: ../index.php');
    }

    public function pwdInsert(string $email, string $selector, string $token, int $expires): void
    {
        $sqlQuery =
        'INSERT INTO pwdReset(pwdReset_email, pwdReset_selector, pwdReset_token, pwdReset_expires) 
        VALUES (:pwdReset_email, :pwdReset_selector, :pwdReset_token, :pwdReset_expires);';

        $insertPwd = $this->connect()->prepare($sqlQuery);

        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        if (!$insertPwd->execute([
            'pwdReset_email' => $email,
            'pwdReset_selector' => $selector,
            'pwdReset_token' => $hashedToken,
            'pwdReset_expires' => $expires
        ])) {
            $insertPwd = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=stmt-failed"));
            //header("Location : ".$url->getThisUrl(). "?error=user-not-found");
        }
        $insertPwd = null;
        /* header("Location : ".Functions::getUrl(). "?error=stmt-failed");
        exit(); */
    }

    public function updatePwd(string $password, string $email)
    {
        $sqlQuery = 'UPDATE users SET password = :password WHERE email = :email;';

        $updatePwdStatement = $this->connect()->prepare($sqlQuery);

        $options = [
            'cost' => 12
        ];

        $newHashedPwd = password_hash($password, PASSWORD_DEFAULT, $options);

        if (!$updatePwdStatement->execute([
            'password' => $newHashedPwd,
            'email' => $email
        ])) {
            $updatePwdStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=stmt-failed"));
        }
        if ($updatePwdStatement->rowCount() == 0) {
            $updatePwdStatement = null;
            echo strip_tags("Ce mot de passe ne peut pas être mise à jour, l'utilisateur ne semble pas exister.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=uppwd-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
    }

    public function getPwdSelector(string $expires, string $selector): array
    {
        $sqlRecipesQuery = 'SELECT * FROM `pwdReset` WHERE pwdReset_selector = :pwdReset_selector AND pwdReset_expires >= :pwdReset_expires;';
        $selectorStatement = $this->connect()->prepare($sqlRecipesQuery);
        if (!$selectorStatement->execute([
            'pwdReset_expires' => $expires,
            'pwdReset_selector' => $selector
        ])) {
            $selectorStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=stmt-failed"));
            //header("Location : ".getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($selectorStatement->rowCount() == 0) {
            $selectorStatement = null;
            echo strip_tags("Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !");
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=selector-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $selector = $selectorStatement->fetchAll();
        return $selector;
    }

    public function getUserEmail(string $email): array
    {
        $sqlRecipesQuery = 'SELECT * FROM `users` WHERE email = :email;';
        $userEmailStatement = $this->connect()->prepare($sqlRecipesQuery);
        if (!$userEmailStatement->execute([
            'email' => $email,
        ])) {
            $userEmailStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=stmt-failed"));
            //header("Location : ".getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($userEmailStatement->rowCount() == 0) {
            $userEmailStatement = null;
            echo strip_tags("Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !");
            throw new Error((string)header("Location: ".Functions::getUrl()."?pwdreset=selector-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $grabUserEmail = $userEmailStatement->fetchAll();
        return $grabUserEmail;
    }
}