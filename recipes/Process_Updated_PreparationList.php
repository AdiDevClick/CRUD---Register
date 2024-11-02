<?php declare(strict_types=1);

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');

$fetchData = $_SERVER['REQUEST_METHOD'] === 'GET';
$data = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($data && isset($_POST)) {
    header('Content-Type: application/json; charset=utf-8');
    $content = file_get_contents("php://input");
    $dataTest = json_decode($content, true);
    $getDatas;

    if ($dataTest) {
        $getDatas = [
            // 'test' => $content,
            'title' => $dataTest['title'],
            'step_1' => $dataTest['step_1'],
            'step_2' => $dataTest['step_2'],
            'step_3' => $dataTest['step_3'],
            'step_4' => $dataTest['step_4'],
            'step_5' => $dataTest['step_5'],
            'step_6' => $dataTest['step_6'],
            'persons' => $dataTest['persons'],
            'total_time' => $dataTest['total_time'],
            'total_time_length' => $dataTest['total_time_length'],
            'resting_time' => $dataTest['resting_time'],
            'resting_time_length' => $dataTest['resting_time_length'],
            'oven_time' => $dataTest['oven_time'],
            'oven_time_length' => $dataTest['oven_time_length'],
            'ingredient' => $dataTest['ingredient'],
            'ingredient2' => $dataTest['ingredient2'],
            'ingredient3' => $dataTest['ingredient3'],
            'ingredient4' => $dataTest['ingredient4'],
            'ingredient5' => $dataTest['ingredient5'],
            'ingredient6' => $dataTest['ingredient6'],
            'custom_ingredients' => $dataTest['custom_ingredient'],
            'recipe_id' => $_GET['id'],
            'file' => $dataTest['file']
            // 'custom_ingredients' => $customIngredients,
            // Envoi toutes les données reçues au format JSON vers le serveur -
            // Les données ont préalablement été vérifiées en JavaScript mais
            // il ne faudra pas oublier de sanitize on use -
            // 'total_time' => $content,
            // Utiliser json_encode($array) pour permettre
            // de réencoder en JSON si nécessaire lors d'un renvoi vers JavaScript ou autre -
            // 'persons' => json_encode($dataTest)
            ];
        echo 'je suis là';
        $checkId = new RecipeView($getDatas);
        if ($checkId->checkId()) {
            $checkId->updateRecipeInfoById();
        } else {
            throw new Error(" Erreur de la mise à jour de votre recette ");
        }

        $err = CheckInput::getErrorMessages();
        if (count($err) > 0) {
            $errorMessage = CheckInput::showErrorMessage();
            $successMessage = '';
            $successMessage = '<div>';
            $successMessage .= '<p class="alert-error"> ' . strip_tags($errorMessage) . '</p>';
            $successMessage .= '</div>';
        } else {
        // echo json_encode($getDatas['success'] = 0);
        // return header('Location: ../index.php?success=recipe-shared');
        // echo 'window.location.href = ../index.php?success=recipe-shared';

        // echo header('refresh:10, ../index.php?success=recipe-shared');
        // session_destroy();
        }
    }
}

if ($fetchData && isset($_GET['id'])) {
    $getIdDatas = $_GET['id'];
    $setRecipe = new RecipeView($getIdDatas);
    $setRecipe->fetchIngredientsById();
}
