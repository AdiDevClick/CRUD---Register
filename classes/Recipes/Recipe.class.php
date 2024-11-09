<?php

class Recipe extends Mysql
{
    /**
     * Récupère les données d'une table de la base de données en fonction de l'ID de la recette.
     *
     * Cette méthode génère une requête SQL pour obtenir des données à partir de la table spécifiée,
     * utilise une instance de la classe `Database` pour exécuter la requête et retourne les résultats.
     *
     * @param array $params Tableau contenant les paramètres de la requête, y compris les champs et les tables.
     * @param int $recipeId L'identifiant de la recette.
     * @param bool $silentMode Mode silencieux pour la récupération des données (par défaut : false).
     * @return array Les données SQL récupérées.
     * @throws Error Si la recette n'existe pas.
     */
    protected function getFromTable(array $params, int $recipeId, bool $silentMode = false)
    {
        // Ajoute un message d'erreur aux paramètres si la recette n'existe pas
        // $params['error'] = ["Cette recette n'existe pas"];

        // Crée une instance de la classe Database avec des données optionnelles
        $Fetch = new Database(
            $this->optionnalData(),
            [
            'silentMode' => $silentMode
            ]
        );

        // Génère et exécute la requête SQL pour récupérer les données
        $SQLData = $Fetch->__createGetQuery($params, $recipeId, $this->connect());

        // Retourne les données SQL récupérées
        return $SQLData;
    }

    /**
     * Récupère les titres des recettes en fonction d'un mot clé et des options fournies.
     *
     * Cette fonction construit dynamiquement une requête SQL pour rechercher des recettes correspondant à un mot clé.
     * Les résultats peuvent inclure plusieurs champs et utiliser des jointures, des clauses MATCH, des conditions WHERE dynamiques, et des options de pagination.
     *
     * @param string $recipes Le mot clé à rechercher dans les titres de recettes.
     * @param array $optionnal Options supplémentaires pour la requête, y compris la limite et l'état de réinitialisation.
     * ```php
     * Exemple :
     *  $optionnal = [
     *      'limit' => 10,
     *      'resetState' => 1
     *  ];
     * ```
     * @return array Un tableau associatif contenant les informations des recettes trouvées.
     */
    protected function getRecipesTitles(string $recipes, array $optionnal): array
    {
        // Paramètres de la requête SQL
        $params = [
            "limit" => $optionnal['limit'],
            "fields" => ['r.recipe_id', 'r.title', 'r.author', 'i.img_path', 'i.youtubeID'],
            "match" => [
                'fields' => 'title',
                'against' => ':word'
            ],
            "join" => [
                'images i' => 'i.recipe_id = r.recipe_id'
            ],
            "where" => [
                "conditions" => [
                    'r.is_enabled' => '= 1',
                    'r.recipe_id' => '> :recipe_id'
                ],
                "logic" => 'AND'
            ],
            "order_by" => "r.recipe_id ASC",
            "word" => $recipes,
            "table" => ["recipes r"],
            "error" => ['Fetch Error']
        ];

        // Instancie la classe Database avec les options spécifiées
        $Fetch = new Database($this->optionnalData(), [
            'fetchAll' => true,
            'silentMode' => true
        ]);

        // Exécute la requête et retourne les résultats
        $SQLData = $Fetch->__createGetQuery($params, null, $this->connect(), $optionnal);
        return $SQLData;

        // $limit = $optionnal['limit'];
        // $optionnal['resetState'] == 1 ? $_SESSION['LAST_ID'] = 0 : null;
        // $sqlRecipe = "SELECT r.recipe_id, r.title, r.author, i.img_path, i.youtubeID,
        //         MATCH title
        //             AGAINST(:word IN BOOLEAN MODE) AS score
        //         FROM recipes r
        //         LEFT JOIN images i
        //             ON i.recipe_id = r.recipe_id
        //         WHERE r.is_enabled = 1 AND r.recipe_id > :id
        //         HAVING score > 0
        //         ORDER BY r.recipe_id ASC
        //         LIMIT $limit;";
        // $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        // if (!$getRecipesIdStatement->execute([
        //     'word' => $recipes . '*',
        //     'id' => $_SESSION['LAST_ID'],
        // ])) {
        //     $getRecipesIdStatement = null;
        //     throw new Error("stmt Failed");
        // }

        // if ($getRecipesIdStatement->rowCount() == 0) {
        //     $_SESSION['LAST_ID'] = 0;
        //     $getRecipesIdStatement = null;
        //     return $data = [];
        // }

        // if ($getRecipesIdStatement->rowCount() > 0) {
        //     while ($recipesArray = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC)) {
        //         if ($recipesArray['recipe_id'] > $_SESSION['LAST_ID']) {
        //             $_SESSION['LAST_ID'] = $recipesArray['recipe_id'];
        //             $data[] = $recipesArray;
        //         }
        //     }
        //     return $data;
        // }
    }

