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
            $this->getRecipesId($this->getData);
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

    protected function fetchRecipesTitle()
    {
        try {
            //if ($checkId->checkIds()) {
            $this->getRecipesTitles($this->getData);
            $titleRecipe = [
                'title' => $this->getData
            ];
            $_SESSION['TITLE_RECIPE'] = $titleRecipe;
            //}
            if (isset($titleRecipe)) {
                $this->getRecipesTitles($this->getData);
                unset($titleRecipe);
            }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons récupérer le titre de cette recette");
        }
    }

    /*  public function fetchRecipeId()
     {
         try {
             if ($this->checkIds()) {
             //$this->getRecipesId($this->getData);
             $recipeId = [
                 'recipeId' => $this->getData
             ];
             $_SESSION['ID_RECIPE'] = $recipeId;
             }
             if (isset($recipeId)) {
                 $this->getRecipesId($this->getData);
                 unset($recipeId);
             }
         } catch (Error $e) {
             die('Erreur : ' . $e->getMessage() . "Nous n'avons récupérer cette recette");
         }
     } */

    protected function fetchesRecipeId()
    {
        try {
            if (!isset($recipeId)) {
                $this->checkIds();
                $recipeId = [
                    'recipeId' => $this->getData
                ];
                $_SESSION['ID_RECIPE'] = $recipeId;
                return $this->getRecipesId($this->getData);
            }
            unset($recipeId);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons récupérer cette recette");
        }
    }

    protected function fetchesRecipeInfosById()
    {
        try {
            if (!isset($recipeId)) {
                $this->checkIds();
                $recipeId = [
                    'recipeInfos' => $this->getData
                ];
                $_SESSION['INFO_RECIPE'] = $recipeId;
                return $this->getRecipesInfosById($this->getData);
            }
            unset($recipeId);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons récupérer cette recette");
        }
    }

    //protected function updateRecipesInfosById($title, $recipe, $id)
    protected function updateRecipesInfosById()
    {
        try {
            if (!isset($recipeId)) {
            //if ($this->checkIds()) {
                $this->checkIds();

                $checkInput = new CheckInput(
                    $this->getData
                );

                $title = $checkInput->test_input($this->getData["title"]);
                $recipe = $checkInput->test_input($this->getData["recipe"]);
                $id = $checkInput->test_input($this->getData["recipe_id"]);
                $checkInput->checkInputs();

                /* $title = $checkInput->test_input($this->getData["title"]);
                $recipe = $checkInput->test_input($this->getData["recipe"]);
                $checkInput->checkInputs(); */
                /* $title = $checkInput->test_input($title);
                $recipe = $checkInput->test_input($recipe);
                $checkInput->checkInputs(); */

                $this->updateRecipes($title, $recipe, $id);

                $recipeId = [
                    'updatedRecipeInfos' => $this->getData
                    //'updatedRecipeInfos' => $id
                ];
                $_SESSION['UPDATED_RECIPE'] = $recipeId;

                //return $this->updateRecipes($this->getData, $this->getData, $this->getData);
                //return $this->updateRecipes($title, $recipe, $id);
                //return $recipeId;

            }
            //unset($recipeId);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu mettre à jour cette recette");
        }
    }

    protected function getCommentsById()
    {
        try {
            if (!isset($recipeId)) {
            //if ($this->checkIds()) {
                $this->checkIds();

                $checkInput = new CheckInput(
                    $this->getData
                ); 

                $id = $checkInput->test_input($this->getData["recipe_id"]);
                $checkInput->checkInputs();

                /* $title = $checkInput->test_input($this->getData["title"]);
                $recipe = $checkInput->test_input($this->getData["recipe"]);
                $checkInput->checkInputs(); */
                /* $title = $checkInput->test_input($title);
                $recipe = $checkInput->test_input($recipe);
                $checkInput->checkInputs(); */

                $this->getRecipesWithCommentsById($id);

                $recipeId = [
                    'comments' => $this->getData
                    //'updatedRecipeInfos' => $id
                ];
                $_SESSION['COMMENTS'] = $recipeId;

                //return $this->updateRecipes($this->getData, $this->getData, $this->getData);
                //return $this->updateRecipes($title, $recipe, $id);
                //return $recipeId;

            }
            //unset($recipeId);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu récupérer les commentaires de cette recette");
        }
    }

    protected function getAverageRatingById()
    {
        try {
            if (!isset($recipeId)) {
            //if ($this->checkIds()) {
                $this->checkIds();

                $checkInput = new CheckInput(
                    $this->getData
                );

                $id = $checkInput->test_input($this->getData["recipe_id"]);
                $checkInput->checkInputs();

                /* $title = $checkInput->test_input($this->getData["title"]);
                $recipe = $checkInput->test_input($this->getData["recipe"]);
                $checkInput->checkInputs(); */
                /* $title = $checkInput->test_input($title);
                $recipe = $checkInput->test_input($recipe);
                $checkInput->checkInputs(); */

                $this->getAverageRatingCommentsById($id);

                $recipeId = [
                    'averageRating' => $this->getData
                    //'updatedRecipeInfos' => $id
                ];
                $_SESSION['RATING'] = $recipeId;

                //return $this->updateRecipes($this->getData, $this->getData, $this->getData);
                //return $this->updateRecipes($title, $recipe, $id);
                //return $recipeId;

            }
            //unset($recipeId);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu mettre à jour cette recette");
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
    /***
     * Inserts comments using the $loggedUser[user array][level 1 (user id)]
     * to link users with their comments
     * returns a session
     * => Cookie dependents => Clean cookies before checking the issue
     */
    protected function setComments($getData)
    {
        $loggedUser = LoginController::checkLoggedStatus();
            if  (!isset($loggedUser)) {
                throw new Error("Erreur : Veuillez vous identifier avant de partager une recette.") ;
            } else {
                $checkInput = new CheckInput(
                    $this->getData
                );
               /*  $message = $checkInput->test_input($this->getData["comment"]);
                $recipeId = $checkInput->test_input($this->getData["recipe_id"]); */
                $message = $checkInput->test_input($getData['comment']);
                $recipeId = $checkInput->test_input($getData['recipeId']);

                $checkInput->checkInputs();

                $this->insertComments($message, $recipeId, $loggedUser['user'][1]);
                $registeredComment = [
                    'email' => $loggedUser['email']
                ];
                $_SESSION['REGISTERED_COMMENT'] = $registeredComment;
                //header("Location: ".Functions::getUrl()."?error=none") ; */
                return $registeredComment;    
        }
    }

    protected function getUsersById() : array
    {
        $sqlUsersQuery =
        'SELECT * FROM users;';
        /* 'SELECT * FROM `users`;'; */

        $usersStatement = $this->connect()->prepare($sqlUsersQuery);

        if (!$usersStatement->execute()) {
            $usersStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            //header("Location : ".$url->getThisUrl(). "?error=user-not-found");
        }
        if ($usersStatement->rowCount() == 0) {
            $usersStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=user-not-found"));
            //header("Location : ".$url->getThisUrl()."?error=user-not-found");
            //exit();
        } //else {
        $users = $usersStatement->fetchAll(PDO::FETCH_ASSOC);
            /* $usersStatement = null;
            header("Location : ".Functions::getUrl(). "?error=stmt-failed");
            exit(); */       
        return $users;
    }
}
