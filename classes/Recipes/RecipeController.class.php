<?php

class RecipeController extends Recipe
{
    public function __construct(
        private $getData,
        private $optionnalData = null,
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
    protected function fetchFromTable(array $params, string $sessionName)
    {

        try {
            if (!isset($id)) {

                // Checks if a correct ID type is passed
                $this->checkIds();
                //  Create SQL Query
                $datas = Functions::getFromTable($params, $this->getData, $this->connect(), $this->optionnalData());
                // $datas = $this->getFromTable($params, $this->getData);
                // Saves the ID in Session
                $id = [
                    $sessionName => $this->getData
                ];
                $_SESSION[$sessionName] = $id;
                return $datas;
            }
            unset($id);
        } catch (Error $e) {
            // This grabs the first Error from Recipe then concatenates the additionnel message
            return ('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu récupérer cette recette ");
        }
    }
    // public function getSelfData() {
    //     return (int) $this->getData;
    // }

    // protected function setOptionnalData($param) {
    //     $this->optionnalData = $param;
    // }

    protected function getOptionnalData()
    {
        return $this->optionnalData;
    }

    protected function controllerSetRecipe()
    {
        try {
            $loggedUser = LoginController::checkLoggedStatus();
            if (!isset($loggedUser)) {
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de partager une recette.");
            } else {
                // Delete data recipe_id & AJAX in case of a recipe creation
                if ($this->optionnalData === 'creation') {
                    $filterKeysToRemove = [
                        'AJAX',
                        'recipe_id'
                    ];
                    foreach ($this->getData as $filterKey => $value) {
                        if (!in_array($filterKey, $filterKeysToRemove)) {
                            $newData[$filterKey] = $value;
                        }
                    }
                    $this->getData = $newData;
                }

                $checker = new CheckInput($this->getData);

                $options = [
                    'key' => 'video_link',
                    'convert' => false
                ];

                $sanitized_Datas = $checker->sanitizeData($options);

                if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                    // if (empty($checkInput->getErrorsArray())) {
                    unset($_SESSION['SANITIZED']);
                    $id = $this->setRecipes($loggedUser['email'], $sanitized_Datas);
                    $data = $id;

                    // Sets email info inside user Session
                    $registeredRecipe = [
                        'email' => $loggedUser['email']
                    ];
                    $_SESSION['REGISTERED_RECIPE'] = $registeredRecipe;
                } else {
                    echo json_encode($_SESSION['SANITIZED']);
                    unset($_SESSION['SANITIZED']);
                }

                return $data;
                // return $registeredRecipe;
            }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . ' , Insertion de la recette dans la DB impossible');
        }
    }

    /**
     * Supprime une recette de la table recipes
     * Supprime l'image associée sur le disque
     * @return void
     */
    protected function deleteRecipes()
    {
        try {
            $recipeParams = [
                'error' => ["Cette recette ne peut pas être supprimée, elle n'existe pas."],
                "where" => [
                    "conditions" => [
                        "recipe_id" => "= :recipe_id"
                    ],
                ],
                'table' => ['recipes']
            ];
            $this->deleteFromTable($recipeParams, $this->getData);
            $this->deleteImageId($this->getData);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu supprimer cette recette ");
        }
    }

