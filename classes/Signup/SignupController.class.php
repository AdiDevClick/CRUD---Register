<?php

// require_once("templates/toaster_template.html");

class SignupController extends Signup
{
    public function __construct(
        protected $getDatas
    ) {
        //
    }

    protected function signupUsers()
    {
        try {
            $checkInput = new CheckInput(
                $this->getDatas
            );
            $username = $checkInput->test_input($this->getDatas["username"]);
            $email = $checkInput->test_input($this->getDatas["email"]);
            $password = $checkInput->test_input($this->getDatas["password"]);
            $repeatPassword = $checkInput->test_input($this->getDatas["pwdRepeat"]);
            $age = $checkInput->test_input($this->getDatas["age"]);

            $checkInput->checkInputs();

            if  ($this->emailTaken($email, $username) || !$this->pwMatch($password, $repeatPassword) || !isset($this->getDatas)) {
                //if  (!$this->pwMatch()) {
                CheckInput::insertErrorMessageInArray("SGNTKN : On n'a pas pu check les inputs");
                // throw new Error("SGNTKN : On n'a pas pu check les inputs") ;
                return;
            } else {
                // $checkInput = new CheckInput(
                //     $this->getDatas
                // );
                // $checkInput->checkInputs();

                if (empty($checkInput->getErrorsArray())) {
                    $this->insertUser($username, $email, $password, $age);
                    // $this->insertUser($this->getDatas['username'], $this->getDatas['email'], $this->getDatas['password'], $this->getDatas['age']);
                } else {
                    $checkInput->getErrorsArray();
                    return;
                    // die('test');
                }
                //$this->insertUser($this->nom, $this->email, $this->password, $this->age);
                // $this->insertUser($this->getData['username'], $this->getData['email'], $this->getData['password'], $this->getData['age']);
                $registeredUser = [
                            'email' => $this->getDatas['email'],
                            //'username' => $user['full_name'],
                            ];
                /* setcookie(
                            'REGISTERED_USER',
                            $this->getData['email'],
                            [
                                'expires' => time() + 0 * 0 * 10,
                                'secure' => true,
                                'httponly' => true,
                            ]
                );  */
                //session_start();
                $_SESSION['REGISTERED_USER'] = $registeredUser;
                //header("Location: ".Functions::getUrl()."?error=none") ; */
                return $registeredUser;
            }
            /* if ($this->emailTaken()) {
                $registeredUser = [
                'email' => $this->getData['email'],
                //'username' => $user['full_name'],
                ];

                //header('Location: index.php');
                //session_start();

            } */

            //$db = null;
        } catch (Error $e) {
            CheckInput::insertErrorMessageInArray($e->getMessage());
            // $errormsg = CheckInput::getErrorsArray();
            // print_r($errormsg);
            // header("Location: ".Functions::getUrl()."?error=user-taken");
            // return;
            // die('Erreur : '. $e->getMessage() . ' , Insertion de l\'utilisateur dans la DB impossible') ;
        }
    }

    /*  private function emailTaken(): bool
     {
         $resultCheck = '' ;
         if (!$this->checkUser($this->email, $this->nom)) {
             $resultCheck = false;
         } else {
             $resultCheck = true;
         }
         return $resultCheck;
     } */

    private function emailTaken($email, $username): bool
    {
        $resultCheck = '' ;
        if (!$this->checkUser($email, $username)) {
            // if (!$this->checkUser($this->getDatas['email'], $this->getDatas['username'])) {
            $resultCheck = true;
        } else {
            $resultCheck = false;
        }
        return $resultCheck;
    }

    private function pwMatch($password, $repeatPassword): bool
    {
        $resultCheck = '' ;
        if ($password !== $repeatPassword) {
            // if ($this->getDatas['password'] !== $this->getDatas['pwdRepeat']) {
            $resultCheck = false;
            CheckInput::insertErrorMessageInArray('SGNPWM : Les mots de passes ne sont pas identiques');
            // new Error("SGNPWM : Les mots de passes ne sont pas identiques");
            // throw new Error("SGNPWM : Les mots de passes ne sont pas identiques");
            //throw new Error("Erreur : Les mots de passes ne sont pas identiques" .
            //header("Location: ".Functions::getUrl()."?error=pwd-doesnt-match")) ;
        } else {
            $resultCheck = true;
        }
        return $resultCheck;
    }

    /* private function pwMatch(): bool
    {
        $resultCheck = '' ;
        if ($this->password != $this->pwdRepeat) {
            $resultCheck = false;
            throw new Error("Erreur : Les mots de passes ne sont pas identiques" .
                header("Location: ".Functions::getUrl()."?error=pwd-doesnt-match")) ;
        } else {
            $resultCheck = true;
        }
        return $resultCheck;
    } */
}
/*  private function uidTaken(): bool
 {
     (bool)$resultCheck = '' ;
     if (!$this->checkUser($this->email, $this->nom)) {
         $resultCheck = false;
     } else {
         $resultCheck = true;
     }
     return $resultCheck;
 } */
