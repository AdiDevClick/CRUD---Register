<?php

class Process_Ajax
{
    private bool $isImageSent = false;
    private array $getDatas;
    private bool $isImageAlreadyOnServer = false;
    private string $send_Status = 'failed';
    private $Recipe;

    public function __construct(
        private array $Post_Data,
        private array $Post_Files,
        private bool $is_Post,
        private string $session,
        private $Get_Id,
    ) {

        if ($this->Post_Data) {
            // Setting array ready for DB insertion
            foreach ($this->Post_Data as $key => $value) {
                $this->getDatas[$key] = $value;
            }
            $this->getDatas['recipe_id'] = $this->Get_Id;
            // Envoi toutes les données reçues au format JSON vers le serveur -
            // Les données ont préalablement été vérifiées en JavaScript mais
            // il ne faudra pas oublier de sanitize on use -
            // 'total_time' => $content,
            // Utiliser json_encode($array) pour permettre
            // de réencoder en JSON si nécessaire
            // 'persons' => json_encode($dataTest)
            // ];
            // die(json_encode($this->getDatas));
            $this->Recipe = new RecipeView($this->getDatas, 'creation');
            $this->send_Status = 'success';

            // POST(true) ?
            // Insert file and recipe in DB
            if ($this->is_Post && $this->session === 'REGISTERED_RECIPE' && !isset($_SESSION['UPDATED_RECIPE'])) {
                // Database INSERT

                $recipeId = $this->Recipe->setRecipe();
                // If no picture is chosen by the user during creation, one is added by default
                if (empty($this->Post_Files['file']['name'])) {
                    $default = true;
                    $this->insert_Default_File((int) $recipeId);
                }
                // Insert File in Table
                if ($this->Post_Files['file'] && $this->Post_Files['file']['error'] == 0) {
                    $this->insert_File($this->Post_Files, (int) $recipeId);
                }
            }

            // POST(false) ?
            // This is an update request => Update DB from the recipe_id
            if (!$this->is_Post && $this->session === 'UPDATED_RECIPE') {
                // Database UPDATE
                $recipeId = $this->Recipe->updateRecipeInfoById();
                // Insert File in Table
                if ($this->Post_Files['file'] && $this->Post_Files['file']['error'] == 0) {
                    $this->insert_File($this->Post_Files, $this->Get_Id);
                }
            }

            // Error during update
            if (isset($recipeId['status']) && $recipeId['status']['update_status'] === 'RCPUPDTSTMTEXECNT') {
                $this->send_Status = $recipeId['status']['update_status'];
                // $this->send_Status = 'RCPUPDTSTMTEXECNT';
                $recipeId = $recipeId['recipe_id'];
            }

            // Sends data to client
            echo json_encode([
                'status' => $this->send_Status,
                'img_status' => $this->isImageSent,
                'is_on_server' => $this->isImageAlreadyOnServer,
                'default_image' => $default ?? false
            ]);
        }
    }

    /**
     * Insertion d'un fichier dans la TABLE images
     * @param mixed $constructor_Data Instanciation du constructeur
     * @param array $file Fichier à récupérer
     * @param int $datas
     * @return void
     */
    private function insert_File(array $file, int $id)
    {
        // On vérifie que le fichier ne pèse pas plus d'10Mo
        if ($file['file']['size'] < 10 * 1024 * 1024) {
            // Récupération du user email, file name & extension
            $loggedUser = LoginController::checkLoggedStatus();
            $fileinfo = pathinfo($file['file']['name']);
            $extension = strtolower($fileinfo['extension']);
            // On vérifie les extensions autorisée
            $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
            // Création du path qui sera utilisé dans la table
            $data = $this->setPath(time() . '.' . $extension, $id, $loggedUser['email']);

            try {
                if (isset($id) && in_array($extension, $allowedExtensions)) {
                    // Création du dossier si besoin
                    makeDir($data['disk_dir']);
                    // Déplacement du fichier temporaire et le renomme
                    move_uploaded_file($file['file']['tmp_name'], $data['disk_dir_path']);
                    $this->isImageSent = true;
                    $image_Data = [
                        'recipeId' => $id,
                        'fileName' => $data['name'],
                        'filePath' => $data['database_dir']
                    ];

                    // Delete data inside row
                    $this->Recipe->deleteImage($id, true);
                    // Insert new data inside row
                    $this->Recipe->insertImage($image_Data);

                    $this->send_Status = 'success';
                } else {
                    $this->isImageSent = false;
                    $this->send_Status = 'failed';
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
     * @param int $id Le recipe_id
     * @return void
     */
    private function insert_Default_File(int $id): void
    {
        // Récupération du user email
        $loggedUser = LoginController::checkLoggedStatus();
        // Création du path qui sera utilisé dans la table
        $data = $this->setPath('default.jpeg', $id, $loggedUser['email']);

        if (copy('../img/default-upload-file/default.jpeg', $data['disk_dir_path'])) {
            $image_Data = [
                'recipeId' => $id,
                'fileName' => $data['name'],
                'filePath' => $data['database_dir']
            ];
            // Delete data inside row table if exists
            $this->Recipe->deleteImage($id, true);
            // Insert new data inside row table
            $this->Recipe->insertImage($image_Data);

            $this->isImageSent = true;
            // Success message
            $this->send_Status = 'success';
        } else {
            $this->isImageSent = false;
            $this->send_Status = 'failed';
            // echo "Échec de la copie du fichier.";
        }
    }

    /**
     * Définit les chemins pour le stockage et la base de données d'une image de recette.
     *
     * Cette fonction crée les chemins pour le stockage du fichier sur le disque et dans la base de données.
     * Elle crée également le dossier de destination sur le disque si celui-ci n'existe pas déjà.
     *
     * @param string $name Le nom du fichier image.
     * @param int $id L'identifiant de la recette.
     * @param string $user Le nom d'utilisateur.
     * @return array Un tableau associatif contenant les chemins pour la base de données et le disque.
     * - 'database_dir' : Le chemin du fichier dans la base de données.
     * - 'disk_dir' : Le chemin du dossier sur le disque où le fichier sera stocké.
     * - 'disk_dir_path' : Le chemin complet du fichier sur le disque.
     * - 'name' : Le nom du fichier.
     * */
    private function setPath(string $name, int $id, string $user): array
    {
        // Création du path qui sera utilisé dans la table
        $new_name = $name;
        $database_Dir = 'uploads/' . $user . '/recipes_images/' . $id;
        $file_In_Database = $database_Dir . '/' . $new_name;

        // Création du path qui sera utilisé pour déplacer le fichier
        $file_Upload_Dir = '../uploads/' . $user . '/recipes_images/' . $id . '/';
        $file_In_Upload_Dir = $file_Upload_Dir . '/' . $new_name;

        // Vérifie si le dossier existe, sinon le crée
        if (!file_exists($file_Upload_Dir)) {
            makeDir($file_Upload_Dir);
        }

        // Retourne les informations sur les chemins créés
        $datas = [
            'database_dir' => $file_In_Database,
            'disk_dir' => $file_Upload_Dir,
            'disk_dir_path' => $file_In_Upload_Dir,
            'name' => $new_name
        ];

        return $datas;
    }
}