    //protected function updateRecipesInfosById($title, $recipe, $id)
    protected function updateRecipesInfosById()
    {
        try {
            $loggedUser = LoginController::checkLoggedStatus();
            if (!isset($loggedUser)) {
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de pouvoir mettre à jour une recette.");
            }
            if (!isset($recipeId)) {
                // Checks if the content is legit
                // TO BE FIXED
                $filterKeysToRemove = [
                    'AJAX'
                ];

                foreach ($this->getData as $filterKey => $value) {
                    if (!in_array($filterKey, $filterKeysToRemove)) {
                        $newData[$filterKey] = $value;
                    }
                }

                $this->getData = $newData;

                $this->checkIds();

                $checker = new CheckInput($this->getData);

                $options = [
                    'convert' => false
                ];
                $sanitized_Datas = $checker->sanitizeData($options);
                if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                    unset($_SESSION['SANITIZED']);
                    $id = [];
                    $update_Status = $this->updateRecipes($sanitized_Datas);
                    $update_Status['update_status'] === 'success' ?
                        null :
                        $id = [
                            'recipe_id' => $sanitized_Datas['recipe_id'],
                            'status' => $update_Status,
                            'query_type' => 'update'
                        ];
                    // Sets infos inside the User Session
                    $recipeId = [
                        'updatedRecipeInfos' => $this->getData
                    ];
                    $_SESSION['UPDATED_RECIPE'] = $recipeId;
                    return $id;
                } else {
                    echo json_encode($_SESSION['SANITIZED']);
                    unset($_SESSION['SANITIZED']);
                }
            }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu mettre à jour cette recette ");
        }
    }

    protected function controller_enableOrDisableRecipe()
    {
        try {
            $loggedUser = LoginController::checkLoggedStatus();
            if (!isset($loggedUser)) {
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de pouvoir mettre à jour une recette.");
            }
            if (!isset($response)) {

                $filterKeysToRemove = [
                    "session_name",
                    "any_post"
                ];

                foreach ($this->getData as $filterKey => $value) {
                    if (!in_array($filterKey, $filterKeysToRemove)) {
                        $newData[$filterKey] = $value;
                    }
                }

                $this->getData = $newData;

                $this->checkIds();

                // $checker = new CheckInput($this->getData);

                // $options = [
                //     'convert' => false
                // ];

                // die(var_dump($newData));
                // $sanitized_Datas = $checker->sanitizeData($options);
                if (is_bool($this->getData["is_enabled"] && $this->getData["recipe_id"])) {

                    // if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                    // unset($_SESSION['SANITIZED']);
                    $id = [];
                    $update_Status = $this->enableOrDisableRecipe($this->getData);

                    $update_Status['update_status'] === 'success' ?
                        null :
                        $id = [
                            'recipe_id' => $this->getData['recipe_id'],
                            'status' => $update_Status['status'],
                            'query_type' => 'update'
                        ];
                    // Sets infos inside the User Session
                    $response = [
                        'updatedRecipeInfos' => $this->getData
                    ];
                    $_SESSION['ENABLE_RECIPE'] = $response;

                    return $id;
                } else {
                    echo json_encode("test");
                    // echo json_encode($_SESSION['SANITIZED']);
                    // unset($_SESSION['SANITIZED']);
                }
            }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu activer/désactiver cette recette ");
        }
    }

    protected function getAverageRatingById()
    {
        try {
            if (!isset($recipeId)) {
                $this->checkIds();

                $checker = new CheckInput($this->getData);
                $data = $checker->sanitizeData();
                $id = $data["recipe_id"];

                if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                    $this->getAverageRatingCommentsById($id);

                    $recipeId = [
                        'averageRating' => $this->getData
                        //'updatedRecipeInfos' => $id
                    ];
                    $_SESSION['RATING'] = $recipeId;
                }
            }
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
            throw new Error("RCPDATACHK - Vous n'avez pas sélectionné la bonne recette");
        } else {
            // echo "c'est ok pour l'id" ;
        }
        return $status;
    }

    /**
     * Insère les commentaires dans la TABLE comment
     * @param array $getData
     * @throws \Error
     * @return mixed
     */
    protected function setComments($getData)
    {
        $errorCode = 500;
        $loggedUser = LoginController::checkLoggedStatus();
        // $isAjaxRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        try {
            if (!isset($loggedUser)) {
                $errorCode = 401;
                throw new Error("RCPLGGDUSROFF - Veuillez vous identifier avant de partager un commentaire.");
            } else {

                $notToInclude = ['comment_date', 'created_at', 'user_name', 'any_post', 'comment_id', 'session_name'];
                $filteredArray = [];

                foreach ($getData as $key => $value) {
                    if (!in_array($key, $notToInclude)) {
                        $filteredArray[$key] = $value;
                    }
                }

                // $filteredArray["user_id"] = 12;
                if (!empty($filteredArray["user_id"]) && $filteredArray["user_id"] !== $loggedUser['userId']) {
                    $errorCode = 403;
                    throw new Error("CMTIDLGGDUSROFF - Erreur lors du partage de votre commentaire.");
                }

                $checker = new CheckInput($filteredArray);
                // $checker = new CheckInput($this->$getData);

                // Do not need to convert into HTMLSUPERCHARS
                $options = [
                    'convert' => false
                ];

                $sanitized_Datas = $checker->sanitizeData($options);

                if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                    unset($_SESSION['SANITIZED']);

                    $response = $this->insertComments($sanitized_Datas, $loggedUser['userId']);

                    $registeredComment = [
                        'body' => $sanitized_Datas['comment'],
                        'comment_id' => $response['comment_id'],
                        'status' => $response['status'],
                        'ok' => $response['ok'],
                        'canCreateTooltips' => $response['canCreateTooltips'],
                    ];

                    $_SESSION[$getData['session_name']] = $registeredComment;

                    return $registeredComment;
                } else {
                    $errorCode = 400;
                    throw new Error('CMTSNTZEDOFF - ' . json_encode($_SESSION['SANITIZED']));
                    // echo json_encode($_SESSION['SANITIZED']);
                }
            }
        } catch (\Throwable $th) {
            unset($_SESSION['SANITIZED']);

            $response = [
                'status' => $errorCode,
                'message' => $th->getMessage(),
                'ok' => false
            ];
            http_response_code($errorCode);
            return $response;
            // echo json_encode($response);
            // return json_encode($response);
            // if ($isAjaxRequest) {
            //     // Définit le statut HTTP 200 à 500 
            //     http_response_code($errorCode);
            //     echo json_encode($response);
            // } else {
            //     return json_encode($response);
            //     // return $_SESSION['RESPONSE'] = $response;
            //     // header('Location: read.php');
            // }
            // header("Location: ../templates/404.php");
        }
    }

    /**
     * Permet de mettre à jour le commentaire
     * @param array $datas Les données reçues du formulaire $_POST
     * @throws \Error
     * @return array Renvoi l'erreur HTTP
     */
    protected function controllerUpdateComment(array $datas)
    {
        $errorCode = 500;
        $loggedUser = LoginController::checkLoggedStatus();

        try {
            if (!isset($loggedUser)) {
                $errorCode = 401;
                throw new Error("LGGDUSROFF  : Veuillez vous identifier avant de pouvoir mettre à jour une recette.");
            }

            $notToInclude = ['comment_date', 'created_at', 'user_name', 'any_post', 'session_name'];
            $filteredArray = [];

            foreach ($datas as $key => $value) {
                if (!in_array($key, $notToInclude)) {
                    $filteredArray[$key] = $value;
                }
            }

            if (!empty($filteredArray["user_id"]) && (int) $filteredArray["user_id"] !== (int) $loggedUser['userId']) {
                $errorCode = 403;
                throw new Error("CMTIDLGGDUSROFF - Erreur lors de la mise à jour de votre commentaire.");
            }

            $checker = new CheckInput($filteredArray);

            $options = [
                'convert' => false
            ];

            $sanitized_Datas = $checker->sanitizeData($options);

            if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                unset($_SESSION['SANITIZED']);

                $response = $this->updateComments($sanitized_Datas, $loggedUser['userId']);

                // Sets infos inside the User Session
                $updatedComment = [
                    'status' => $response['status'],
                    'ok' => $response['ok'],
                ];

                $_SESSION[$datas['session_name']] = $updatedComment;

                return $updatedComment;
            } else {
                $errorCode = 400;
                throw new Error('CMTSNTZEDOFF - ' . $_SESSION['SANITIZED']);
            }
        } catch (\Throwable $th) {
            unset($_SESSION['SANITIZED']);

            $response = [
                'status' => $errorCode,
                'message' => $th->getMessage(),
                'ok' => false
            ];

            // Définit le statut HTTP de 200 à 500
            http_response_code($errorCode);
            return $response;
            // die(json_encode($response));
        }
    }

    /**
     * Supprime un commentaire de la table comments
     * @param array $datas
     * @return
     */
    protected function controllerDeleteComments($datas)
    {
        $errorCode = 500;
        $loggedUser = LoginController::checkLoggedStatus();
        try {
            if (!isset($loggedUser)) {
                $errorCode = 401;
                throw new Error("RCPLGGDUSROFF - Veuillez vous identifier avant de partager un commentaire.");
            } else {

                if (!empty($datas["user_id"]) && (int) $datas["user_id"] !== (int) $loggedUser['userId']) {
                    $errorCode = 403;
                    throw new Error("CMTIDLGGDUSROFF - Erreur lors de la mise à jour de votre commentaire.");
                }

                $params = [
                    'error' => ["Ce commentaire ne peut pas être supprimée, il n'existe pas."],
                    "where" => [
                        "conditions" => [
                            "recipe_id" => '= ' . $datas['recipe_id'],
                            "user_id" => '= ' . $datas['user_id'],
                            "comment_id" => '= :comment_id'
                        ],
                    ],
                    'table' => ['comments']
                ];

                // unset($_SESSION['SANITIZED']);
                $this->deleteFromTable($params, $datas['comment_id']);
                $response = [
                    'status' => $errorCode,
                    'ok' => true
                ];
                $_SESSION[$datas['session_name']] = $response;

                return $response;
            }
        } catch (\Throwable $th) {
            // die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu supprimer cette recette ");

            $response = [
                'status' => $errorCode,
                'message' => $th->getMessage(),
                'ok' => false
            ];

            // Définit le statut HTTP de 200 à 500
            http_response_code($errorCode);
            return $response;
        }
    }

    /**
     * Insère les images path dans la TABLE images
     * @param array $getData
     * @throws \Error
     * @return void
     */
    protected function setImages($getData)
    {
        $loggedUser = LoginController::checkLoggedStatus();
        if (!isset($loggedUser)) {
            throw new Error("RCPLGGDUSROFF - Veuillez vous identifier avant de partager une recette.");
        } else {
            $checker = new CheckInput($getData);
            $sanitized_Datas = $checker->sanitizeData();

            if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {

                $fileName = $sanitized_Datas['fileName'];
                $filePath = $sanitized_Datas['filePath'];
                $recipeId = $sanitized_Datas['recipeId'];

                unset($_SESSION['SANITIZED']);

                $this->insertImages($recipeId, $loggedUser['userId'], $fileName, $filePath);
                $registeredImage = [
                    'email' => $loggedUser['email']
                ];
                $_SESSION['REGISTERED_IMG'] = $registeredImage;
            } else {
                echo json_encode($_SESSION['SANITIZED']);
                unset($_SESSION['SANITIZED']);
            }
        }
    }
}
