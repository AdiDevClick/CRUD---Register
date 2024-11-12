<?php

class Signup extends Mysql
{
    // On vérifie que l'input ne correspond pas à une table de la DB déjà prise
    protected function checkUser(string $email, string $username): bool
    {
        $loweredUsername = strtolower($username);
        $resultCheck = '';
        $stmt = $this->connect()->prepare(
            'SELECT full_name, email FROM users 
            WHERE full_name = :full_name OR email = :email;'
        );

        if (!$stmt->execute([
        'email' => $email,
        'full_name' => $loweredUsername])) {
            $stmt = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmtfailed"));
            throw new Error("STMTSGNEXEDBCH - Failed");
            //exit();
        }
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stmt ->rowCount() > 0) {
            $resultCheck = false;
            $stmt = null;

            if ($users[0]["full_name"] === $loweredUsername) {
                CheckInput::insertErrorMessageInArray('STMTSGNDBCHCNT - Cet utilisateur existe déjà');
                // throw new Error("STMTSGNDBCHCNT - Cet utilisateur existe déjà");
            }
            if ($users[0]["email"] === $email) {
                CheckInput::insertErrorMessageInArray('STMTSGNDBCHCNTEM - Cet email existe déjà');
                // throw new Error("STMTSGNDBCHCNTEM - Cet email existe déjà");
                //throw new Error((string)header("Location: ".Functions::getUrl()."?error=user-already-exists"));
            }
        } else {
            $resultCheck = true;
        }
        return $resultCheck;
    }

    protected function insertUser(string $nom, string $email, string $password, int $age)
    {
        try {
            $sqlQuery =
            'INSERT INTO users (full_name, email, password, age) 
            VALUES (:full_name, :email, :password, :age);';

            $insertUsers = $this->connect()->prepare($sqlQuery);
            $options = [
                'cost' => 12
            ];

            $hashedPwd = password_hash($password, PASSWORD_DEFAULT, $options);

            /*  $insertUsers->execute([
                 'full_name' => $nom,
                 'email' => $email,
                 'password' => $hashedPwd,
                 'age' => $age
             ]); */
            if (!$insertUsers->execute([
                'full_name' => strtolower($nom),
                'email' => $email,
                'password' => $hashedPwd,
                'age' => $age
            ])) {
                $insertUsers = null;
                //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmtfailed"));
                throw new Error("STMTSGNINSRTUSR - Failed");
                //header('Location : ../register.php?error=stmtfailed');
                //exit();
            }
            $insertUsers = null;
            // exit;
        } catch (Error $e) {
            CheckInput::insertErrorMessageInArray($e->getMessage());
            // die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas...') ;
        }
    }
}
