<?php

class Login extends Mysql
{
    /**
     * Summary of getUsers
     * @param mixed $email
     * @return array
     */
    protected function getUsers(string $email) : array
    {
        $sqlUsersQuery =
        'SELECT * FROM `users`
        WHERE full_name = :full_name OR email = :email;';
        /* 'SELECT * FROM `users`;'; */

        $usersStatement = $this->connect()->prepare($sqlUsersQuery);

        if (!$usersStatement->execute([
            'email' => $email,
            'full_name' => $email
        ])) {
            $usersStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            //header("Location : ".$url->getThisUrl(). "?error=user-not-found");
        }
        if ($usersStatement->rowCount() == 0) {
            $usersStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=user-not-found"));
            //header("Location : ".$url->getThisUrl()."?error=user-not-found");
            //exit();
        } //else {
        $users = $usersStatement->fetchAll(PDO::FETCH_ASSOC);
            /* $usersStatement = null;
            header("Location : ".Functions::getUrl(). "?error=stmt-failed");
            exit(); */       
        return $users;
    }

    /**
     * Summary of getRecipes
     * @return array
     */
    protected function getRecipes() : array
    {
        $sqlRecipesQuery = 'SELECT * FROM `recipes`';
        $recipesStatement = $this->connect()->prepare($sqlRecipesQuery);
        if (!$recipesStatement->execute()) {
            $recipesStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            //header("Location : ".getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($recipesStatement->rowCount() == 0) {
            //$recipesStatement = null;
            $newRecipe = '';
            Echo "Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !";
            $newRecipe = '<div class="">';
            $newRecipe .= '<p><a href="recipes/create_recipes.php">Cliquez ici</a> pour créer votre recette !</p>';
            echo $newRecipe;

            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipe-not-found")); 
            //header("Location : ".Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipes = $recipesStatement->fetchAll();
        return $recipes;
    }

    /**********
     * Vérifier le pw 
     ****                                      ***********/
    protected function getPwd(string $pwd, string $email): bool
    {
        $sqlUsersQuery =
        'SELECT `password` FROM `users` 
        WHERE full_name = :full_name OR email = :email;';
        $pwdStatement = $this->connect()->prepare($sqlUsersQuery);
        if (!$pwdStatement->execute([
            'full_name' => $email,
            'email' => $email
        ])) {
            $pwdStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            //header("Location : ".Functions::getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($pwdStatement->rowCount() == 0) {
            $pwdStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=user-not-found"));
            //exit();
        }
        $hashedPwd = $pwdStatement->fetchAll(PDO::FETCH_ASSOC);
        $checkedPwd = password_verify($pwd, $hashedPwd[0]['password']);
        return $checkedPwd;
    }

    /* protected function login(string $pwd, string $email)
    {

        foreach ($this->getUsers($email) as $user) {
            try {
                if (($this->getPwd($pwd, $email) &&
                (($user['email'] === $email) ||
                ($user['full_name'] === $email)))
                //password_verify($pwd, $user['password'])) {
                ) {
                    $loggedUser = [
                        'email' => $user['email'],
                         //'username' => $user['full_name'],
                        ];
                    header('Location: index.php');
                    //session_start();
                    setcookie(
                        'LOGGED_USER',
                        $user['email'],
                        [
                            'expires' => time() + 365 * 24 * 3600,
                            'secure' => true,
                            'httponly' => true,
                        ]
                    );
                    
                    $_SESSION['USER_ID'] = $user['user_id'];
                    $_SESSION['LOGGED_USER'] = $user['full_name'];

                    return $loggedUser;
                } else {
                    $errorMessage = sprintf(
                        'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                        $email,
                        $pwd
                    );
                    //throw new Error((string)header("Location: ".$this->getUrl(). "?error=pwd-does-not-match"));
                    //throw new Error($errorMessage);
                    echo $errorMessage;
                    //header("Location: ".Functions::getUrl()."?error=pwd-does-not-match");
                }
            } catch (Error $e) {
                die('Erreur de login : '. $e->getMessage());
            }
        }
    } */
}

/* protected function login(string $pwd, string $email)
    {
        try {
            //$users = $this->getUsers($email);
            if ($this->getPwd($pwd, $email) &&
            (($this->getUsers($email)[0]['email'] === $email) ||
                ($this->getUsers($email)[0]['full_name'] === $email))) {
                //password_verify($pwd, $users[0]['password'])) {

                $loggedUser = [
                'email' => $this->getUsers($email)[0]['email'],
                 //'username' => $user['full_name'],
                ];

                header('Location: index.php');
                //session_start();
                setcookie(
                    'LOGGED_USER',
                    $this->getUsers($email)[0]['email'],
                    [
                        'expires' => time() + 365 * 24 * 3600,
                        'secure' => true,
                        'httponly' => true,
                    ]
                );
                $_SESSION['USER_ID'] = $this->getUsers($email)[0]['user_id'];
                $_SESSION['LOGGED_USER'] = $this->getUsers($email)[0]['full_name'];

                return $loggedUser;
            } else {
                $errorMessage = sprintf(
                    'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                    $email,
                    $pwd
                );
                //throw new Error((string)header("Location: ".$this->getUrl(). "?error=pwd-does-not-match"));
                //throw new Error($errorMessage);
                echo $errorMessage;
                //header("Location: ".Functions::getUrl()."?error=pwd-does-not-match");
            }
        } catch (Error $e) {
            die('Erreur de login : '. $e->getMessage());
        }
    } */

    /*  public function getPassword() {
        $sqlUsersQuery = 'SELECT password FROM `users`';
        $usersStatement = $this->connect()->prepare($sqlUsersQuery);
        $usersStatement->execute();
        $password = $usersStatement->fetchAll(PDO::FETCH_ASSOC);
        return $password;
    }*/