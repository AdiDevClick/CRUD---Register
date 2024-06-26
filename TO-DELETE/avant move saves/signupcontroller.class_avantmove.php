<?php

class SignupController extends Signup
{
    public function __construct(
        /* private $nom,
        private $email,
        private $password,
        private $pwdRepeat,
        private $age, */
        private $getData
    ) {
    }

    protected function signupUsers()
    {
        try {
            if  ($this->emailTaken() || !$this->pwMatch() || !isset($this->getData)) {
                //if  (!$this->pwMatch()) {
                throw new Error("Erreur : On n'a pas pu check les inputs") ;
            } else {
                $checkInput = new CheckInput(
                    $this->getData
                );
                $checkInput->checkInputs();

                if (empty($checkInput->getErrorsArray())) {
                    die("ok");
                    $this->insertUser($this->getData['username'], $this->getData['email'], $this->getData['password'], $this->getData['age']);
                    echo("ok");
                } else {
                    // $checkInput->getErrorsArray();
                    echo('je suis pas ok');
                    $checkInput->getErrorsArray();
                    // die('test');
                }
                //$this->insertUser($this->nom, $this->email, $this->password, $this->age);
                // $this->insertUser($this->getData['username'], $this->getData['email'], $this->getData['password'], $this->getData['age']);
                $registeredUser = [
                            'email' => $this->getData['email'],
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
            die('Erreur : '. $e->getMessage() . ' , Insertion de l\'utilisateur dans la DB impossible') ;
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

    private function emailTaken(): bool
    {
        $resultCheck = '' ;
        if (!$this->checkUser($this->getData['email'], $this->getData['username'])) {
            $resultCheck = true;
        } else {
            $resultCheck = false;
        }
        return $resultCheck;
    }

    private function pwMatch(): bool
    {
        $resultCheck = '' ;
        if ($this->getData['password'] != $this->getData['pwdRepeat']) {
            $resultCheck = false;
            throw new Error("Erreur : Les mots de passes ne sont pas identiques" .
                header("Location: ".Functions::getUrl()."?error=pwd-doesnt-match")) ;
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
