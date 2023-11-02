<?php

class Recipe extends Mysql
{
    /**
     * Summary of setRecipes
     * @param string $title
     * @param string $recipe
     * @throws Error
     * @return array
     */
    protected function setRecipes(string $title, string $recipe, string $loggedUser): void
    {
        $sqlQuery =
        'INSERT INTO recipes(title, recipe, author, is_enabled) 
        VALUES (:title, :recipe, :author, :is_enabled);';

        $insertRecipe = $this->connect()->prepare($sqlQuery);

        if (!$insertRecipe->execute([
            'title' => $title,
            'recipe' => $recipe,
            'author' => $loggedUser,
            'is_enabled' => 1,
        ])) {
            $insertRecipe = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            //header("Location : ".$url->getThisUrl(). "?error=user-not-found");
        }
        /* $usersStatement = null;
        header("Location : ".Functions::getUrl(). "?error=stmt-failed");
        exit(); */
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
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
            //header("Location : ".getUrl(). "?error=stmt-failed");
            //exit();
        }
        if ($recipesStatement->rowCount() == 0) {
            $recipesStatement = null;
            echo strip_tags("Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipe-not-found"));
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
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        }
        if ($getRecipesIdStatement->rowCount() == 0) {
            $getRecipesIdStatement = null;
            echo strip_tags("Cette recette n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipeid-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipe = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC);
        return $recipe;
    }

    protected function getRecipesInfosById(int $recipeId)
    {
        $sqlRecipe = 'SELECT title, recipe_id, recipe FROM recipes WHERE recipe_id = :recipe_id;';
        $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        if (!$getRecipesIdStatement->execute([
            'recipe_id' => $recipeId,
        ])) {
            $getRecipesIdStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
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

    protected function getRecipesTitles($recipes)
    {
        $sqlRecipe = 'SELECT * FROM recipes WHERE title = :title;';
        $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        if (!$getRecipesIdStatement->execute([
            'title' => $recipes
        ])) {
            $getRecipesIdStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        }
        if ($getRecipesIdStatement->rowCount() == 0) {
            $getRecipesIdStatement = null;
            echo strip_tags("Cette recette n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipetitle-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipe = $getRecipesIdStatement->fetch(PDO::FETCH_ASSOC);
        return $recipe;
    }

    protected function deleteRecipeId(int $recipeId)
    {
        $sqlQuery = 'DELETE FROM recipes WHERE recipe_id = :id;';
        $deteRecipeStatement = $this->connect()->prepare($sqlQuery);
        if (!$deteRecipeStatement->execute([
            'id' => $recipeId
        ])) {
            echo "c'est pas delete !";
            $deteRecipeStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        }
        if ($deteRecipeStatement->rowCount() == 0) {
            $deteRecipeStatement = null;
            echo strip_tags("Cette recette ne peut pas être supprimée, elle n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=delrecipeid-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        //header('Location: ../index.php');
    }

    protected function updateRecipes(string $title, string $recipe, $id)
    {
        $sqlQuery = 'UPDATE recipes SET title = :title, recipe = :recipe WHERE recipe_id = :recipe_id;';

        $updateRecipeStatement = $this->connect()->prepare($sqlQuery);

        if (!$updateRecipeStatement->execute([
            'title' => $title,
            'recipe' => $recipe,
            'recipe_id' => $id,
        ])) {
            $updateRecipeStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        }
        if ($updateRecipeStatement->rowCount() == 0) {
            $updateRecipeStatement = null;
            echo strip_tags("Cette recette ne peut pas être mise à jour, elle n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=uprecipeid-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
    }

    public function getRecipesWithCommentsById($recipeId)
    {
        $sqlRecipe = 
        'SELECT *, DATE_FORMAT(c.created_at, "%d/%m/%Y") as comment_date 
        FROM recipes r 
        LEFT JOIN comments c             
        ON r.recipe_id = c.recipe_id 
        WHERE r.recipe_id = :recipe_id;';
        $retrieveRecipeWithCommentsStatement = $this->connect()->prepare($sqlRecipe);
        if (!$retrieveRecipeWithCommentsStatement->execute([
            'recipe_id' => $recipeId,
        ])) {
            $retrieveRecipeWithCommentsStatement = null;
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        }
        if ($retrieveRecipeWithCommentsStatement->rowCount() == 0) {
            $retrieveRecipeWithCommentsStatement = null;
            echo strip_tags("Cette recette n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipeid-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipe = $retrieveRecipeWithCommentsStatement->fetchAll(PDO::FETCH_ASSOC);
        return $recipe;
    }
    /***
     * Fetching reviews avec rounding them by average
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
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        }
        if ($averageRatingStatment->rowCount() == 0) {
            $averageRatingStatment = null;
            echo strip_tags("Cette recette n'existe pas.");
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipeid-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
        $recipe = $averageRatingStatment->fetch(PDO::FETCH_ASSOC);
        return $recipe;
    }
    /***
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
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=stmt-failed"));
        
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }
    }
}
/* if (!isset($getData['id']) && is_numeric($getData['id']))
{
    echo ('Il faut un identifiant de recette pour la modifier.');
    return;
} */
