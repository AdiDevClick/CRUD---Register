<?php

class RecipeView extends RecipeController
{
    public function insertRecipes($title, $recipe, $loggedUser)
    {
        return $this->setRecipes($title, $recipe, $loggedUser);
    }

    public function displayShareSuccess(string $title, string $recipe, string $loggedUser): string
    {
        $successMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
        $successMessage = '<section class="container">';
        $successMessage .= '<div class="form-flex">';
        $successMessage .= '<h1>Votre recette à bien été partagée !</h1>';
        $successMessage .= '<div class="card">';
        $successMessage .= '<div class="card-body">';
        $successMessage .= '<h5>Rappel de vos informations :</h5>';
        $successMessage .= '<p><b>Titre de votre recette</b> : ' . strip_tags($title) . '</p>';
        $successMessage .= '<p><b>Votre recette</b> : ' . strip_tags($recipe) . '</p>';
        $successMessage .= '<p><b>Crée par </b> : ' . strip_tags($loggedUser['email']) . '</p>';
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</section>';
        echo $successMessage;
    }
}