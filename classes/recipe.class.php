<?php

class Recipe extends Mysql
{
    protected function setRecipeTest(
        string $title = null,
        string $description = null,
        string $step_1 = null,
        string $step_2 = null,
        string $step_3 = null,
        string $step_4 = null,
        string $step_5 = null,
        string $step_6 = null,
        int $total_time = null,
        string $total_time_length = null,
        int $resting_time = null,
        string $resting_time_length = null,
        int $oven_time = null,
        string $oven_time_length = null,
        string $ingredient = null,
        string $ingredient2 = null,
        string $ingredient3 = null,
        string $ingredient4 = null,
        string $ingredient5 = null,
        string $ingredient6 = null,
        string $persons = null,
        string $custom_ingredients = null,
        string $loggedUser
    ) {
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
        // $insertRecipe = $this->connect()->prepare($sqlQuery);

        if (!$insertRecipe->execute([
            'title' => $title,
            'description' => $description,
            'step_1' => $step_1,
            'step_2' => $step_2,
            'step_3' => $step_3,
            'step_4' => $step_4,
            'step_5' => $step_5,
            'step_6' => $step_6,
            'total_time' => $total_time,
            'total_time_length' => $total_time_length,
            'resting_time' => $resting_time,
            'resting_time_length' => $resting_time_length,
            'oven_time' => $oven_time,
            'oven_time_length' => $oven_time_length,
            'ingredient_1' => $ingredient,
            'ingredient_2' => $ingredient2,
            'ingredient_3' => $ingredient3,
            'ingredient_4' => $ingredient4,
            'ingredient_5' => $ingredient5,
            'ingredient_6' => $ingredient6,
            'persons' => $persons,
            'custom_ingredients' => $custom_ingredients,
            'author' => $loggedUser,
            'is_enabled' => 1,
        ])) {
            $insertRecipe = null;
            throw new Error("stmt Failed");
        }
        $id = $PDO_Instance->lastInsertId();
        return $id;
        // $recipe = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC);
        // print_r($insertRecipe);
        // $sqlQuery = 'SELECT LAST_INSERT_ID() FROM `recipes`;';
        // $insertRecipe = $this->connect()->prepare($sqlQuery);
        // $insertRecipe->execute();
    }

    /**
     * Summary of setRecipes
     * @param string $title
     * @param string $recipe
     * @throws Error
     * @return array
     */
    protected function setRecipes(string $title, string $step_1, string $step_2, string $step_3, string $step_4, string $step_5, string $step_6, string $loggedUser): void
    {
        $sqlQuery =
        'INSERT INTO recipes(title, step_1, step_2, step_3, step_4, step_5, step_6, author, is_enabled) 
        VALUES (:title, :step_1, :step_2, :step_3, :step_4, :step_5, :step_6, :author, :is_enabled);';

        $insertRecipe = $this->connect()->prepare($sqlQuery);

        if (!$insertRecipe->execute([
            'title' => $title,
            'step_1' => $step_1,
            'step_2' => $step_2,
            'step_3' => $step_3,
            'step_4' => $step_4,
            'step_5' => $step_5,
            'step_6' => $step_6,
            'author' => $loggedUser,
            'is_enabled' => 1,
        ])) {
            $insertRecipe = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
            //header("Location : ".$url->getThisUrl(). "?error=user-not-found");
        }
        /* $usersStatement = null;
        header("Location : ".Functions::getUrl(). "?error=stmt-failed");
        exit(); */
        exit;
    }

