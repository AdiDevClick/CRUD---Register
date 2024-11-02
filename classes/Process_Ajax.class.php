<?php

class Process_Ajax
{
    private bool $isImageSent = false;
    private array $getDatas;
    private bool $isImageAlreadyOnServer = false;
    private string $send_Status = 'failed';

    public function __construct(
        private array $Post_Data,
        private array $Post_Files,
        private bool $is_Post,
        private $Get_Id,
    ) {

        if ($this->Post_Data) {

            // Setting array ready for DB insertion
            foreach ($this->Post_Data as $key => $value) {
                $this->getDatas[$key] = $value;
            }
            $this->getDatas['recipe_id'] = $this->Get_Id;

            // 'file' => $this->Post_Files['file']['name'],
            // Envoi toutes les données reçues au format JSON vers le serveur -
            // Les données ont préalablement été vérifiées en JavaScript mais
            // il ne faudra pas oublier de sanitize on use -
            // 'total_time' => $content,
            // Utiliser json_encode($array) pour permettre
            // de réencoder en JSON si nécessaire
            // 'persons' => json_encode($dataTest)
            // ];
            $setRecipe = new RecipeView($this->getDatas, 'creation');

            // Is it :
            // POST(true) ? => Insert recipe in DB / deletion of the recipe_id key
            // POST(false) ? This is an update request => Update DB from the recipe_id
            $this->is_Post ? $recipeId = $setRecipe->setRecipe() : $recipeId = $setRecipe->updateRecipeInfoById();
            
            $this->send_Status = 'success';

            // If no picture is chosen by the user, one is added by default
            if ($this->is_Post && empty($this->Post_Files['file']['name'])) {
                echo 'aucun fichier ajouté par l\`utilisateur';
                $this->insert_Default_File($setRecipe, $recipeId);
            }

            // Error during update
            if (isset($recipeId['status']) && $recipeId['status']['update_status'] === 'RCPUPDTSTMTEXECNT') {
                $this->send_Status = $recipeId['status']['update_status'];
                // $this->send_Status = 'RCPUPDTSTMTEXECNT';
                $recipeId = $recipeId['recipe_id'];
            }

            // Insert File in Table
            if ($this->Post_Files['file'] && $this->Post_Files['file']['error'] == 0) {
                $this->insert_File($setRecipe, $this->Post_Files, $recipeId);
            }
            echo json_encode(['status' => $this->send_Status, 'img_status' => $this->isImageSent, 'is_on_server' => $this->isImageAlreadyOnServer]);
        }
    }

    /**
     * Insertion d'un fichier dans la TABLE images
     * @param mixed $constructor_Data Instanciation du constructeur
     * @param array $file Fichier à récupérer
     * @param mixed $datas
     * @return void
     */
    private function insert_File($constructor_Data, array $file, mixed $datas)
    {
        // On vérifie que le fichier ne pèse pas plus d'10Mo
        if ($file['file']['size'] < 10 * 1024 * 1024) {
            // Récupération du user email, file name & extension
            $loggedUser = LoginController::checkLoggedStatus();
            $fileinfo = pathinfo($file['file']['name']);
            $extension = strtolower($fileinfo['extension']);
            // On vérifie les extensions autorisée
            $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
            // Nouveau nom
            $new_name = time() . '.' . $extension;
            // Création du path qui sera utilisé dans la table
            $database_Dir = 'uploads/'. $loggedUser['email'] . '/recipes_images/' . $datas;
            $file_In_Database = $database_Dir . '/' . $new_name;
            // Création du path qui sera utilisé pour déplacer le fichier
            $file_Upload_Dir = '../uploads/'. $loggedUser['email'] . '/recipes_images/' . $datas . '/';
            $file_In_Upload_Dir = $file_Upload_Dir . '/' . $new_name;
            
            try {
                if (isset($datas) && in_array($extension, $allowedExtensions)) {
                    // Création du dossier si besoin
                    makeDir($file_Upload_Dir);
                    // Déplacement du fichier temporaire et le renomme
                    move_uploaded_file($file['file']['tmp_name'], $file_In_Upload_Dir);
                    $this->isImageSent = true;
                    $image_Data = [
                        'recipeId' => $datas,
                        'fileName' => $new_name,
                        'filePath' => $file_In_Database
                    ];
                    // Delete data inside row
                    $constructor_Data->deleteImage($datas);
                    // Insert new data inside row
                    $constructor_Data->insertImage($image_Data);
                    $this->send_Status = 'success';
                } else {
                    $this->isImageSent = false;
                    // echo "Votre fichier n'est pas compatible";
                }
            } catch (\Throwable $error) {
                throw $error;
            }

        } else {
            $this->isImageAlreadyOnServer = true;
            // echo "Déjà envoyé..";
        }
    }

    /**
     * Insère une image par défaut dans le cas où
     * l'utilisateur n'en choisit aucune.
     * @param mixed $constructor_Data Instanciation du constructeur
     * @param int $data Le recipe_id
     * @return void
     */
    private function insert_Default_File($constructor_Data, int $data): void
    {
        // Récupération du user email
        $loggedUser = LoginController::checkLoggedStatus();
        // Création du path qui sera utilisé dans la table
        $new_name = 'default.jpeg';
        $database_Dir = 'uploads/'. $loggedUser['email'] . '/recipes_images/' . $data;
        $file_In_Database = $database_Dir . '/' . $new_name;
        // Création du path qui sera utilisé pour déplacer le fichier
        $file_Upload_Dir = '../uploads/'. $loggedUser['email'] . '/recipes_images/' . $data . '/';
        $file_In_Upload_Dir = $file_Upload_Dir . '/' . $new_name;
        // Création du dossier si besoin
        if (!file_exists($file_Upload_Dir)) {
            makeDir($file_Upload_Dir);
        }
        if (copy('../img/default-upload-file/default.jpeg', $file_In_Upload_Dir)) {
            echo "Le fichier a été copié avec succès.";
            $image_Data = [
                'recipeId' => $data,
                'fileName' => $new_name,
                'filePath' => $file_In_Database
            ];
            // Delete data inside row table if exists
            $constructor_Data->deleteImage($data);
            // Insert new data inside row table
            $constructor_Data->insertImage($image_Data);

            $this->isImageSent = true;
        } else {
            echo "Échec de la copie du fichier.";
        }
        // Success message
        $this->send_Status = 'success';
    }
}
