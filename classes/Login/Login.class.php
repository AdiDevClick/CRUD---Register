<?php

class Login extends Mysql
{
    /**
     * Récupère les données d'une table de la base de données en fonction de l'ID de la recette.
     *
     * Cette méthode génère une requête SQL pour obtenir des données à partir de la table spécifiée,
     * utilise une instance de la classe `Database` pour exécuter la requête et retourne les résultats.
     *
     * @param array $params Tableau contenant les paramètres de la requête, y compris les champs et les tables.
     * @param int|string $recipeId L'identifiant de la recette.
     * @param bool $silentMode Mode silencieux pour la récupération des données (par défaut : false).
     * @return array Les données SQL récupérées.
     * @throws Error Si la recette n'existe pas.
     */
    protected function getFromTable(array $params, int|string $recipeId)
    {
        // Option du constructeur
        $options = [
            "fetchAll" => $params["fetchAll"] ?? false,
            "searchMode" => $params["searchMode"] ?? false,
            "silentMode" => $params["silentMode"] ?? false,
            "silentExecute" => $params["silentExecute"] ?? false
        ];
        // Crée une instance de la classe Database avec des données optionnelles
        $Fetch = new Database($options);

        // Génère et exécute la requête SQL pour récupérer les données
        $SQLData = $Fetch->__createGetQuery($params, $recipeId, $this->connect());

        // Retourne les données SQL récupérées
        return $SQLData;
    }

    /**
     * Summary of getUsers
     *
     */
    protected function getUsers(string $email): array
    {
        $params = [
            "fields" => ['*'],
            "table" => ['users'],
            "error" => ["STMTLGNGETUSR - Failed"],
            "where" => [
                'conditions' => [
                    'full_name' => '= :full_name',
                    'email' => '= :email'
                ],
                'logic' => 'OR'
            ],
            "login" => true,
            "silentMode" => true,
        ];


        // $users = $usersStatement->fetchAll(PDO::FETCH_ASSOC);
        $users = $this->getFromTable($params, $email);

        // header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        // http_response_code(404);
        // header("HTTP/1.1 404 Not Found");
        /* $usersStatement = null;
        header("Location : ".Functions::getUrl(). "?error=stmt-failed");
        exit(); */
        // echo json_encode(['status' => 1]);
        return $users;
    }

    /**
     * Summary of getRecipes
     * @return array
     */
    protected function getRecipes(): array
    {
        $sqlRecipesQuery = 'SELECT * FROM `recipes`';
        $recipesStatement = $this->connect()->prepare($sqlRecipesQuery);
        if (!$recipesStatement->execute()) {
            $recipesStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("STMTLGNGETRCP - Failed");
            //header("Location : ".getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($recipesStatement->rowCount() == 0) {
            //$recipesStatement = null;
            $newRecipe = '';
            echo "Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !";
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
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("STMTLGNGETPW - Failed");
            //header("Location : ".Functions::getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($pwdStatement->rowCount() == 0) {
            $pwdStatement = null;
            // CheckInput::insertErrorMessageInArray("STMTLGNGETPWCNT - L'utilisateur n'a pas été trouvé");

            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=user-not-found"));
            throw new Error("STMTLGNGETPWCNT - L'utilisateur n'a pas été trouvé");
            //exit();
        }
        $hashedPwd = $pwdStatement->fetchAll(PDO::FETCH_ASSOC);
        $checkedPwd = password_verify($pwd, $hashedPwd[0]['password']);
        // echo json_encode([$checkedPwd => 'pwcheck']);
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
