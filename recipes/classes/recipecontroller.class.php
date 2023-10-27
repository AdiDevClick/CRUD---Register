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

    protected function deleteRecipes()
    {
        //$checkId = $this->deleteRecipeId($this->getData);

        //try {
            //$this->getData->checkIds();
            $this->getData->checkIds();
            echo 'etape 1';
            $this->getRecipeId($this->getData);               
            echo 'etape 2';
            $this->deleteRecipeId($this->getData);
            echo 'etape 3';
            //}
        /* } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu supprimer cette recette");
        } */
    }

    public function checkIds(): bool
    {
        //$errorMessage = "";
        $status = true;
        if (!isset($this->getData) || empty($this->getData)) {
    /* $errorMessage = sprintf(
        "Vous n'avez pas saisit de : %s",
        $this->input2
    ); */
        $status = false;
        throw new Error("Vous n'avez pas sélectionné la bonne recette") ;
        } else {
            echo "c'est ok pour l'id" ;
        }
        return $status;
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

