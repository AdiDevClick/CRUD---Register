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
        $sqlRecipesQuery = 'SELECT * FROM `recipes`';
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

    public function getRecipeId(int $recipeId)
    {
        $sqlRecipe = 'SELECT * FROM recipes WHERE recipe_id = :id;';
        $getRecipesIdStatement = $this->connect()->prepare($sqlRecipe);
        if (!$getRecipesIdStatement->execute([
            'id' => $recipeId
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
        echo 'okay !';
        return $recipe;
    }

    public function deleteRecipeId(int $recipeId)
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
        echo "c'est delete !";
        //header('Location: ../index.php');
    }

    public function updateRecipes(string $title, string $recipe, string $id)
    {
        $sqlQuery = 'UPDATE recipes SET title = :title, recipe = :recipe WHERE recipe_id = :id;';

        $updateRecipeStatement = $this->connect()->prepare($sqlQuery);

        if (!$updateRecipeStatement->execute([
            'title' => $title,
            'recipe' => $recipe,
            'id' => $id,
        ])) {
            echo "c'est pas update!";
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
}
/* if (!isset($getData['id']) && is_numeric($getData['id']))
{
    echo ('Il faut un identifiant de recette pour la modifier.');
    return;
} */
