<?php

class LoginController extends Login
{
    public function __construct(
        private string $password, 
        private string $username,
        private $data,
        private $getData)
        {
            //
    } 

    /**
     * Summary of index
     * @return void
     */
    protected function index(): void
    {
        if (!$this->data && isset($this->getData)) {
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
                $this->getData
            );
            $password = $checkInput->test_input($this->password);
            $username = $checkInput->test_input($this->username);
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
}