    /**
     * Insère les données dans la TABLE recipes
     * @param string $user Email de l'utilisateur.
     * @param array $data Un tableau de données à faire passer à la TABLE recipes
     * @throws \Error
     * @return bool|string
     */
    protected function setRecipes(string $user, array $data): bool|string
    {
        // Valeurs par défaut pour les étapes non présentes dans le tableau
        $steps = ['step_3', 'step_4', 'step_5', 'step_6'];
        foreach ($steps as $step) {
            if (!isset($data[$step])) {
                $data[$step] = '';
            }
        }

        $data['is_enabled'] = 1;
        $data['author'] = $user;

        $sqlQuery =
        'INSERT INTO recipes(
            title,
            description,
            step_1,
            step_2,
            step_3,
            step_4,
            step_5,
            step_6,
            total_time,
            total_time_length,
            resting_time,
            resting_time_length,
            oven_time,
            oven_time_length,
            ingredient_1,
            ingredient_2,
            ingredient_3,
            ingredient_4,
            ingredient_5,
            ingredient_6,
            persons,
            custom_ingredients,
            author,
            is_enabled)
        VALUES (
            :title,
            :description,
            :step_1,
            :step_2,
            :step_3,
            :step_4,
            :step_5,
            :step_6,
            :total_time,
            :total_time_length,
            :resting_time,
            :resting_time_length,
            :oven_time,
            :oven_time_length,
            :ingredient_1,
            :ingredient_2,
            :ingredient_3,
            :ingredient_4,
            :ingredient_5,
            :ingredient_6,
            :persons,
            :custom_ingredients,
            :author,
            :is_enabled);';
        $PDO_Instance = $this->connect();
        $insertRecipe = $PDO_Instance->prepare($sqlQuery);

        if (!$insertRecipe->execute($data)) {
            $insertRecipe = null;
            throw new Error(message: "stmt Failed");
        }

