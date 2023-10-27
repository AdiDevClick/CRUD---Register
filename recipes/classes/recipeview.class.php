<?php

class RecipeView extends RecipeController
{
    public function insertRecipe()
    {
        return $this->insertRecipes();
    }

    public function deleteRecipe()
    {
        return $this->deleteRecipes();
    }

    public function displayShareSuccess(array $getDatas, array $loggedUser): void
    {
        $successMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
        $successMessage = '<section class="container">';
        $successMessage .= '<div class="form-flex">';
        $successMessage .= '<h1>Votre recette à bien été partagée !</h1>';
        $successMessage .= '<div class="card">';
        $successMessage .= '<div class="card-body">';
        $successMessage .= '<h5>Rappel de vos informations :</h5>';
        $successMessage .= '<p><b>Titre de votre recette</b> : ' . strip_tags($getDatas['title']) . '</p>';
        $successMessage .= '<p><b>Votre recette</b> : ' . strip_tags($getDatas['recipe']) . '</p>';
        $successMessage .= '<p><b>Crée par </b> : ' . strip_tags($loggedUser['email']) . '</p>';
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</section>';
        echo $successMessage;
    }
}
