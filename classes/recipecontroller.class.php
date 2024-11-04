<?php

class RecipeController extends Recipe
{
    public function __construct(
        private $getData,
        protected $optionnalData = null,
    ) {
        //
    }

    /**
     * Récupère dynamiquement des ROWS de la TABLE recipes
     * @param array $params ex : ['user', 'id', 'title']
     * @param int $recipeId L'ID de la recette
     * @param string $sessionName Lenom de la session qui sera créée
     * @return mixed
     */
    protected function fetchFromTable(array $params, string $sessionName) {
        try {
            if (!isset($id)) {
                // Checks if a correct ID type is passed
                $this->checkIds();
                $recipeInfos = $this->getFromTable($params, $this->getData);
                // Saves the ID in Session
                $id = [
                    $sessionName => $this->getData
                ];
                $_SESSION[$sessionName] = $id;
                return $recipeInfos;
            }
            unset($id);
        } catch (Error $e) {
            // This grabs the first Error from Recipe then concatenates the additionnel message
            die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu récupérer cette recette ");
        }
    }
    // public function getSelfData() {
    //     return (int) $this->getData;
    // }

    // protected function setOptionnalData($param) {
    //     $this->optionnalData = $param;
    // }

    protected function insertRecipe()
    {
        try {
            $loggedUser = LoginController::checkLoggedStatus();
            if  (!isset($loggedUser)) {
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de partager une recette.") ;
            } else {
                // Delete data recipe_id in case of a recipe creation
                if ($this->optionnalData === 'creation') {
                    array_pop($this->getData);
                }

                $checkInput = new CheckInput(
                    $this->getData
                );
                // die(var_dump($this->getData));

                foreach ($this->getData as $key => $value) {
                    if ($key !== 'video_link') {
                        $sanitized_Datas[$key] = $checkInput->test_input($value);
                    }
                }

                $checkInput->checkInputs();
                if (empty($checkInput->getErrorsArray())) {
                    $id = $this->setRecipes($loggedUser['email'], $sanitized_Datas);
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
                    // $data = $checkInput->getErrorsArray();
                    echo json_encode($checkInput->getErrorsArray());
                    // $data = new Error('Erreur : ' . $checkInput->getErrorsArray());
                    // print_r($checkInput->getErrorsArray());
                    // echo "</pre>";
                    // header('refresh:10, ../index.php?success=recipe-shared');
                    // $checkInput->getErrorsArray();
                    // echo json_encode(['status' => 'error','message' => 'test error']);
                    // return;
                }

                // Sets email info inside user Session
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
    // protected function insertRecipes()
    // {
    //     try {
    //         $loggedUser = LoginController::checkLoggedStatus();
    //         if  (!isset($loggedUser)) {
    //             throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de partager une recette.") ;
    //         } else {
    //             // print_r($this->getData);

    //             $checkInput = new CheckInput(
    //                 $this->getData
    //             );

    //             $title = $checkInput->test_input($this->getData["title"]);
    //             $step_1 = $checkInput->test_input($this->getData["step_1"]);
    //             $step_2 = $checkInput->test_input($this->getData["step_2"]);
    //             $step_3 = $checkInput->test_input($this->getData["step_3"]);
    //             $step_4 = $checkInput->test_input($this->getData["step_4"]);
    //             $step_5 = $checkInput->test_input($this->getData["step_5"]);
    //             $step_6 = $checkInput->test_input($this->getData["step_6"]);

    //             $checkInput->checkInputs();

    //             // echo $loggedUser['email'][0];
    //             // echo 'array dans le recipectroller';
    //             // print_r($loggedUser);
    //             //$this->insertUser($this->nom, $this->email, $this->password, $this->age);
    //             if (empty($checkInput->getErrorsArray())) {
    //                 $this->setRecipes($title, $step_1, $step_2, $step_3, $step_4, $step_5, $step_6, $loggedUser['email']);
    //                 // $this->insertUser($this->getDatas['username'], $this->getDatas['email'], $this->getDatas['password'], $this->getDatas['age']);
    //             } else {
    //                 $checkInput->getErrorsArray();
    //                 return;
    //             }

    //             $registeredRecipe = [
    //                 'email' => $loggedUser['email']
    //             ];

    //             $_SESSION['REGISTERED_RECIPE'] = $registeredRecipe;
    //             //header("Location: ".Functions::getUrl()."?error=none") ; */
    //             return $registeredRecipe;
    //             /* $registeredRecipe = [
    //                     'email' => $this->getData['title'],
    //                     //'username' => $user['full_name'],
    //                     ]; */
    //             /* setcookie(
    //                         'REGISTERED_USER',
    //                         $this->getData['email'],
    //                         [
    //                             'expires' => time() + 0 * 0 * 10,
    //                             'secure' => true,
    //                             'httponly' => true,
    //                         ]
    //             );  */
    //             //session_start();
    //             //$_SESSION['REGISTERED_RECIPE'] = $registeredRecipe;
    //             //header("Location: ".Functions::getUrl()."?error=none") ; */
    //             //return $registeredRecipe;
    //         }
    //         /* if ($this->emailTaken()) {
    //             $registeredUser = [
    //             'email' => $this->getData['email'],
    //             //'username' => $user['full_name'],
    //             ];

    //             //header('Location: index.php');
    //             //session_start();

    //         } */

    //         //$db = null;
    //     } catch (Error $e) {
    //         die('Erreur : '. $e->getMessage() . ' , Insertion de la recette dans la DB impossible') ;
    //     }
    // }

    /**
     * Supprime une recette
     * @return void
     */
    protected function deleteRecipes()
    {
        try {
            $this->deleteRecipeId($this->getData);
            $this->deleteImageId($this->getData);
            // $this->getRecipesId($this->getData);
            // $deletingRecipe = [
            //     'id' => $this->getData
            // ];
            // $_SESSION['DELETING_RECIPE'] = $deletingRecipe;
            // if (isset($deletingRecipe)) {
            //     $this->deleteRecipeId($this->getData);
            //     unset($deletingRecipe);
            // }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu supprimer cette recette ");
        }
    }

    protected function fetchRecipesTitle()
    {
        try {
            //if ($checkId->checkIds()) {
            // echo $this->getData . ' ' . $this->optionnalData . ' ' . $_SESSION['LAST_ID'];
            $title = $this->getRecipesTitles($this->getData, $this->optionnalData);
            // echo json_encode(array("title"=> $title));
            // foreach ($title = $this->getRecipesTitles($this->getData) as $recipeItem) {
            //     echo '<br>';
            //     print_r($recipeItem);
            //     echo '<br>';
            // }
            $titleRecipe = [
                'title' => $title
            ];
            // print_r ($titleRecipe);
            $_SESSION['TITLE_RECIPE'] = $titleRecipe;
            //}
            // if (isset($titleRecipe)) {
            //     $this->getRecipesTitles($this->getData);
            //     unset($titleRecipe);
            // }
            return $title;
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

    /**
     * Récupère le TITRE et ID de la recette
     * en utilisant le nombre passé en paramètre
     * @return mixed
     */
    // protected function fetchesRecipeAuthorAndTitle()
    // {
    //     try {
    //         if (!isset($recipeId)) {
    //             // Checks if a correct ID type is passed
    //             $this->checkIds();
    //             $recipeId = [
    //                 'recipeId' => $this->getData
    //             ];
    //             $_SESSION['ID_RECIPE'] = $recipeId;
    //             // Fetch datas
    //             return $this->getRecipesId($this->getData);
    //         }
    //         unset($recipeId);
    //     } catch (Error $e) {
    //         die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu récupérer cette recette ");
    //     }
    // }

    /**
     * Vérifie qu'une ID de recette a bien été passée
     * Récupère les informations de recettes
     * @return mixed
     */
    // protected function fetchesRecipeInfosById()
    // {
    //     try {
    //         if (!isset($recipeId)) {

    //             // Check if the ID is set
    //             $this->checkIds();
    //             // Saves the ID in Session
    //             $recipeId = [
    //                 'recipeInfos' => $this->getData
    //             ];
    //             $_SESSION['INFO_RECIPE'] = $recipeId;
    //             // Calls for the recipe infos
    //             return $this->getRecipesInfosById($this->getData);

    //         }
    //         unset($recipeId);
    //     } catch (Error $e) {
    //         die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu récupérer cette recette");
    //     }
    // }

    /**
     * Récupère les ingrédients personnalisés de la recette
     * @return void
     */
    protected function fetchesIngredientsInfosById()
    {
        try {
            if (!isset($recipeId)) {
                // Checks if an ID is present in the request
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
            $loggedUser = LoginController::checkLoggedStatus();
            if  (!isset($loggedUser)) {
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de pouvoir mettre à jour une recette.") ;
            }
            if (!isset($recipeId)) {
                // Checks if the content is legit
                // TO BE FIXED
                $this->checkIds();
                // die(var_dump($this->getData)) ;

                $checkInput = new CheckInput(
                    $this->getData
                );

                // Sanitize every value
                foreach ($this->getData as $key => $value) {
                    $sanitized_Datas[$key] = $checkInput->test_input($value);
                }
                $checkInput->checkInputs();

                if (empty($checkInput->getErrorsArray())) {
                    $id = [];
                    $update_Status = $this->updateRecipes($sanitized_Datas);
                    $update_Status['update_status'] === 'success' ?
                        null :
                        $id = [
                            'recipe_id' => $sanitized_Datas['recipe_id'],
                            'status' => $update_Status,
                            'query_type' => 'update'
                        ];
                    // echo json_encode(['update_status' => 'success']);
                    // Sets infos inside the User Session
                    $recipeId = [
                        'updatedRecipeInfos' => $this->getData
                        // 'updatedRecipeInfos' => $id
                    ];
                    $_SESSION['UPDATED_RECIPE'] = $recipeId;
                    return $id;
                } else {
                    echo json_encode($checkInput->getErrorsArray());
                }
            }
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

    /**
     * Vérifie que les données ont bien été envoyées normalement
     * @throws \Error
     * @return bool
     */
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
            // echo "c'est ok pour l'id" ;
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
