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
                $datas = $this->getFromTable($params, $this->getData);
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
            die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu récupérer cette recette ");
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

                // $sanitized_Datas = $this->sanitizeData($this->getData, 'video_link');
                $checker = new CheckInput($this->getData);
                $options = [
                    'key' => 'video_link',
                    'convert' => false
                ];
                // die('je suis ici');
                $sanitized_Datas = $checker->sanitizeData($options);
                // die('je suis passé ici');

                // $checkInput = new CheckInput(
                //     $this->getData
                // );

                // foreach ($this->getData as $key => $value) {
                //     if ($key !== 'video_link') {
                //         $sanitized_Datas[$key] = $checkInput->test_input($value);
                //     }
                // }

                // $checkInput->checkInputs();
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
                    // echo json_encode($checkInput->getErrorsArray());
                    unset($_SESSION['SANITIZED']);
                }

                return $data;
                // return $registeredRecipe;
            }
        } catch (Error $e) {
            die('Erreur : '. $e->getMessage() . ' , Insertion de la recette dans la DB impossible') ;
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
                'table' => ['recipes']
            ];
            $this->deleteFromTable($recipeParams, $this->getData);
            $this->deleteImageId($this->getData);
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . "Nous n'avons pas pu supprimer cette recette ");
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

                // $sanitized_Datas = $this->sanitizeData($this->getData);
                $checker = new CheckInput($this->getData);

                $options = [
                    'convert' => false
                ];
                $sanitized_Datas = $checker->sanitizeData($options);
                // $checkInput = new CheckInput(
                //     $this->getData
                // );

                // Sanitize every value
                // foreach ($this->getData as $key => $value) {
                //     $sanitized_Datas[$key] = $checkInput->test_input($value);
                // }
                // $checkInput->checkInputs();
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
                // if (empty($checkInput->getErrorsArray())) {
                //     $id = [];
                //     $update_Status = $this->updateRecipes($sanitized_Datas);
                //     $update_Status['update_status'] === 'success' ?
                //         null :
                //         $id = [
                //             'recipe_id' => $sanitized_Datas['recipe_id'],
                //             'status' => $update_Status,
                //             'query_type' => 'update'
                //         ];
                //     // echo json_encode(['update_status' => 'success']);
                //     // Sets infos inside the User Session
                //     $recipeId = [
                //         'updatedRecipeInfos' => $this->getData
                //     ];
                //     $_SESSION['UPDATED_RECIPE'] = $recipeId;
                //     return $id;
                // } else {
                //     echo json_encode($checkInput->getErrorsArray());
                // }
            }
        } catch (Error $e) {
            die('Erreur : ' . $e->getMessage() . " Nous n'avons pas pu mettre à jour cette recette ");
        }
    }

    protected function getAverageRatingById()
    {
        try {
            if (!isset($recipeId)) {
                $this->checkIds();

                // $data = $this->sanitizeData($this->getData);
                $checker = new CheckInput($this->getData);
                $data = $checker->sanitizeData();
                // $checkInput = new CheckInput(
                //     $this->getData
                // );

                // $id = $checkInput->test_input($this->getData["recipe_id"]);
                // $checkInput->checkInputs();
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

    /**
     * Sanitize les données passée en paramètre
     * @param array $datas Les données à sanitize
     * @param string $option Une clé à ne pas intégrer dans l'array
     * @return string[]
     */
    private function sanitizeData(array $datas, string $option = null)
    {
        $datas = is_array($datas) ? $datas : [$datas];

        $checkDatas = new CheckInput(
            $datas
        );

        foreach ($datas as $key => $value) {
            if ($option) {
                if ($key !== $option) {
                    $sanitized_Datas[$key] = $checkDatas->test_input($value);
                }
            } else {
                $sanitized_Datas[$key] = $checkDatas->test_input($value);
            }
        }

        $checkDatas->checkInputs();

        if (empty($checkDatas->getErrorsArray())) {
            $status = true;
            $_SESSION['SANITIZED'] = $status;
        } else {
            $status = false;
            $_SESSION['SANITIZED'] = $checkDatas->getErrorsArray();
        }

        return $sanitized_Datas;
    }

    /**
     * Insère les commentaires dans la TABLE comment
     * @param array $getData
     * @throws \Error
     * @return array
     */
    protected function setComments($getData)
    {
        $loggedUser = LoginController::checkLoggedStatus();
        if  (!isset($loggedUser)) {
            throw new Error("RCPLGGDUSROFF - Veuillez vous identifier avant de partager une recette.") ;
        } else {
            // $checkInput = new CheckInput(
            //     $this->getData
            // );

            // $message = $checkInput->test_input($getData['comment']);
            // $recipeId = $checkInput->test_input($getData['recipeId']);

            // $checkInput->checkInputs();

            // if (empty(CheckInput::getErrorsArray())) {
            //     $this->insertComments($message, $recipeId, $loggedUser['userId']);
            //     $registeredComment = [
            //         'email' => $loggedUser['email']
            //     ];
            //     $_SESSION['REGISTERED_COMMENT'] = $registeredComment;
            //     return $registeredComment;
            // }

            // $sanitized_Datas = $this->sanitizeData($getData);
            $checker = new CheckInput($this->getData);
            $options = [
                'convert' => false
            ];
            $sanitized_Datas = $checker->sanitizeData($options);

            if (isset($_SESSION['SANITIZED']) && $_SESSION['SANITIZED'] === true) {
                $message = $sanitized_Datas['comment'];
                $recipeId = $sanitized_Datas['recipeId'];

                unset($_SESSION['SANITIZED']);
                $this->insertComments($message, $recipeId, $loggedUser['userId']);
                $registeredComment = [
                    'email' => $loggedUser['email']
                ];
                $_SESSION['REGISTERED_COMMENT'] = $registeredComment;
                return $registeredComment;
            } else {
                echo json_encode($_SESSION['SANITIZED']);
                unset($_SESSION['SANITIZED']);
            }
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
        if  (!isset($loggedUser)) {
            throw new Error("RCPLGGDUSROFF - Veuillez vous identifier avant de partager une recette.") ;
        } else {
            // $checkInput = new CheckInput(
            //     $this->getData
            // );
            // $recipeId = $checkInput->test_input($getData['recipeId']);
            // $fileName = $getData['fileName'];
            // $filePath = $getData['filePath'];
            // $checkInput->checkInputs();
            // if (empty(CheckInput::getErrorsArray())) {
            //     $this->insertImages($recipeId, $loggedUser['userId'], $fileName, $filePath);
            //     $registeredImage = [
            //         'email' => $loggedUser['email']
            //     ];
            //     $_SESSION['REGISTERED_IMG'] = $registeredImage;
            //     //header("Location: ".Functions::getUrl()."?error=none") ; */
            //     // return $registeredImage;
            // }
            // $sanitized_Datas = $this->sanitizeData($getData);
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
