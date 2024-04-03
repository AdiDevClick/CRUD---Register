<?php

require_once("templates/toaster_template.html");

class LoginController extends Login
{
    /* public function __construct(
        private string $password,
        private string $username,
        private $data,
        private $getData)
        { */
    // public array $postDatas;

    public function __construct(
        private $getDatas
    ) {
        //
    }

    /**
     * Summary of index
     */
    protected function index(): void
    {

        if (!isset($this->getDatas)) {
            throw new Error("Nous ne pouvons pas tester vos identifiants...");
            //throw new Error("Nous ne pouvons pas tester vos identifiants...".
            //header("Location: ".Functions::getUrl(). "?error=missing-ids"));
            /* $this->username = '';
            $this->password = ''; */

            //require_once("includes/class-autoloader.inc.php");

            //$getData = $_POST;

            /* $checkInput = new CheckInput(
                $getData
            ); */
            //require_once("includes/class-autoloader.inc.php");


            /* $this->password = $checkInput->test_input($getData['password']);
            $this->username = $checkInput->test_input($getData['username']); */



            /* $errorUsername = "Nom d'utilisateur";
            $errorPassword = 'Mot de passe'; */


            //$this->login($this->password, $this->username);
        } else {
            $checkInput = new CheckInput(
                $this->getDatas
            );
            $password = $checkInput->test_input($this->getDatas["password"]);
            $username = $checkInput->test_input($this->getDatas["username"]);
            // $validInputs = $checkInput->checkInputs();
            $checkInput->checkInputs();

            if (empty($checkInput->getErrorsArray())) {
                $this->login($password, $username);
            } else {
                // $checkInput->getErrorsArray();
                $checkInput->getErrorsArray();
                // die('test');
                return;
            }
        }
    }

    protected function login(string $pwd, string $email)
    {

        if (empty(CheckInput::getErrorsArray())) {
            // die('je peux continuer');
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
                //$users = $this->getUsers($email);
                if ($this->getPwd($pwd, $email) &&
                (($this->getUsers($email)[0]['email'] === $email) ||
                    ($this->getUsers($email)[0]['full_name'] === $email))) {
                    //password_verify($pwd, $users[0]['password'])) {

                    // $loggedUser = [
                    // 'email' => $this->getUsers($email)[0]['email'],
                    // 'username' => $this->getUsers($email)[0]['full_name'],
                    // 'user_id' => $this->getUsers($email)[0]['user_id']
                    // ];

                    //header('Location: index.php');
                    session_start();

                    $arrCookiesOptions = [
                        'expires' => time() + 365 * 24 * 3600,
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ];
                    setcookie('EMAIL', $this->getUsers($email)[0]['email'], $arrCookiesOptions);
                    // setcookie('EMAIL', $this->getUsers($email)[0]['email'], $arrCookiesOptions);
                    // setcookie('LOGGED_USER[0]', $this->getUsers($email)[0]['email'], $arrCookiesOptions);
                    setcookie('USER_ID', $this->getUsers($email)[0]['user_id'], $arrCookiesOptions);
                    // setcookie('LOGGED_USER[1]', $this->getUsers($email)[0]['user_id'], $arrCookiesOptions);
                    setcookie('FULLNAME', $this->getUsers($email)[0]['full_name'], $arrCookiesOptions);
                    // setcookie('LOGGED_USER[2]', $this->getUsers($email)[0]['full_name'], $arrCookiesOptions);
                    // setcookie(
                    //     'LOGGED_USER',
                    //     $this->getUsers($email)[0]['email'],
                    //     [
                    //         'expires' => time() + 365 * 24 * 3600,
                    //         'secure' => true,
                    //         'httponly' => true,
                    //     ]
                    // );
                    //session_start();
                    $_SESSION['USER_NAME'] = $this->getUsers($email)[0]['full_name'];
                    $_SESSION['USER_ID'] = $this->getUsers($email)[0]['user_id'];
                    $_SESSION['LOGGED_USER'] = [
                        $this->getUsers($email)[0]['full_name'],
                        $this->getUsers($email)[0]['user_id'],
                        $this->getUsers($email)[0]['email']
                    ];

                    // return $loggedUser;
                } else {
                    $errorMessage = sprintf(
                        'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                        $email,
                        $pwd
                    );
                    // throw new Error($errorMessage.header("Location: ".Functions::getUrl()."?error=pwd-or-id-does-not-match"));
                    throw new Error($errorMessage);
                    //echo $errorMessage;
                    //header("Location: ".Functions::getUrl()."?error=pwd-does-not-match");
                }
            } catch (Error $errorMessage) {
                // echo $errorMessage->getMessage();
                // exit($errorMessage->getMessage());
                // exit($errorMessage);
                // $script2;
                // die('Erreur de login : '. $errorMessage->getMessage());
                echo('Erreur de login : '. $errorMessage->getMessage());

            }
        }
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
     * Appends an array inside $loggedUser like this : $loggedUser['user'][1]
     * Used in Comment page mainly to retrieve the user ID
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


            if(isset($_COOKIE['EMAIL'])) {
                $loggedUser['email'] = $_COOKIE['EMAIL'];
            }
            if (isset($_COOKIE['FULLNAME'])) {
                $loggedUser['name'] = $_COOKIE['FULLNAME'];
            }
            if (isset($_COOKIE['USER_ID'])) {
                $loggedUser['userId'] = $_COOKIE['USER_ID'];
            }
            if  (isset($_SESSION['LOGGED_USER'])) {
                // echo'voici le session user => '. $_SESSION['LOGGED_USER'][0] . '<==';
                $loggedUser['user'] = $_SESSION['LOGGED_USER'];
                // echo('registered');
                // return $loggedUser;
            }
            if (isset($_COOKIE['REGISTERED_RECIPE']) || isset($_SESSION['REGISTERED_RECIPE'])) {
                $loggedUser = [
                    'email' => $_COOKIE['REGISTERED_RECIPE'] ?? $_SESSION['REGISTERED_RECIPE'],
                ];
                //return $loggedUser;
            }
            if (isset($_COOKIE['REGISTERED_USER']) || isset($_SESSION['REGISTERED_USER'])) {
                $loggedUser = [
                    'email' => $_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER'],
                ];
                //return $loggedUser;
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
        } catch (Error $errorMessage) {
            die("Erreur : ". $errorMessage->getMessage() . header('refresh:5,../index.php?error=not-loggedin'));
        }
        return $loggedUser;
    }
}
