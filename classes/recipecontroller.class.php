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

        try {
            //if ($checkId->checkIds()) {
                $this->getRecipeId($this->getData);
                $deletingRecipe = [
                    'id' => $this->getData
                ];
                $_SESSION['DELETING_RECIPE'] = $deletingRecipe;
            //}
            if (isset($deletingRecipe)) {
                $this->deleteRecipeId($this->getData);
                unset($deletingRecipe);
            }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu supprimer cette recette");
        }
    }

    protected function checkIds(): bool
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
            //echo "c'est ok pour l'id" ;
        }
        return $status;
    }
}
