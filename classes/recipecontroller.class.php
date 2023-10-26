<?php

class Recipecontroller extends Recipe
{
    public function __construct(
        private $getData
    ) {
    }

    protected function insertRecipes()
    {
        try {
            $loggedUser = LoginController::checkLoggedStatus();
            if  (!isset($loggedUser)) {
                throw new Error("Erreur : Veuillez vous identifier avant de partager une recette.") ;
            } else {
                $checkInput = new CheckInput(
                    $this->getData
                );
                
                $title = $checkInput->test_input($this->getData["title"]);
                $recipe = $checkInput->test_input($this->getData["recipe"]);
                $checkInput->checkInputs();

                //$this->insertUser($this->nom, $this->email, $this->password, $this->age);
                $this->setRecipes($title, $recipe, $loggedUser['email']);
                $registeredRecipe = [
                    'email' => $loggedUser['email']
                ];
                $_SESSION['REGISTERED_RECIPE'] = $registeredRecipe;
                //header("Location: ".Functions::getUrl()."?error=none") ; */
                return $registeredRecipe;   
                    /* $registeredRecipe = [
                            'email' => $this->getData['title'],
                            //'username' => $user['full_name'],
                            ]; */
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
                //$_SESSION['REGISTERED_RECIPE'] = $registeredRecipe;
                //header("Location: ".Functions::getUrl()."?error=none") ; */
                //return $registeredRecipe;
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
            die('Erreur : '. $e->getMessage() . ' , Insertion de la recette dans la DB impossible') ;
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
/* 
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
 */
}
