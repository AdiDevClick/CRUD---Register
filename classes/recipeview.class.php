<?php

class RecipeView extends RecipeController
{
    // public function insertRecipe()
    // {
    //     return $this->insertRecipes();
    // }

    public function setRecipe()
    {
        return $this->insertRecipe();
    }

    public function insertComment($getDatas)
    {
        return $this->setComments($getDatas);
    }

    public function fetchAverageRatingCommentsById($getDatas)
    {

        return $this->getAverageRatingCommentsById($getDatas);
    }

    public function fetchRecipesWithCommentsById($getDatas)
    {
        return $this->getRecipesWithCommentsById($getDatas);
    }

    public function insertImage($getDatas)
    {
        return $this->setImages($getDatas);
    }

    public function deleteImage($getDatas)
    {
        return $this->deleteImageId($getDatas);
    }

    public function fetchRecipesWithImagesById($getDatas)
    {
        return $this->getRecipesWithCommentsById($getDatas);
    }


    public function deleteRecipe()
    {
        return $this->deleteRecipes();
    }

    public function getRecipesTitle()
    {

        $recipe = $this->fetchRecipesTitle();
        return $recipe;
    }

    // public function getRecipeAuthorAndTitle()
    // {
    //     return $this->fetchesRecipeAuthorAndTitle();
    // }

    public function retrieveFromTable(array $params, string $sessionName)
    {
        return $this->fetchFromTable($params, $sessionName);
    }

    // public function getRecipeInfoById()
    // {
    //     return $this->fetchesRecipeInfosById();
    // }
    public function fetchIngredientsById()
    {
        $result = $this->fetchesIngredientsInfosById();
        return $result;
    }

    //public function updateRecipeInfoById($title, $recipe, $id)
    public function updateRecipeInfoById()
    {
        //return $this->updateRecipesInfosById($title, $recipe, $id);
        return $this->updateRecipesInfosById();
    }

    public function fetchAverageRatingById()
    {
        return $this->getAverageRatingById();
    }

    public function fetchCommentsById()
    {
        return $this->getCommentsById();
    }

    public function checkId()
    {
        return $this->checkIds();
    }
    public function displayShareSuccess(array $getDatas, array $loggedUser): void
    {
        //$title = "Clic'Répare - Partagé avec succès";
        $successMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
        //ob_start();
        $successMessage = '<section class="container">';
        $successMessage .= '<div class="form-flex">';
        $successMessage .= '<h1>Votre recette à bien été partagée !</h1>';
        $successMessage .= '<div class="card">';
        $successMessage .= '<div class="card-body">';
        $successMessage .= '<h5>Rappel de vos informations :</h5>';
        $successMessage .= '<p><b>Titre de votre recette</b> : ' . strip_tags($getDatas['title']) . '</p>';
        $successMessage .= '<p><b>Votre recette</b> : ' . strip_tags($getDatas['recipe']) . '</p>';
        $successMessage .= '<p><b>Crée par </b> : ' . strip_tags($loggedUser['email']['email']) . '</p>';
        $successMessage .= "<p>Vous serez redirigé vers la page d'accueil dans 10 secondes</p>";
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</section>';
        echo $successMessage;
        // foreach ($loggedUser as $user) {
        //     echo ($user) ;
        //     }
        // print_r ($loggedUser);


        //$content = ob_get_clean();
        //require('../templates/layout.php');
        //exit();
    }

