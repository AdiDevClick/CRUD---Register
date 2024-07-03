<?php

class RecipeController extends Recipe
{
    public function __construct(
        private $getData
    ) {
    }

    protected function insertRecipes2()
    {
        try {
            $loggedUser = LoginController::checkLoggedStatus();
            if  (!isset($loggedUser)) {
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de partager une recette.") ;
            } else {

                $checkInput = new CheckInput(
                    $this->getData
                );
                // if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                // print_r($_FILES);
                // }
                // print_r($this->getData);
                $title = $checkInput->test_input($this->getData['title']);
                $step_1 = $checkInput->test_input($this->getData["step_1"]);
                $step_2 = $checkInput->test_input($this->getData["step_2"]);
                $step_3 = $checkInput->test_input($this->getData["step_3"]);
                $step_4 = $checkInput->test_input($this->getData["step_4"]);
                $step_5 = $checkInput->test_input($this->getData["step_5"]);
                $step_6 = $checkInput->test_input($this->getData["step_6"]);
                $total_time = $checkInput->test_input($this->getData["total_time"]);
                $total_time_length = $checkInput->test_input($this->getData["total_time_length"]);
                $resting_time = $checkInput->test_input($this->getData["resting_time"]);
                $resting_time_length = $checkInput->test_input($this->getData["resting_time_length"]);
                $oven_time = $checkInput->test_input($this->getData["oven_time"]);
                $oven_time_length = $checkInput->test_input($this->getData["oven_time_length"]);
                $ingredient = $checkInput->test_input($this->getData["ingredient"] ?? null);
                $ingredient2 = $checkInput->test_input($this->getData["ingredient2"] ?? null);
                $ingredient3 = $checkInput->test_input($this->getData["ingredient3"] ?? null);
                $ingredient4 = $checkInput->test_input($this->getData["ingredient4"] ?? null);
                $ingredient5 = $checkInput->test_input($this->getData["ingredient5"] ?? null);
                $ingredient6 = $checkInput->test_input($this->getData["ingredient6"] ?? null);
                $persons = $checkInput->test_input($this->getData["persons"]);
                $custom_ingredients = $checkInput->test_input($this->getData["custom_ingredients"]);
                // $title = $this->getData["title"];
                // $step_1 = $this->getData["step_1"];
                // $step_2 = $this->getData["step_2"];
                // $step_3 = $this->getData["step_3"];
                // $step_4 = $this->getData["step_4"];
                // $step_5 = $this->getData["step_5"];
                // $step_6 = $this->getData["step_6"];
                // $total_time = $this->getData["total_time"];
                // $total_time_length = $this->getData["total_time_length"];
                // $resting_time = $this->getData["resting_time"];
                // $resting_time_length = $this->getData["resting_time_length"];
                // $oven_time = $this->getData["oven_time"];
                // $oven_time_length = $this->getData["oven_time_length"];
                // $ingredient = $this->getData["ingredient"];
                // $ingredient2 = $this->getData["ingredient2"];
                // $ingredient3 = $this->getData["ingredient3"];
                // $ingredient4 = $this->getData["ingredient4"];
                // $ingredient5 = $this->getData["ingredient5"];
                // $ingredient6 = $this->getData["ingredient6"];
                // $persons = $this->getData["persons"];
                // $custom_ingredients = $this->getData["custom_ingredients"];
                $checkInput->checkInputs();
                if (empty($checkInput->getErrorsArray())) {
                    $id = $this->setRecipeTest(
                        $title,
                        $step_1,
                        $step_2,
                        $step_3,
                        $step_4,
                        $step_5,
                        $step_6,
                        $total_time,
                        $total_time_length,
                        $resting_time,
                        $resting_time_length,
                        $oven_time,
                        $oven_time_length,
                        $ingredient,
                        $ingredient2,
                        $ingredient3,
                        $ingredient4,
                        $ingredient5,
                        $ingredient6,
                        $persons,
                        $custom_ingredients,
                        $loggedUser['email']
                    );
                    $data = $id;
                    // $data = ['recipeId' => $id, 'fileName' => $this->getData['file'], 'filePath' => $this->getData['file_path']];
                    // return $data;
                    // $this->setImages($data);
                    // echo json_encode(['status' => 'success', 'img_status' => $this->getData['img_status'], 'is_on_server' => $this->getData['img_on_server']]);

                    // echo json_encode('window.location.href = ../index.php?success=recipe-shared');
                    // echo json_encode(header('refresh:10, ../index.php?success=recipe-shared'));
                    // echo 'window.location.href = ../index.php?success=recipe-shared';
                    // echo 'window.location.href = ../index.php?success=recipe-shared';
                    // echo json_encode(['status' => 'success','message' => 'test']);
                } else {
                    // echo "<pre>";
                    $data = $checkInput->getErrorsArray();
                    // echo json_encode($checkInput->getErrorsArray());
                    // print_r($checkInput->getErrorsArray());
                    // echo "</pre>";
                    // header('refresh:10, ../index.php?success=recipe-shared');
                    // $checkInput->getErrorsArray();
                    // echo json_encode(['status' => 'error','message' => 'test error']);
                    // return;
                }

                $registeredRecipe = [
                    'email' => $loggedUser['email']
                ];
                $_SESSION['REGISTERED_RECIPE'] = $registeredRecipe;

                return $data;
                // return $registeredRecipe;
            }
        } catch (Error $e) {
            die('Erreur : '. $e->getMessage() . ' , Insertion de la recette dans la DB impossible') ;
        }
    }
    protected function insertRecipes()
    {
        try {
            $loggedUser = LoginController::checkLoggedStatus();
            if  (!isset($loggedUser)) {
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de partager une recette.") ;
            } else {
                print_r($this->getData);

                $checkInput = new CheckInput(
                    $this->getData
                );

                $title = $checkInput->test_input($this->getData["title"]);
                $step_1 = $checkInput->test_input($this->getData["step_1"]);
                $step_2 = $checkInput->test_input($this->getData["step_2"]);
                $step_3 = $checkInput->test_input($this->getData["step_3"]);
                $step_4 = $checkInput->test_input($this->getData["step_4"]);
                $step_5 = $checkInput->test_input($this->getData["step_5"]);
                $step_6 = $checkInput->test_input($this->getData["step_6"]);

                $checkInput->checkInputs();

                // echo $loggedUser['email'][0];
                // echo 'array dans le recipectroller';
                // print_r($loggedUser);
                //$this->insertUser($this->nom, $this->email, $this->password, $this->age);
                if (empty($checkInput->getErrorsArray())) {
                    $this->setRecipes($title, $step_1, $step_2, $step_3, $step_4, $step_5, $step_6, $loggedUser['email']);
                    // $this->insertUser($this->getDatas['username'], $this->getDatas['email'], $this->getDatas['password'], $this->getDatas['age']);
                } else {
                    $checkInput->getErrorsArray();
                    return;
                }

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
            $this->getRecipesId($this->getData);
            $deletingRecipe = [
                'id' => $this->getData
            ];
            $_SESSION['DELETING_RECIPE'] = $deletingRecipe;
            if (isset($deletingRecipe)) {
                $this->deleteRecipeId($this->getData);
                unset($deletingRecipe);
            }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu supprimer cette recette ");
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
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu récupérer le titre de cette recette ");
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
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu récupérer cette recette ");
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
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu récupérer cette recette");
        }
    }

    protected function fetchesIngredientsInfosById()
    {
        try {
            if (!isset($recipeId)) {
                $this->checkIds();
                $recipeId = [
                    'recipeInfos' => $this->getData
                ];
                $_SESSION['INFO_RECIPE'] = $recipeId;
                return $this->getIngredientsInfosById($this->getData);
            }
            unset($recipeId);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu récupérer les ingrédients ");
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
                // print_r($this->getData);
                $title = $checkInput->test_input($this->getData['title']);
                $step_1 = $checkInput->test_input($this->getData["step_1"]);
                $step_2 = $checkInput->test_input($this->getData["step_2"]);
                $step_3 = $checkInput->test_input($this->getData["step_3"]);
                $step_4 = $checkInput->test_input($this->getData["step_4"]);
                $step_5 = $checkInput->test_input($this->getData["step_5"]);
                $step_6 = $checkInput->test_input($this->getData["step_6"]);
                $total_time = $checkInput->test_input($this->getData["total_time"]);
                $total_time_length = $checkInput->test_input($this->getData["total_time_length"]);
                $resting_time = $checkInput->test_input($this->getData["resting_time"]);
                $resting_time_length = $checkInput->test_input($this->getData["resting_time_length"]);
                $oven_time = $checkInput->test_input($this->getData["oven_time"]);
                $oven_time_length = $checkInput->test_input($this->getData["oven_time_length"]);
                $ingredient = $checkInput->test_input($this->getData["ingredient"] ?? null);
                $ingredient2 = $checkInput->test_input($this->getData["ingredient2"] ?? null);
                $ingredient3 = $checkInput->test_input($this->getData["ingredient3"] ?? null);
                $ingredient4 = $checkInput->test_input($this->getData["ingredient4"] ?? null);
                $ingredient5 = $checkInput->test_input($this->getData["ingredient5"] ?? null);
                $ingredient6 = $checkInput->test_input($this->getData["ingredient6"] ?? null);
                $persons = $checkInput->test_input($this->getData["persons"]);
                $custom_ingredients = $checkInput->test_input($this->getData["custom_ingredients"]);
                $id = $checkInput->test_input($this->getData["recipe_id"]);
                $file = $checkInput->test_input($this->getData["file"]);
                $checkInput->checkInputs();
                // exit;
                /* $title = $checkInput->test_input($this->getData["title"]);
                $recipe = $checkInput->test_input($this->getData["recipe"]);
                $checkInput->checkInputs(); */
                /* $title = $checkInput->test_input($title);
                $recipe = $checkInput->test_input($recipe);
                $checkInput->checkInputs(); */
                // echo $id;
                if (empty($checkInput->getErrorsArray())) {
                    $this->updateRecipes(
                        $title,
                        $step_1,
                        $step_2,
                        $step_3,
                        $step_4,
                        $step_5,
                        $step_6,
                        $total_time,
                        $total_time_length,
                        $resting_time,
                        $resting_time_length,
                        $oven_time,
                        $oven_time_length,
                        $ingredient,
                        $ingredient2,
                        $ingredient3,
                        $ingredient4,
                        $ingredient5,
                        $ingredient6,
                        $persons,
                        $custom_ingredients,
                        $id
                    );
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode($checkInput->getErrorsArray());
                }
                $recipeId = [
                    'updatedRecipeInfos' => $this->getData
                    //'updatedRecipeInfos' => $id
                ];
                $_SESSION['UPDATED_RECIPE'] = $recipeId;
                //return $this->updateRecipes($this->getData, $this->getData, $this->getData);
                //return $this->updateRecipes($title, $recipe, $id);
                return $recipeId;

            }
            //unset($recipeId);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu mettre à jour cette recette ");
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
                if (empty(CheckInput::getErrorsArray())) {
                    $this->getAverageRatingCommentsById($id);

                    $recipeId = [
                        'averageRating' => $this->getData
                        //'updatedRecipeInfos' => $id
                    ];
                    $_SESSION['RATING'] = $recipeId;
                }
                //return $this->updateRecipes($this->getData, $this->getData, $this->getData);
                //return $this->updateRecipes($title, $recipe, $id);
                //return $recipeId;

            }
            //unset($recipeId);
        } catch (Error $e) {
            CheckInput::insertErrorMessageInArray($e->getMessage());

            // array_push(CheckInput::getErrorsArray($e->getMessage()), );
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
            throw new Error("RCPDATACHK - Vous n'avez pas sélectionné la bonne recette") ;
        } else {
            //echo "c'est ok pour l'id" ;
        }
        return $status;
    }

    protected function setComments($getData)
    {
        $loggedUser = LoginController::checkLoggedStatus();
        if  (!isset($loggedUser)) {
            throw new Error("RCPLGGDUSROFF - Veuillez vous identifier avant de partager une recette.") ;
        } else {
            $checkInput = new CheckInput(
                $this->getData
            );
            /*  $message = $checkInput->test_input($this->getData["comment"]);
             $recipeId = $checkInput->test_input($this->getData["recipe_id"]); */
            $message = $checkInput->test_input($getData['comment']);
            $recipeId = $checkInput->test_input($getData['recipeId']);

            $checkInput->checkInputs();

            if (empty(CheckInput::getErrorsArray())) {
                //print_r($loggedUser);
                $this->insertComments($message, $recipeId, $loggedUser['userId']);
                $registeredComment = [
                    'email' => $loggedUser['email']
                ];
                $_SESSION['REGISTERED_COMMENT'] = $registeredComment;
                //header("Location: ".Functions::getUrl()."?error=none") ; */
                return $registeredComment;
            }
        }
    }

    protected function setImages($getData)
    {
        $loggedUser = LoginController::checkLoggedStatus();
        if  (!isset($loggedUser)) {
            throw new Error("RCPLGGDUSROFF - Veuillez vous identifier avant de partager une recette.") ;
        } else {
            $checkInput = new CheckInput(
                $this->getData
            );
            /*  $message = $checkInput->test_input($this->getData["comment"]);
             $recipeId = $checkInput->test_input($this->getData["recipe_id"]); */
            // $message = $checkInput->test_input($getData['comment']);
            $recipeId = $checkInput->test_input($getData['recipeId']);
            $fileName = $getData['fileName'];
            $filePath = $getData['filePath'];
            $checkInput->checkInputs();
            if (empty(CheckInput::getErrorsArray())) {
                $this->insertImages($recipeId, $loggedUser['userId'], $fileName, $filePath);
                $registeredImage = [
                    'email' => $loggedUser['email']
                ];
                $_SESSION['REGISTERED_IMG'] = $registeredImage;
                //header("Location: ".Functions::getUrl()."?error=none") ; */
                // return $registeredImage;
            }
        }
    }

    protected function getUsersById(): array
    {
        $sqlUsersQuery =
        'SELECT * FROM users;';
        /* 'SELECT * FROM `users`;'; */

        $usersStatement = $this->connect()->prepare($sqlUsersQuery);

        if (!$usersStatement->execute()) {
            $usersStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("STMTRCPGETUSR - Failed");
            //header("Location : ".$url->getThisUrl(). "?error=user-not-found");
        }
        if ($usersStatement->rowCount() == 0) {
            $usersStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=user-not-found"));
            throw new Error("STMTRCPGETPWCNT - L'utilisateur n'a pas été trouvé");
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
