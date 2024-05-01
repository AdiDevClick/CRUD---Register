<?php

declare(strict_types=1);

include_once("../includes/class-autoloader.inc.php");

$data = $_SERVER['REQUEST_METHOD'] === 'POST';
// if (isset($_POST)) {
//     // $content = file_get_contents("php://input");
//     // $test = $_REQUEST;
//     // header('Content-Type: application/json');
//     // $content = trim(file_get_contents("php://input"));
//     // $dataTest = json_encode($content, true);
//     // echo $dataTest['persons'];
//     // echo $dataTest;
//     // print_r(json_decode($dataTest['persons']));
//     // print_r($content);
//     // print_r(json_decode($dataTest));
//     // $data = json_decode($content, true);
//     // $data['success'] = true;
//     // $setRecipe = new RecipeView($data);
//     // $setRecipe->insertRecipe();
//     // echo json_encode($data['persons']);
//     // foreach ($test as $key => $value) {
//     //     echo" key => ". $key ." value => ". $value ." ";
//     // }
//     echo ($_POST['persons']);
//     // echo json_encode($data['persons']);
//     // echo json_encode($data);
//     // print_r($data);
//     // $test = json_encode($data);
//     // $loggedUser = LoginController::checkLoggedStatus();
//     // echo json_encode($loggedUser);
// }
if ($data && isset($_POST)) {
    $content = file_get_contents("php://input");
    $dataTest = json_decode($content, true);
    echo $content;
    if ($dataTest) {
        $getDatas = [
            'test' => $content,
            // 'recipe' => json_encode($dataTest['total_time']),
            // 'persons' => json_encode($dataTest['persons'])
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
            // envoi toutes les données reçues en json
            // Il ne faudra pas oublier de sanitize on use
            // 'total_time' => $content,
            // Utiliser json_encode($array) pour permettre
            // de réencoder en JSON si nécessaire
            // 'persons' => json_encode($dataTest)
            ];
        // $setRecipe = new RecipeView($dataTest);
        $setRecipe = new RecipeView($getDatas);
        $setRecipe->insertRecipeTest();
        echo $dataTest['persons'];
    }
}