    public function displayUpdateSuccess(array $getDatas, array $loggedUser): void
    {
        $successMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
        //ob_start();
        $successMessage = '<section class="container">';
        $successMessage .=  '<div class="form-flex">';
        $successMessage .=      '<h1>La modification de votre recette à bien été prise en compte !</h1>';
        $successMessage .=          '<div class="card">';
        $successMessage .=              '<div class="card-body">';
        $successMessage .=                  '<h5>Rappel de vos informations :</h5>';
        $successMessage .=                  '<p><b>Titre de votre recette</b> : ' . strip_tags($getDatas['title']) . '</p>';
        $successMessage .=                  '<p><b>Votre recette</b> : ' . strip_tags($getDatas['recipe']) . '</p>';
        $successMessage .=                  '<p><b>Crée par </b> : ' . strip_tags($loggedUser['email'][0]) . '</p>';
        $successMessage .=                  "<p>Vous serez redirigé vers la page d'accueil dans 10 secondes</p>";
        $successMessage .=              '</div>';
        $successMessage .=          '</div>';
        $successMessage .=      '</div>';
        $successMessage .= '</section>';
        echo $successMessage;
        //$content = ob_get_clean();
        //require('../templates/layout.php');
        //exit();
    }
    public function displayCommentSuccess(): void
    {
        $getData = [
            'comment' => $_POST['comment'],
            'recipeId' => $_POST['recipe_id']
        ];

        $this->setComments($getData);
        ob_start();
        //$recipeId = $postData['recipe_id'];
        $successMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
        $successMessage = '<section class="container">';
        $successMessage .= '<div class="form-flex">';
        $successMessage .= '<h1>Commentaire ajouté avec succès !</h1>';
        $successMessage .= '<div class="card">';
        $successMessage .= '<div class="card-body">';
        $successMessage .= '<h5>Rappel de vos informations :</h5>';
        $successMessage .= '<p class="card-text"><b>Votre commentaire</b> : ' . strip_tags($getData['comment']) . '</p>';
        $successMessage .= "<p>Vous serez redirigé vers la page d'accueil dans 10 secondes</p>";
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</div>';
        $successMessage .= '</section>';
        echo $successMessage;
        $content = ob_get_clean();
        require('../templates/layout.php');
        exit();
    }
    public function displayCommentForm($recipe)
    {
        /* $getData = [
            'comment' => $_POST['comment'],
            'recipeId' => $_POST['recipe_id']
        ];

        $this->setComments($getData); */

        $formMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
        $formMessage  = '<form action=" '.htmlentities($_SERVER['PHP_SELF']).' " method="POST">';
        $formMessage .= '<div class="mb-3 visually-hidden">';
        $formMessage .= '<input class="form-control" type="hidden" name="recipe_id" value=" '.$recipe['recipe_id'].' " />';
        $formMessage .= '</div>';
        $formMessage .= '<div class="mb-3">';
        $formMessage .= '<label for="comment" class="form-label">Postez un commentaire</label>';
        $formMessage .= '<textarea class="form-control" placeholder="Soyez respectueux/se, nous sommes humain(e)s." id="comment" name="comment"></textarea>';
        $formMessage .= '</div>';
        $formMessage .= '<button type="submit" class="btn btn-primary">Envoyer</button>';
        $formMessage .= '</form>';
        echo $formMessage;
    }
    private function displayComments($recipe, $getInfos)
    {
        $loggedUser = LoginController::checkLoggedStatus();
        $comments = '';
        if(count($recipe['comments']) > 0) {
            $comments = '<hr />';
            $comments .= '<h2>Commentaires</h2>';
            $comments .= '<div class="row">';
            foreach($recipe['comments'] as $comment) {
                $comments .= '<div class="comment">';
                $comments .= '<p> '.$comment['created_at'].' </p>';
                $comments .= '<p> '.($comment['comment']).'</p>';
                //$comments .= '<i>( '.$this->display_user($comment['user_id'], $loggedUser).' )</i>';
                $comments .= '<i>( '.$comment['user_id'].' )</i>';
                $comments .= '<p> </div>';
            }
            $comments .= '<p> </div>';
        }
        echo $comments;
    }

    public function display_user($userId)
    {
        $users = $this->getUsersById();
        foreach($users as $user) {
            if ($user['user_id'] === $userId) {
                return $user['full_name'];
                // return $user['full_name'] . '(' . $user['age'] . ' ans)';
            }
        }
        return 'Annonyme';
    }
}
