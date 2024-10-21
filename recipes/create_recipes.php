<?php

declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}


// include_once(__DIR__ . "/includes/variables.inc.php");
include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');
// require_once(__DIR__ . "/logs/customErrorHandlers.php");

// require('../recipes/test.php');
// include('../recipes/test.php');

// echo json_encode($data);
// if (isset($_POST)) {
//     $content = trim(file_get_contents("php://input"));
//     $dataTest = json_decode($content, true);
//     echo json_encode($dataTest);
// }
// print_r(json_decode($dataTest));
// foreach($dataTest  as $key => $value) {
//     echo'key => '. $key .' value => '. $value;
// }
// ob_start();

// if (isset($_POST)) {
//     // $content = file_get_contents("php://input");
//     // print_r($_REQUEST);
//     // print_r($_REQUEST);
//     $content = trim(file_get_contents("php://input"));
//     // $dataTest = json_encode($content, true);
//     // echo $dataTest['persons'];
//     // echo $dataTest;
//     // print_r(json_decode($dataTest['persons']));
//     // print_r($content);
//     // print_r(json_decode($dataTest));
//     $data = json_decode($content, true);
//     $data['success'] = true;

//     echo json_encode($data);

//     // echo(json_encode($data));
//     // print_r($data);
//     // $test = json_encode($data);





// }

$script = 'src="' . $rootUrl . $clicServer .'/scripts/fadeInScroller.js" defer';
$script2 = 'type="module" src="' . $rootUrl . $clicServer .'/scripts/recipeApp.js" defer';
$pageTitle = "Partagez votre recette";

$title = "Clic'Répare - $pageTitle";

// ob_start();
require_once('../recipes/submit_recipes.php');

// $content = ob_get_contents();
// $content = ob_get_clean();
require('../templates/layout.php');
