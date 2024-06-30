<?php

declare(strict_types=1);

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');

// header('Content-Type: application/json; charset=utf-8');

$fetchData = $_SERVER['REQUEST_METHOD'] === 'GET';
$data = $_SERVER['REQUEST_METHOD'] === 'POST';
// print_r($_REQUEST);
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
// print_r($data);
if ($data && isset($_POST)) {
    // header('Content-Type: image/jpeg');
    header('Content-Type: application/json; charset=utf-8');
    $content = file_get_contents("php://input");
    $dataTest = json_decode($content, true);
    $getDatas;
    $isImageSent;
    $isImageAlreadyOnServer;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // On vérifie que le fichier ne pèse pas plus d'10Mo
        if ($_FILES['file']['size'] < 10 * 1024 * 1024) {
            $fileinfo = pathinfo($_FILES['file']['name']);
            // $fileinfo = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $extension = strtolower($fileinfo['extension']);
            // On vérifie les extensions autorisée
            $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
            $new_name = time() . '.' . $extension;
            // move_uploaded_file($_FILES['sample_image']['tmp_name'], 'images/' . $new_name);
            
            if (in_array($extension, $allowedExtensions)) {
                move_uploaded_file($_FILES['file']['tmp_name'], '../uploads/' . $new_name) ;
                // move_uploaded_file($_FILES['file']['tmp_name'], '../uploads/' . basename($_FILES['file']['name'])) ;
                // echo "L'envoi a bien été effectué !";
                // $encoded_data = [
                //     // 'image_source' => '../uploads/' . $new_name
                //     'imageSent' => true
                // ];
                $isImageSent = true;
                // $encoded_data = json_encode($sent_image);
                // echo $encoded_data."\n";
            } else {
                $isImageSent = false;
                // $encoded_data = ['imageSent' => false];
                echo "Votre fichier n'est pas compatible";
            }
        } else {
            $isImageAlreadyOnServer = true;
            // $isImageAlreadyOnServer = ['isSentAlready' => true];
            echo("Déjà envoyé..");
        }
    }
    // if ($_FILES['file']['size'] < 1000000) {
    //     $fileinfo = pathinfo($_FILES['file']['name']);
        // $fileinfo = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
    //     echo $fileinfo;
    //     $extension = $fileinfo['extension'];
    //     $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    //     if (in_array($extension, $allowedExtensions)) {
    //         move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . basename($_FILES['file']['name'])) ;
    //         echo "L'envoi a bien été effectué !";
    //     } else {
    //         echo "Votre fichier n'est pas compatible";
    //     }
    // } else {
    //     echo("Déjà envoyé..");
    // }
// }
    // exit;
    if ($dataTest || isset($_POST)) {
        $getDatas = [
            // 'test' => $content,
            // 'recipe' => json_encode($dataTest['total_time']),
            // 'persons' => json_encode($dataTest['persons'])
            'title' => $dataTest['title'] ?? $_POST['title'],
            'step_1' => $dataTest['step_1'] ?? $_POST['step_1'],
            'step_2' => $dataTest['step_2'] ?? $_POST['step_2'],
            'step_3' => $dataTest['step_3'] ?? $_POST['step_3'],
            'step_4' => $dataTest['step_4'] ?? $_POST['step_4'],
            'step_5' => $dataTest['step_5'] ?? $_POST['step_5'],
            'step_6' => $dataTest['step_6'] ?? $_POST['step_6'],
            'persons' => $dataTest['persons'] ?? $_POST['persons'],
            'total_time' => $dataTest['total_time'] ?? $_POST['total_time'],
            'total_time_length' => $dataTest['total_time_length'] ?? $_POST['total_time_length'],
            'resting_time' => $dataTest['resting_time'] ?? $_POST['resting_time'],
            'resting_time_length' => $dataTest['resting_time_length'] ?? $_POST['resting_time_length'],
            'oven_time' => $dataTest['oven_time'] ?? $_POST['oven_time'],
            'oven_time_length' => $dataTest['oven_time_length'] ?? $_POST['oven_time_length'],
            'ingredient' => $dataTest['ingredient'] ?? $_POST['ingredient'],
            'ingredient2' => $dataTest['ingredient2'] ?? $_POST['ingredient2'],
            'ingredient3' => $dataTest['ingredient3'] ?? $_POST['ingredient3'],
            'ingredient4' => $dataTest['ingredient4'] ?? $_POST['ingredient4'],
            'ingredient5' => $dataTest['ingredient5'] ?? $_POST['ingredient5'],
            'ingredient6' => $dataTest['ingredient6'] ?? $_POST['ingredient6'],
            'custom_ingredients' => $dataTest['custom_ingredient'] ?? $_POST['custom_ingredient'],
            'file' => $dataTest['file'] ?? $_FILES['file']['name'],
            'img_status' => $isImageSent,
            'img_on_server' => $isImageAlreadyOnServer,
            // 'custom_ingredients' => $customIngredients,
            // Envoi toutes les données reçues au format JSON vers le serveur -
            // Les données ont préalablement été vérifiées en JavaScript mais
            // il ne faudra pas oublier de sanitize on use -
            // 'total_time' => $content,
            // Utiliser json_encode($array) pour permettre
            // de réencoder en JSON si nécessaire
            // 'persons' => json_encode($dataTest)
            ];
            // print_r($getDatas);
        $setRecipe = new RecipeView($getDatas);
        $setRecipe->insertRecipeTest();

        $err = CheckInput::getErrorMessages();
        if (count($err) > 0) {
            // print_r($err);
            $errorMessage = CheckInput::showErrorMessage();

            $successMessage = '';
            //if (isset($_SESSION['REGISTERED_USER'])) {
            //ob_start();
            $successMessage = '<div>';
            $successMessage .= '<p class="alert-error"> ' . strip_tags($errorMessage) . '</p>';
            $successMessage .= '</div>';
            // echo json_encode($successMessage);
            // print $getDatas['failed'] = true;
            // echo $getDatas['failed'] = 1;
            // echo json_encode($getDatas['failed'] = 1);
            // echo 'window.location.href = ../index.php?success=recipe-shared';
            // session_destroy();
        }
    }
}
