<?php

class SignupController extends Signup
{
    public function __construct(
        private $nom,
        private $email,
        private $password,
        private $pwdRepeat,
        private $age,
        private $getData
    ) {
    }

    public function signupUsers()
    {
        //try {
        //$data = $_POST;

        /*  $checkInput = new CheckInput(
            $data
         ); */
        if (!$this->pwMatch() || $this->emailTaken() || !isset($this->getData)) {
            throw new Error("Erreur : On n'a pas pu check les inputs") ;
        } else {
            $checkInput = new CheckInput(
                $this->getData
            );
            $checkInput->checkInputs();
            $this->insertUser($this->nom, $this->email, $this->password, $this->age);
        }
        //$db = null;
        /* } catch (Error $e) {
            die('Erreur : '. $e->getMessage() . ' Insertion dans la DB impossible') ;
        } */
    }

    private function emailTaken(): bool
    {
        (bool)$resultCheck = '' ;
        if (!$this->checkUser($this->email, $this->nom)) {
            $resultCheck = false;
        } else {
            $resultCheck = true;
        }
        return $resultCheck;
    }

    private function pwMatch(): bool
    {
        (bool)$resultCheck = '' ;
        if ($this->password != $this->pwdRepeat) {
            $resultCheck = false;
            throw new Error("Erreur : Les mots de passes ne sont pas identiques" .
                header("Location: ".Functions::getUrl()."?error=pwd-doesnt-match")) ;
        } else {
            $resultCheck = true;
        }
        return $resultCheck;
    }
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
