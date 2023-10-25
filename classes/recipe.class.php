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
            Echo "Il n'existe aucune recette à ce jour. Soyez le premier à partager la votre !";
            throw new Error((string)header("Location: ".Functions::getUrl()."?error=recipe-not-found"));
            //header("Location :" .Functions::getUrl(). "?error=recipe-not-found");
            //exit();
        }  
        $recipes = $recipesStatement->fetchAll();
        return $recipes;
    }
}