    /**
     * Summary of getRecipes
     * @return array
     */
    protected function getRecipes(): array
    {
        $sqlRecipesQuery = 'SELECT * FROM `recipes`;';
        $recipesStatement = $this->connect()->prepare($sqlRecipesQuery);
        if (!$recipesStatement->execute()) {
            $recipesStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
            //header("Location : ".getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($recipesStatement->rowCount() == 0) {
            $recipesStatement = null;
            //echo strip_tags("Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !");
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipe-not-found"));
            throw new Error("Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !");
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipes = $recipesStatement->fetchAll();
        return $recipes;
    }

    protected function getRecipesId(int $recipeId)
    {
        $sqlRecipe = 'SELECT title, recipe_id FROM recipes WHERE recipe_id = :id;';
        $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        if (!$getRecipesIdStatement->execute([
            'id' => $recipeId,
        ])) {
            $getRecipesIdStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
        }
        if ($getRecipesIdStatement->rowCount() == 0) {
            $getRecipesIdStatement = null;
            //echo strip_tags("Cette recette n'existe pas.");
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipeid-not-found"));
            throw new Error("Cette recette n'existe pas");
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipe = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC);
        return $recipe;
    }

    protected function getRecipesInfosById(int $recipeId)
    {
        // $sqlRecipe = 'SELECT title, recipe_id, step_1, step_2, step_3, step_4, step_5, step_6,
        // is_enabled, ingredient_1, ingredient_1, ingredient_2, ingredient_3, ingredient_4, ingredient_5, ingredient_6,
        // total_time, total_time_length, resting_time, resting_time_length, oven_time, oven_time_length, persons, custom_ingredients
        // FROM recipes WHERE recipe_id = :recipe_id;';
        $sqlRecipe =
        'SELECT *, DATE_FORMAT(i.created_at, "%d/%m/%Y") as image_date 
        FROM recipes r
        LEFT JOIN images i
        ON r.recipe_id = i.recipe_id
        WHERE r.recipe_id = :recipe_id;';
        $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        if (!$getRecipesIdStatement->execute([
            'recipe_id' => $recipeId,
        ])) {
            $getRecipesIdStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
        }
        /* if ($getRecipesIdStatement->rowCount() == 0) {
            $getRecipesIdStatement = null;
            echo strip_tags("Cette recette n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipeid-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        } */
        $recipe = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC);
        return $recipe;
    }
    protected function getIngredientsInfosById(int $recipeId)
    {
        $sqlRecipe = 'SELECT custom_ingredients
        FROM recipes WHERE recipe_id = :recipe_id;';
        $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        if (!$getRecipesIdStatement->execute([
            'recipe_id' => $recipeId,
        ])) {
            $getRecipesIdStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            echo json_encode(['status' => 'failed']);
        }
        $recipe = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC);
        echo json_encode($recipe);
        // echo json_encode(['status' => 'failed']);
        die();
        // print_r($recipe);
        // exit;
        // return json_encode($recipe);
    }

    protected function getRecipesTitles(string $recipes, array $optionnal): array
    {
        $limit = $optionnal['limit'];
        $optionnal['resetState'] == 1 ? $_SESSION['LAST_ID'] = 0 : null;

        // $optionnal['reset'] ? $_SESSION['LAST_ID'] = 0 : null;
        // echo "resetState => ". $optionnal['resetState'] . ' limit => ' . $limit . ' session id => ' . $_SESSION['LAST_ID'];

        // echo $_SESSION['LAST_ID'];
        // $reset = $optionnal['_reset'];
        $sqlRecipe = "SELECT r.recipe_id, r.title, r.author, i.img_path, i.youtubeID,
                MATCH title
                    AGAINST(:word IN BOOLEAN MODE) AS score
                FROM recipes r
                LEFT JOIN images i
                    ON i.recipe_id = r.recipe_id
                WHERE r.is_enabled = 1 AND r.recipe_id > :id
                HAVING score > 0
                ORDER BY r.recipe_id ASC
                LIMIT $limit;";
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


        // $recipesArray = $getRecipesIdStatement->fetchAll(PDO::FETCH_ASSOC);
        // echo json_encode($recipesArray);

        // if ($limit > 0) {
        //     for ($i = 0; $i < count($recipesArray); $i++) {
        //         array_slice($recipesArray, $i);
        //         return $recipesArray;
        //     }
        // } else {
        // return $recipesArray;
        // }
        // foreach ($recipe as $recipeItem) {
        //     echo json_encode($recipeItem);
        // }
        // echo json_encode($recipe);
    }

    protected function deleteRecipeId(int $recipeId)
    {
        $sqlQuery = 'DELETE FROM recipes WHERE recipe_id = :id;';
        $deteRecipeStatement = $this->connect()->prepare($sqlQuery);
        if (!$deteRecipeStatement->execute([
            'id' => $recipeId
        ])) {
            //echo "c'est pas delete !";
            $deteRecipeStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
        }
        if ($deteRecipeStatement->rowCount() == 0) {
            $deteRecipeStatement = null;
            //echo strip_tags("Cette recette ne peut pas être supprimée, elle n'existe pas.");
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=delrecipeid-not-found"));
            throw new Error("Cette recette ne peut pas être supprimée, elle n'existe pas.");
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        //header('Location: ../index.php');
        // exit;
    }

    protected function updateRecipes(
        string $title = null,
        string $description = null,
        string $step_1 = null,
        string $step_2 = null,
        string $step_3 = null,
        string $step_4 = null,
        string $step_5 = null,
        string $step_6 = null,
        int $total_time = null,
        string $total_time_length = null,
        int $resting_time = null,
        string $resting_time_length = null,
        int $oven_time = null,
        string $oven_time_length = null,
        string $ingredient = null,
        string $ingredient2 = null,
        string $ingredient3 = null,
        string $ingredient4 = null,
        string $ingredient5 = null,
        string $ingredient6 = null,
        string $persons = null,
        string $custom_ingredients = null,
        string $youtubeID = null,
        int $id
    ) {
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
                images.youtubeID = :youtubeID
            WHERE recipes.recipe_id = :recipe_id;';
        // $sqlQuery = 'UPDATE recipes SET
        //     title = :title,
        //     description = :description,
        //     step_1 = :step_1,
        //     step_2 = :step_2,
        //     step_3 = :step_3,
        //     step_4 = :step_4,
        //     step_5 = :step_5,
        //     step_6 = :step_6,
        //     total_time = :total_time,
        //     total_time_length = :total_time_length,
        //     resting_time = :resting_time,
        //     resting_time_length = :resting_time_length,
        //     oven_time = :oven_time,
        //     oven_time_length = :oven_time_length,
        //     ingredient_1 = :ingredient_1,
        //     ingredient_2 = :ingredient_2,
        //     ingredient_3 = :ingredient_3,
        //     ingredient_4 = :ingredient_4,
        //     ingredient_5 = :ingredient_5,
        //     ingredient_6 = :ingredient_6,
        //     persons = :persons,
        //     custom_ingredients = :custom_ingredients
        // WHERE recipe_id = :recipe_id;
        // UPDATE images SET
        //     youtubeID = :youtubeID
        // WHERE recipe_id = :recipe_id;';
        // $sqlQuery2 = 'UPDATE images SET
        //     youtubeID = :youtubeID
        // WHERE recipe_id = :recipe_id;';
        // echo 'test';
        $updateRecipeStatement = $this->connect()->prepare($sqlQuery);
        // $updateRecipeStatement2 = $this->connect()->prepare($sqlQuery2);
        // print_r($updateRecipeStatement2);
        if (!$updateRecipeStatement->execute([
            'title' => $title,
            'description' => $description,
            'step_1' => $step_1,
            'step_2' => $step_2,
            'step_3' => $step_3,
            'step_4' => $step_4,
            'step_5' => $step_5,
            'step_6' => $step_6,
            'total_time' => $total_time,
            'total_time_length' => $total_time_length,
            'resting_time' => $resting_time,
            'resting_time_length' => $resting_time_length,
            'oven_time' => $oven_time,
            'oven_time_length' => $oven_time_length,
            'ingredient_1' => $ingredient,
            'ingredient_2' => $ingredient2,
            'ingredient_3' => $ingredient3,
            'ingredient_4' => $ingredient4,
            'ingredient_5' => $ingredient5,
            'ingredient_6' => $ingredient6,
            'persons' => $persons,
            'custom_ingredients' => $custom_ingredients,
            'youtubeID' => $youtubeID,
            'recipe_id' => $id
        ])) {
            $updateRecipeStatement = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");
        }
        // if (!$updateRecipeStatement2->execute([
        //     'youtubeID' => $youtubeID,
        //     'recipe_id' => $id
        // ])) {
        //     $updateRecipeStatement2 = null;
        //     //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        //     throw new Error("stmt Failed");
        // }
        $status = 'success';
        if ($updateRecipeStatement->rowCount() == 0) {
            $updateRecipeStatement = null;
            //echo strip_tags("Cette recette ne peut pas être mise à jour, elle n'existe pas.");
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=uprecipeid-not-found"));
            // echo json_encode(['update_status' => 'RCPUPDTSTMTEXECNT']);
            $status = 'RCPUPDTSTMTEXECNT';
            // throw new Error("RCPUPDTSTMTEXECNT - Vous n'avez fait aucun changement.");

            // throw new Error("Cette recette ne peut pas être mise à jour, elle n'existe pas.");
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        // if ($updateRecipeStatement2->rowCount() == 0) {
        //     $updateRecipeStatement2 = null;
        //     $status = 'RCPUPDTSTMTEXECNT';
        // }
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

    /***
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
            //'user_id' => retrieve_id_from_user_mail($loggedUser['email'], $users),
        ])) {
            $insertCommentsStatment = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");

            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        // exit;
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
            // 'user_id' => retrieve_id_from_user_mail($loggedUser['email'], $users),
        ])) {
            $insertCommentsStatment = null;
            //throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            throw new Error("stmt Failed");

            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        // exit;
    }

    protected function deleteImageId(int $recipeId)
    {
        $sqlQuery = 'SELECT img_path FROM images WHERE recipe_id = :id;';
        $getRecipeStatement = $this->connect()->prepare($sqlQuery);
        if (!$getRecipeStatement->execute([
            'id' => $recipeId
        ])) {
            $getRecipeStatement = null;
            throw new Error("stmt Failed");
        }

        $image = $getRecipeStatement->fetchAll(PDO::FETCH_ASSOC);
        // print_r($image);
        if (isset($image[0]) && file_exists($image[0]['img_path'])) {
            unlink($image[0]['img_path']);
        }

        $sqlQuery = 'DELETE FROM images WHERE recipe_id = :id;';
        $deleteRecipeStatement = $this->connect()->prepare($sqlQuery);
        if (!$deleteRecipeStatement->execute([
            'id' => $recipeId
        ])) {
            $deleteRecipeStatement = null;
            throw new Error("stmt Failed");
        }
        // if ($deleteRecipeStatement->rowCount() == 0) {
        //     $deleteRecipeStatement = null;
        //     throw new Error("Cette recette ne peut pas être supprimée, elle n'existe pas.");
        // }
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
        return $recipe;
    }

    /* if (!isset($getData['id']) && is_numeric($getData['id']))
    {
        echo ('Il faut un identifiant de recette pour la modifier.');
        return;
    } */

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
