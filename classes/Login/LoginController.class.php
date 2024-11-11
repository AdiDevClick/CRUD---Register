<?php

// require_once("templates/toaster_template.html");

class LoginController extends Login
{
    public function __construct(
        private $getDatas
    ) {
        //
    }

    /**
     * Vérifie les données reçues et les assainit avant de pouvoir les envoyer
     * à la fonction main (->login)
     *
     * @return
     */
    protected function index()
    {
        try {
            if (!isset($this->getDatas)) {
                throw new Error("Nous ne pouvons pas tester vos identifiants...");
            } else {
                $checkInput = new CheckInput($this->getDatas);
                $sanitized_Datas = $checkInput->sanitizeData();

                // $password = $checkInput->test_input($this->getDatas["password"]);
                // $username = $checkInput->test_input($this->getDatas["username"]);
                $password = $sanitized_Datas["password"];
                $username = $sanitized_Datas["username"];

                // die(var_dump($username, $password, $_SESSION));
                // $validInputs = $checkInput->checkInputs();
                // $checkInput->checkInputs();
                // if (empty($checkInput->getErrorsArray())) {
                if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                    unset($_SESSION['SANITIZED']);
                    $this->login($password, $username);
                } else {
                    throw new Error($_SESSION['SANITIZED'][0]);
                }
                return;
            }
        } catch (\Throwable $e) {
            // null;
            // die('Erreur : ' . $e->getMessage() . " Une erreur est survenue dans la connexion");

            CheckInput::insertErrorMessageInArray($e->getMessage());
        }
    }

    /**
     * Main fonction -
     * Vérifie les données de la DB et crer
     * les cookies et données sessions nécéssaires -
     * Renvoie une exception qui sera catch et envoyée dans l'array d'erreur global
     *
     * @param string $pwd
     * @param string $email
     * @return void
     */
    protected function login(string $pwd, string $email)
    {
        // die(var_dump($users));
        // die(var_dump($user));
        // echo($this->getUsers($email)['email']);
        // $userEmail = $this->getUsers($email)[0]['email'] ?? null;
        // $username = $this->getUsers($email)[0]['full_name'] ?? null;
        // $userID = $this->getUsers($email)[0]['user_id'] ?? null;
        // $userEmail = $this->getUsers($email)['email'] ?? null;
        // $username = $this->getUsers($email)['full_name'] ?? null;
        // $userID = $this->getUsers($email)['user_id'] ?? null;


        // $script = <<< JS
        // include_once("templates/toaster_template.html");
        // // require_once("scripts/toaster.js");
        // import { alertMessage } from "./scripts/toaster.js"
        // function() {
        //     const alert = alertMessage($errorMessage)
        //     const alertContainer = document.querySelector('.toast-container')
        //     alertContainer.insertAdjacentElement(
        //         'beforeend',
        //         alert
        //     )
        //     console.log('object3')
        // }
        // JS;
        // $script2 = <<< JS

        //     // import { alertMessage } from "./scripts/toaster.js"

        //     console.log('object4')
        //     const form = document.querySelector('form')
        //     form.addEventListener('submit', e => {
        //         e.preventDefault()
        //         console.log('object2')
        //         // $script
        //         // this.#onSubmit(e.currentTarget)
        //         form.removeEventListener('submit', e)
        //     })

        // JS;
        try {

            if (empty(CheckInput::getErrorsArray())) {
                $user = $this->getUsers($email);

                $userEmail = $user['email'] ?? null;
                $username = $user['full_name'] ?? null;
                $userID = $user['user_id'] ?? null;
            } else {
                // CheckInput::insertErrorMessageInArray($_SESSION['SANITIZED'][0]);
                throw new Error("LGNGETPWSNTZ - Mot de passe et/ou Login incorrects");
            }
            if ($this->getPwd($pwd, $email) &&
                (($userEmail === $email) ||
                ($username === strtolower($email)))) {
                // (($this->getUsers($email)[0]['email'] === $email) ||
                // ($this->getUsers($email)[0]['full_name'] === $email))) {
                //password_verify($pwd, $users[0]['password'])) {

                if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $this->setCookies($userEmail, $username, $userID);

                $_SESSION['USER_NAME'] = $username;
                $_SESSION['USER_ID'] = $userID;
                $_SESSION['LOGGED_USER'] = [
                    $username,
                    $userID,
                    $userEmail
                ];
                // $_SESSION['USER_NAME'] = $this->getUsers($email)[0]['full_name'];
                // $_SESSION['USER_ID'] = $this->getUsers($email)[0]['user_id'];
                // $_SESSION['LOGGED_USER'] = [
                //     $this->getUsers($email)[0]['full_name'],
                //     $this->getUsers($email)[0]['user_id'],
                //     $this->getUsers($email)[0]['email']
                // ];
            } else {
                // $errorMessage = sprintf(
                //     'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                //     $email,
                //     $pwd
                // );
                throw new Error("LGNGETPW - Mot de passe et/ou Login incorrects");
            }
        } catch (Error $errorMessage) {
            // die("". $errorMessage->getMessage());
            CheckInput::insertErrorMessageInArray($errorMessage->getMessage());
        }
    }

    /**
     * Crer les cookies nécessaires au fonctionnement du site
     * Date d'expiration : 1 an à compté de la création
     *
     * @param string $email
     * @param string $username
     * @param int $userID
     * @return void
     */
    protected function setCookies(string $email, string $username, int $userID)
    {
        $arrCookiesOptions = [
            'expires' => time() + 365 * 24 * 3600,
            // 'secure' => false,
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ];
        setcookie('FULLNAME', $username, $arrCookiesOptions);
        setcookie('USER_ID', $userID, $arrCookiesOptions);
        setcookie('EMAIL', $email, $arrCookiesOptions);
    }

    protected function showUsers($pwd, $email)
    {
        $this->login($pwd, $email);
    }

    protected function fetchRecipes(): array
    {
        return $this->getRecipes();
    }

    protected function fetchUsers($email): array
    {
        return $this->getUsers($email);
    }

    /***
     * Check for a set cookie and return the user
     */
    /* public static function checkLoggedStatus()
    {
        $userState = '';
        $state = '';
        switch (isset($_COOKIE[$state]) || isset($_SESSION[$state])) {

            case $state = 'REGISTERED_RECIPE':
                if(isset($_COOKIE['REGISTERED_RECIPE']) || isset($_SESSION['REGISTERED_RECIPE'])) {
                    $userState = [
                    'recipe' => $_COOKIE['REGISTERED_RECIPE'] ?? $_SESSION['REGISTERED_RECIPE']
                                    ];
                    echo 'on a la recipe';
                    return $userState;

                }
                // no break
            case $state = 'LOGGED_USER':
                if(isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
                    $userState = [
                    'email' => $_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER']
                                    ];
                    $state = 'LOGGED_USER';
                    echo 'on a le logged';
                    return $userState;
                }
                // no break
            case $state = 'REGISTERED_USER':
                if(isset($_COOKIE['REGISTERED_USER']) || isset($_SESSION['REGISTERED_USER'])) {
                    $userState = [
                    'user' => $_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER']
                                    ];
                    $state = 'REGISTERED_USER';
                    echo "on a l'user";
                    return $userState;
                }
                // no break
            default:
                echo "default state";
                break;
        }
    } */

    /* public static function status()
    {
        $states = [
            'REGISTERED_USER' => ($_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER']),
            'REGISTERED_RECIPE' => ($_COOKIE['REGISTERED_RECIPE'] ?? $_SESSION['REGISTERED_RECIPE']),
            'LOGGED_USER' => ($_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER'])
        ];
        foreach ($states as $state => $param) {
            if (isset($_COOKIE[$state]) || isset($_SESSION[$state])) {
                echo $param;
                return $param;
            }
        }
    } */


    /**
     * Permet de créer un array contenant
     * toutes les informations des cookies -
     * Les données de sessions seront multidimentionnelles -
     * Les cookies sont essentiels pour le fonctionnement du site
     * Les données sessions ne sont là que pour vérification
     * mais ne sont pas utilisées pleinement
     *
     * @return array
     */
    public static function checkLoggedStatus()
    {
        try {
            $loggedUser = [];
            // if(isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
            //     $loggedUser = [
            //     'email' => $_COOKIE['LOGGED_USER'],
            //     'user' => $_SESSION['LOGGED_USER']
            //     ];
            //     //return $loggedUser;
            // }


            // if(isset($_COOKIE['EMAIL'])) {
            //     // $this->postDatas = $_COOKIE['LOGGED_USER'];
            //     // print($_COOKIE['EMAIL']);
            //     // echo'voici le cookie => '. $_COOKIE['EMAIL'] . '<==';
            //     $loggedUser['email'] = $_COOKIE['EMAIL'];
            //     // return $loggedUser;
            // }
            // if(isset($_COOKIE['FULLNAME'])) {
            //     // $this->postDatas = $_COOKIE['LOGGED_USER'];
            //     // print($_COOKIE['EMAIL']);
            //     // echo'voici le cookie => '. $_COOKIE['EMAIL'] . '<==';
            //     $loggedUser['name'] = $_COOKIE['FULLNAME'];
            //     // return $loggedUser;
            // }
            // if(isset($_SESSION['LOGGED_USER'])) {
            //     // echo'voici le session user => '. $_SESSION['LOGGED_USER'][0] . '<==';
            //     $loggedUser['user'] = $_SESSION['LOGGED_USER'];
            //     // echo('registered');
            //     // return $loggedUser;
            // }


            if (isset($_COOKIE['EMAIL'])) {
                $loggedUser['email'] = $_COOKIE['EMAIL'];
            }
            if (isset($_COOKIE['FULLNAME'])) {
                $loggedUser['name'] = $_COOKIE['FULLNAME'];
            }
            if (isset($_COOKIE['USER_ID'])) {
                $loggedUser['userId'] = $_COOKIE['USER_ID'];
            }
            if (isset($_SESSION['LOGGED_USER'])) {
                // echo'voici le session user => '. $_SESSION['LOGGED_USER'][0] . '<==';
                $loggedUser['user'] = $_SESSION['LOGGED_USER'];
            }
            if (isset($_COOKIE['REGISTERED_RECIPE']) || isset($_SESSION['REGISTERED_RECIPE'])) {
                $loggedUser['recipe'] = $_COOKIE['REGISTERED_RECIPE'] ?? $_SESSION['REGISTERED_RECIPE'];
            }
            if (isset($_COOKIE['REGISTERED_USER']) || isset($_SESSION['REGISTERED_USER'])) {
                $loggedUser['registeredUser'] = $_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER'];
            } else {
                //throw new Error("Veuillez vous identifier pour ajouter une recette" . header('Location:'.Functions::getUrl().'?error=no-loggedin'));
                //throw new Error("Veuillez vous identifier pour ajouter une recette");
                $errorMessage = sprintf(
                    'Veuillez vous identifier'
                );
                //echo strip_tags('Veuillez vous identifier');
            }
            // echo ($loggedUser);
            // print_r($loggedUser);
            return $loggedUser;

        } catch (Error $errorMessage) {
            die("Erreur : ". $errorMessage->getMessage() . header('refresh:5,../index.php?error=not-loggedin'));
        }
    }
}
