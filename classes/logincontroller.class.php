<?php

class LoginController extends Login
{
    /* public function __construct(
        private string $password,
        private string $username,
        private $data,
        private $getData)
        { */
    public function __construct(
        private $getDatas
    ) {
        //
    }

    /**
     * Summary of index
     * @return void
     */
    protected function index(): void
    {
        if (!isset($this->getDatas)) {
            throw new Error("Nous ne pouvons pas tester vos identifiants...".
            header("Location: ".Functions::getUrl(). "?error=missing-ids"));
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
            $checkInput->checkInputs();

            $this->login($password, $username);
        }
    }

    private function login(string $pwd, string $email)
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
                throw new Error($errorMessage .
                header("Location: ".Functions::getUrl()."?error=pwd-or-id-does-not-match"));
                //throw new Error($errorMessage);
                //echo $errorMessage;
                //header("Location: ".Functions::getUrl()."?error=pwd-does-not-match");
            }
        } catch (Error $e) {
            die('Erreur de login : '. $e->getMessage());
        }
    }

    protected function showUsers($pwd, $email)
    {
        $this->login($pwd, $email);
    }

    protected function fetchRecipes(): array
    {
        $result = $this->getRecipes();
        return $result;
    }

    protected function fetchUsers($email): array
    {
        $result = $this->getUsers($email);
        return $result;
    }

    /***
     * Check for a set cookie and return the user
     */
    public static function checkLoggedStatus()
    {
        $userState = '';
        $state='';
            switch (isset($_COOKIE) || isset($_SESSION)) {
                
                case 'REGISTERED_RECIPE':
                    if(isset($_COOKIE['REGISTERED_RECIPE']) || isset($_SESSION['REGISTERED_RECIPE'])) {
                        $userState = [
                        'recipe' => $_COOKIE['REGISTERED_RECIPE'] ?? $_SESSION['REGISTERED_RECIPE']
                                        ];
                        $state = 'REGISTERED_RECIPE';
                        echo 'on a la recipe';
                    return $userState; 
                    
                    }
                case 'LOGGED_USER':
                    if(isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
                        $userState = [
                        'email' => $_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER']
                                        ];
                        $state = 'LOGGED_USER';
                        echo 'on a le logged';
                    break;
                    }    
                case 'REGISTERED_USER':
                    if(isset($_COOKIE['REGISTERED_USER']) || isset($_SESSION['REGISTERED_USER'])) {
                        $userState = [
                        'user' => $_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER']
                                        ];
                        $state = 'REGISTERED_USER';
                        echo "on a l'user";
                    break;
                    }
                default :
                break;
            } 
    }

    /* public static function checkLoggedStatus()
    {
        try {
            if(isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
                $loggedUser = [
                'email' => $_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER'],
                        ];
                return $loggedUser;
            }
            if (isset($_COOKIE['REGISTERED_RECIPE']) || isset($_SESSION['REGISTERED_RECIPE'])) {
                $registeredRecipe = [
                    'email' => $_COOKIE['REGISTERED_RECIPE'] ?? $_SESSION['REGISTERED_RECIPE'],
                ];
                return $registeredRecipe;
            }
            if (isset($_COOKIE['REGISTERED_USER']) || isset($_SESSION['REGISTERED_USER'])) {
                $registeredUser = [
                    'email' => $_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER'],
                ];
                return $registeredUser;
            } else {
                //throw new Error("Veuillez vous identifier pour ajouter une recette" . header('Location:'.Functions::getUrl().'?error=no-loggedin'));
                //throw new Error("Veuillez vous identifier pour ajouter une recette");
                echo strip_tags('Veuillez vous identifier');
            }
        } catch (Error $e) {
            die("Erreur : ". $e->getMessage() . header('refresh:5,../index.php?error=not-loggedin'));
        }
    } */
}

/* protected function login(string $pwd, string $email)
{
    foreach ($this->getUsers($email) as $user) {
        try {
            /* if //(($this->getPwd($pwd, $email) &&
            ((($user['email'] === $email) ||
            ($user['full_name'] === $email)) &&
            password_verify($pwd, $user['password'])) { */


/*if (($this->getPwd($pwd, $email) &&
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