        // Retrieve the newly created recipe_ID and returns it
        $id = $PDO_Instance->lastInsertId();
        return $id;
    }

    /**
     * Permet d'update la TABLE recettes
     * @param array $data Les données des inputs
     * @throws \Error
     * @return array
     */
    protected function updateRecipes(array $data): array
    {
        // Valeurs par défaut pour les étapes non présentes dans le tableau
        $steps = ['step_3', 'step_4', 'step_5', 'step_6'];
        foreach ($steps as $step) {
            if (!isset($data[$step])) {
                $data[$step] = null;
            }
        }

        $sqlQuery = 'UPDATE recipes
            JOIN images ON recipes.recipe_id = images.recipe_id
            SET
                recipes.title = :title,
                recipes.description = :description,
                recipes.step_1 = :step_1,
                recipes.step_2 = :step_2,
                recipes.step_3 = :step_3,
                recipes.step_4 = :step_4,
                recipes.step_5 = :step_5,
                recipes.step_6 = :step_6,
                recipes.total_time = :total_time,
                recipes.total_time_length = :total_time_length,
                recipes.resting_time = :resting_time,
                recipes.resting_time_length = :resting_time_length,
                recipes.oven_time = :oven_time,
                recipes.oven_time_length = :oven_time_length,
                recipes.ingredient_1 = :ingredient_1,
                recipes.ingredient_2 = :ingredient_2,
                recipes.ingredient_3 = :ingredient_3,
                recipes.ingredient_4 = :ingredient_4,
                recipes.ingredient_5 = :ingredient_5,
                recipes.ingredient_6 = :ingredient_6,
                recipes.persons = :persons,
                recipes.custom_ingredients = :custom_ingredients,
                images.youtubeID = :video_link
            WHERE recipes.recipe_id = :recipe_id;';

        $updateRecipeStatement = $this->connect()->prepare($sqlQuery);

        if (!$updateRecipeStatement->execute($data)) {
            $updateRecipeStatement = null;
            throw new Error("stmt Failed");
        }
        $status = 'success';

        if ($updateRecipeStatement->rowCount() == 0) {
            $updateRecipeStatement = null;
            $status = 'RCPUPDTSTMTEXECNT';
        }

        return ['update_status' => $status];
    }

    /**
     * Summary of getRecipesWithCommentsById
     * @param mixed $recipeId
     * @throws \Error
     * @return array
     */
    public function getRecipesWithCommentsById($recipeId)
    {
        $sqlRecipe =
        'SELECT *, DATE_FORMAT(c.created_at, "%d/%m/%Y") as comment_date 
        FROM recipes r
        LEFT JOIN images i
            ON i.recipe_id = r.recipe_id
        LEFT JOIN comments c
            ON c.recipe_id = r.recipe_id
        WHERE r.recipe_id = :recipe_id;';
        $retrieveRecipeWithCommentsStatement = $this->connect()->prepare($sqlRecipe);
        if (!$retrieveRecipeWithCommentsStatement->execute([
            'recipe_id' => $recipeId,
        ])) {
            $retrieveRecipeWithCommentsStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
        }
        if ($retrieveRecipeWithCommentsStatement->rowCount() == 0) {
            $retrieveRecipeWithCommentsStatement = null;
            //echo strip_tags("Cette recette n'existe pas.");
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipeid-not-found"));
            throw new Error("Cette recette n'existe pas");
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipe = $retrieveRecipeWithCommentsStatement->fetchAll(PDO::FETCH_ASSOC);
        // print_r($recipe);
        return $recipe;
    }

    /**
     * Fetching reviews and rounding them by average
     */
    public function getAverageRatingCommentsById($recipeId)
    {
        $sqlRecipe =
        'SELECT ROUND(AVG(c.review),1) as rating 
        FROM recipes r 
        LEFT JOIN comments c 
            ON r.recipe_id = c.recipe_id 
        WHERE r.recipe_id = :id;';
        $averageRatingStatment = $this->connect()->prepare($sqlRecipe);
        if (!$averageRatingStatment->execute([
            'id' => $recipeId,
        ])) {
            $averageRatingStatment = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
        }
        if ($averageRatingStatment->rowCount() == 0) {
            $averageRatingStatment = null;
            //echo strip_tags("Cette recette n'existe pas.");
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipeid-not-found"));
            throw new Error("Cette recette n'existe pas.");
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipe = $averageRatingStatment->fetch(PDO::FETCH_ASSOC);
        return $recipe;
    }

    /**
     * Insert comments
     */
    protected function insertComments($comment, $recipeId, $userId)
    {
        $sqlRecipe = 'INSERT INTO comments(comment, recipe_id, user_id) VALUES (:comment, :recipe_id, :user_id);';
        $insertCommentsStatment = $this->connect()->prepare($sqlRecipe);
        if (!$insertCommentsStatment->execute([
            'comment' => $comment,
            'recipe_id' => $recipeId,
            'user_id' => $userId
        ])) {
            $insertCommentsStatment = null;
            throw new Error("stmt Failed");
        }
    }

    /**
     * Insert images
     */
    protected function insertImages($recipeId, int $userId, $imgName, $imgPath)
    {
        $sqlRecipe = 'INSERT INTO images(recipe_id, user_id, img_name, img_path) VALUES (:recipe_id, :user_id, :img_name, :img_path);';
        $insertCommentsStatment = $this->connect()->prepare($sqlRecipe);
        if (!$insertCommentsStatment->execute([
            'recipe_id' => $recipeId,
            'user_id' => $userId,
            'img_name' => $imgName,
            'img_path' => $imgPath
        ])) {
            $insertCommentsStatment = null;
            throw new Error("stmt Failed");
        }
    }

    /**
     * Supprime une image et son dossier associé à un identifiant de recette spécifique.
     *
     * Cette fonction récupère le chemin de l'image à partir de la base de données, supprime l'image du disque,
     * supprime le dossier associé si celui-ci est vide, puis supprime l'entrée correspondante de la base de données.
     *
     * @param int $recipeId L'identifiant de la recette.
     * @param bool $noChecks Default = false - Permet de ne pas renvoyer d'erreur si nécessaire.
     * @throws Error Si la suppression de l'image ou du dossier échoue, ou si la recette n'existe pas.
     */
    protected function deleteImageId(int $recipeId, bool $silentMode = false)
    {
        $params = [
            'fields' => ['img_path'],
            'table' => ['images i'],
            'error' => ["Impossible de récupérer cette image, elle n'existe pas"]
        ];

        $image = $this->getFromTable($params, $recipeId, $silentMode);

        if (isset($image['img_path']) && $image !== false && file_exists(dirname(__DIR__, 2) .'/'. $image['img_path'])) {

            // Suppression du fichier
            if (!unlink(dirname(__DIR__, 2) .'/'. $image['img_path'])) {
                throw new Error("Failed to delete image file.");
            }

            // Suppression du dossier
            $dirPath = dirname(__DIR__, 2) .'/'. dirname($image['img_path']);
            if (is_dir($dirPath) && count(scandir($dirPath)) == 2) {
                if (!rmdir($dirPath)) {
                    throw new Error("Failed to delete directory.");
                }
            }
        }

        $params = [
            "table" => ["images"],
            "error" => ["La suppression de cette image est impossible"]
        ];
        // Suppression de l'entrée dans la table
        $this->deleteFromTable($params, $recipeId, $silentMode);
    }

    /**
     * Supprime une entrée d'une ou plusieurs tables dans la base de données en fonction de l'ID de la recette.
     *
     * Cette fonction prend un tableau de paramètres et un identifiant de recette, et supprime la ligne correspondante
     * dans la ou les tables spécifiées.
     *
     * @param array $params Tableau contenant le nom de la table.
     * @param int $id L'identifiant de la recette.
     * @throws Error Si la requête SQL échoue ou si aucune ligne n'est affectée par la suppression.
     */
    protected function deleteFromTable(array $params, int $id, bool $silentMode = false)
    {
        // Crée une instance de la classe Database avec des données optionnelles
        $Fetch = new Database(null, [
            'silentMode' => $silentMode
        ]);

        // Génère et exécute la requête SQL pour récupérer les données
        $Fetch->__createDeleteQuery($params, $id, $this->connect());
    }


    /**
     * Find the Image assiociated by the recipe ID
     * @param array $recipeId
     * @return array
     */
    public function getRecipesWithImagesById($recipeId)
    {
        $sqlRecipe =
        'SELECT *, DATE_FORMAT(c.created_at, "%d/%m/%Y") as image_date
        FROM recipes r
        LEFT JOIN images c
        ON r.recipe_id = c.recipe_id
        WHERE r.recipe_id = :recipe_id;';
        $retrieveRecipeWithCommentsStatement = $this->connect()->prepare($sqlRecipe);
        if (!$retrieveRecipeWithCommentsStatement->execute([
            'recipe_id' => $recipeId,
        ])) {
            $retrieveRecipeWithCommentsStatement = null;
            throw new Error("stmt Failed");
        }
        if ($retrieveRecipeWithCommentsStatement->rowCount() == 0) {
            $retrieveRecipeWithCommentsStatement = null;
            throw new Error("Cette recette n'existe pas");
        }
        $recipe = $retrieveRecipeWithCommentsStatement->fetchAll(PDO::FETCH_ASSOC);
        return $recipe;
    }

    protected function getRecipesTitles2(string $recipes, array $optionnal): array
    {
        $limit = $optionnal['limit'];
        $optionnal['resetState'] == 1 ? $_SESSION['LAST_ID'] = 0 : null;

        // $optionnal['reset'] ? $_SESSION['LAST_ID'] = 0 : null;
        // echo "resetState => ". $optionnal['resetState'] . ' limit => ' . $limit . ' session id => ' . $_SESSION['LAST_ID'];

        // echo $_SESSION['LAST_ID'];
        // $reset = $optionnal['_reset'];
        $sqlRecipe = "SELECT *,
                MATCH title
                    AGAINST(:word IN BOOLEAN MODE) AS score
                FROM users r
                LEFT JOIN users2 i
                    ON i.recipe_id = r.recipe_id
                LEFT JOIN images i
                    ON i.recipe_id = r.recipe_id
                WHERE r.is_enabled = 1 AND r.recipe_id > :id
                HAVING score > 0
                ORDER BY r.recipe_id ASC
                LIMIT $limit;";
        // $sqlRecipe = "SELECT *,
        //         MATCH title
        //             AGAINST(:word IN BOOLEAN MODE) AS score
        //         FROM users r
        //         LEFT JOIN users2 i
        //             ON i.recipe_id = r.recipe_id
        //         LEFT JOIN images i
        //             ON i.recipe_id = r.recipe_id
        //         WHERE r.is_enabled = 1 AND r.recipe_id > :id
        //         HAVING score > 0
        //         ORDER BY r.recipe_id ASC
        //         LIMIT $limit;";
        // $sqlRecipe = 'SELECT *
        //     MATCH (r.title)
        //         AGAINST (:word IN BOOLEAN MODE) AS score
        //     FROM recipes r
        //     LEFT JOIN images i
        //         ON i.recipe_id = r.recipe_id
        //     ORDER BY score DESC;';
        $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        if (!$getRecipesIdStatement->execute([
            'word' => $recipes . '*',
            'id' => $_SESSION['LAST_ID'],
        ])) {
            $getRecipesIdStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
        }

        if ($getRecipesIdStatement->rowCount() == 0) {
            // echo json_encode($_SESSION['LAST_ID'] . ' => row zero ');
            $_SESSION['LAST_ID'] = 0;
            $getRecipesIdStatement = null;
            return $data = [];
            //echo strip_tags("Cette recette n'existe pas.");
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipetitle-not-found"));
            // throw new Error("Le titre de cette recette n'existe pas");
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }

        if ($getRecipesIdStatement->rowCount() > 0) {
            // $data = [];
            // output data of each row
            // $recipesArray = $getRecipesIdStatement->fetchAll(PDO::FETCH_ASSOC) ;
            while ($recipesArray = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC)) {
                // echo json_encode($recipesArray[0]['recipe_id']);
                // echo  json_encode($_SESSION['LAST_ID']);

                if ($recipesArray['recipe_id'] > $_SESSION['LAST_ID']) {
                    $_SESSION['LAST_ID'] = $recipesArray['recipe_id'];
                    // echo  json_encode($_SESSION['LAST_ID']);
                    // array_push($data, $recipesArray);
                    $data[] = $recipesArray;
                }
                // $_SESSION['LAST_ID'] = $recipesArray['recipe_id']+= $limite;
            }
            // echo json_encode($_SESSION['LAST_ID'] . ' => row > 0 ');
            return $data;
        }
    }
}
