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

            $sanitized_Datas = $checkInput->sanitizeData();

            if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                unset($_SESSION['SANITIZED']);

                if  ($this->emailTaken($sanitized_Datas["email"], $sanitized_Datas["username"]) ||
                    !$this->pwMatch($sanitized_Datas["password"], $sanitized_Datas["pwdRepeat"]) ||
                    !isset($this->getDatas)) {
                    CheckInput::insertErrorMessageInArray("SGNTKN : On n'a pas pu check les inputs");
                    // throw new Error("SGNTKN : On n'a pas pu check les inputs") ;
                    return;
                } else {

                    if (empty($checkInput->getErrorsArray())) {
                        $this->insertUser($sanitized_Datas["username"], $sanitized_Datas["email"], $sanitized_Datas["password"], $sanitized_Datas["age"]);
                    } else {
                        return $checkInput->getErrorsArray();
                    }

                    $registeredUser = [
                        'email' => $this->getDatas['email'],
                    ];
                    $_SESSION['REGISTERED_USER'] = $registeredUser;

                    return $registeredUser;
                }
            }
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
