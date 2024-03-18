<?php

class Signup extends Mysql
{
    // On vérifie que l'input ne correspond pas à une table de la DB déjà prise
    protected function checkUser(string $email, string $username): bool
    {
        $resultCheck = '';
        $stmt = $this->connect()->prepare(
            'SELECT full_name FROM users 
            WHERE full_name = :full_name OR email = :email;'
        );

        if (!$stmt->execute([
        'email' => $email,
        'full_name' => $username])) {
            $stmt = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmtfailed"));
            throw new Error("stmt Failed");
            //exit();
        }
        if ($stmt ->rowCount() > 0) {
            $resultCheck = false;
            $stmt = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=user-already-exists"));
            throw new Error("Cet utilisateur existe déjà");
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
                'full_name' => $nom,
                'email' => $email,
                'password' => $hashedPwd,
                'age' => $age
            ])) {
                $insertUsers = null;
                //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmtfailed"));
                throw new Error("stmt Failed");
                //header('Location : ../register.php?error=stmtfailed');
                //exit();
            }
            $insertUsers = null;
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas...') ;
        }
    }

    /* public function signupUser($nom, $email, $password, $age)
    {
        try {
            $data = $_POST;
            $checkInput = new CheckInput(
                $data
            );
            if ($checkInput->checkInputs()) {
                throw new Error("Erreur : On n'a pas pu check les inputs") ;
            } else {
                $this->insertUser($nom, $email, $password, $age);
            }
        } catch (Error $e) {
            die('Erreur : '. $e->getMessage() . ' Insertion dans la DB impossible') ;
        }
    }  */

    /* public function signupUser($nom, $email, $password, $age)
     {
             if (!isset($_POST['submit'])) {
                 throw new Error("Erreur : On n'a pas pu check les inputs") ;
             } else {
                 $this->insertUser($nom, $email, $password, $age);
             }
     }  */
}

/*  public function __construct(
     private string $nom,
     private string $email,
     private string $password,
     private string $age
 ) {
 }
private function checkUser(string $email, string $full_name) : bool {
 $resultCheck = (bool)
 $stmt = $this->connect()->prepare('SELECT full_name FROM users WHERE full_name = :full_name OR email = :email;');
     if (!$stmt->execute([
     'email' => $email,
     'full_name' => $full_name])) {
         $stmt = null;
         header('Location : ../index.php?error=stmtfailed');
         exit();
     }
     if ($stmt ->rowCount() > 0) {
         $resultCheck = false;
     } else {
         $resultCheck = true;
     }
 return $resultCheck;
 } */
