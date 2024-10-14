<?php

class Process_Ajax
{
    private bool $isImageSent = false;
    private array $getDatas;
    private bool $isImageAlreadyOnServer = false;
    private string $send_Status;

    public function __construct(
        private array $Post_Data,
        private array $Post_Files,
        private bool $is_Post,
        private $Get_Id,
    ) {

        // $isImageSent = $this->isImageSent;
        // $isImageAlreadyOnServer = $this->isImageAlreadyOnServer;

        if ($this->Post_Data) {
            $this->getDatas = [
                'title' => $this->Post_Data['title'],
                'description' => $this->Post_Data['description'],
                'step_1' => $this->Post_Data['step_1'],
                'step_2' => $this->Post_Data['step_2'],
                'step_3' => $this->Post_Data['step_3'] ?? null,
                'step_4' => $this->Post_Data['step_4'] ?? null,
                'step_5' => $this->Post_Data['step_5'] ?? null,
                'step_6' => $this->Post_Data['step_6'] ?? null,
                'persons' => $this->Post_Data['persons'],
                'total_time' => $this->Post_Data['total_time'],
                'total_time_length' => $this->Post_Data['total_time_length'],
                'resting_time' => $this->Post_Data['resting_time'],
                'resting_time_length' => $this->Post_Data['resting_time_length'],
                'oven_time' => $this->Post_Data['oven_time'],
                'oven_time_length' => $this->Post_Data['oven_time_length'],
                'ingredient' => $this->Post_Data['ingredient'],
                'ingredient2' => $this->Post_Data['ingredient2'],
                'ingredient3' => $this->Post_Data['ingredient3'],
                'ingredient4' => $this->Post_Data['ingredient4'],
                'ingredient5' => $this->Post_Data['ingredient5'],
                'ingredient6' => $this->Post_Data['ingredient6'],
                'custom_ingredients' => $this->Post_Data['custom_ingredient'],
                'youtubeID' => $this->Post_Data['video_link'],
                // 'file' => $this->Post_Files['file']['name'],
                // $this->Get_Id ?: 'recipe_id' => $this->Get_Id
                'recipe_id' => $this->Get_Id
                // Envoi toutes les données reçues au format JSON vers le serveur -
                // Les données ont préalablement été vérifiées en JavaScript mais
                // il ne faudra pas oublier de sanitize on use -
                // 'total_time' => $content,
                // Utiliser json_encode($array) pour permettre
                // de réencoder en JSON si nécessaire
                // 'persons' => json_encode($dataTest)
            ];
            // print_r($this->is_Post);
            $setRecipe = new RecipeView($this->getDatas, 'creation');
            $this->is_Post ? $recipeId = $setRecipe->insertRecipeTest() : $recipeId = $setRecipe->updateRecipeInfoById();
            $this->send_Status = 'success';
            if (isset($recipeId['status']) && $recipeId['status']['update_status'] === 'RCPUPDTSTMTEXECNT') {
                $this->send_Status = 'RCPUPDTSTMTEXECNT';
                $recipeId = $recipeId['recipe_id'];
            }

            // print_r($recipeId);
            // echo json_encode($this->send_Status);

            // return;

            if ($this->Post_Files['file'] && $this->Post_Files['file']['error'] == 0) {
                // On vérifie que le fichier ne pèse pas plus d'10Mo
                if ($this->Post_Files['file']['size'] < 10 * 1024 * 1024) {
                    $loggedUser = LoginController::checkLoggedStatus();
                    $fileinfo = pathinfo($this->Post_Files['file']['name']);
                    $extension = strtolower($fileinfo['extension']);
                    // On vérifie les extensions autorisée
                    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
                    $new_name = time() . '.' . $extension;
                    $database_Dir = 'uploads/'. $loggedUser['email'] . '/recipes_images/' . $recipeId;
                    $file_In_Database = $database_Dir . '/' . $new_name;

                    $file_Upload_Dir = '../uploads/'. $loggedUser['email'] . '/recipes_images/' . $recipeId . '/';
                    $file_In_Upload_Dir = $file_Upload_Dir . '/' . $new_name;
                    // echo($dir);
                    // echo($fileUploadDir);
                    if (isset($recipeId) && in_array($extension, $allowedExtensions)) {
                        makeDir($file_Upload_Dir);
                        move_uploaded_file($this->Post_Files['file']['tmp_name'], $file_In_Upload_Dir);
                        $this->isImageSent = true;
                        $image_Data = ['recipeId' => $recipeId, 'fileName' => $new_name, 'filePath' => $file_In_Database];
                        $setRecipe->deleteImage($recipeId);
                        // if ($recipeId) $setRecipe->deleteImage($recipeId);
                        $setRecipe->insertImage($image_Data);
                        $this->send_Status = 'success';
                    } else {
                        $this->isImageSent = false;
                        // echo "Votre fichier n'est pas compatible";
                    }
                } else {
                    $this->isImageAlreadyOnServer = true;
                    // echo "Déjà envoyé..";
                }
                // print_r($this->$Post_Files['file']);
            }
            echo json_encode(['status' => $this->send_Status, 'img_status' => $this->isImageSent, 'is_on_server' => $this->isImageAlreadyOnServer]);
        }
    }
}
