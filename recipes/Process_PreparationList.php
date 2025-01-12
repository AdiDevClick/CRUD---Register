<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sets last search index in database
$_SESSION['LAST_ID'] ?? $_SESSION['LAST_ID'] = 0;

require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "variables.inc.php";
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "functions.inc.php";

// $fetchData = $_SERVER['REQUEST_METHOD'] === 'GET';
// $data = $_SERVER['REQUEST_METHOD'] === 'POST';
// $id;
// $is_Post = true;
// $session = 'REGISTERED_RECIPE';

/**
 * Dans le cas d'une recherche, on utilise $_SESSION['LAST_ID'] pour sauvegarder l'état de la recherche -
 * Script JS : 'SearchBar.js' -
 * Fetch les éléments depuis la Database -
 * 4 options peuvent être passées : query, _reset, _page, _limit -
 * _reset : Permet de remettre le compteur $_SESSION['LAST_ID'] = 0 -
 *          S'effectue automatiquement par JS quand une nouvelle recherche est amorcée par l'utilisateur -
 * _page :  Le numéro page ne DOIT PAS être modifiée, elle est gérée par JS et
 *          s'incrémente au fur et à mesure que ne nouveaux éléments sont chargés -
 * _limit : Permet de limiter le nombre d'éléments fetch par demande utilisateurs -
 *          Il est préférable de ne pas aller en dessous de 5 -
 * query :  Est défini dans les searchParams par JS dès que l'utilisateur effectue une recherche -
 *          N'est pas modifiable -
 */
// if (isset($_GET['query']) && !isset($_SESSION['SEARCH_QUERY'])) {
//     // header('Content-Type: application/json; charset=utf-8');
//     // $content = file_get_contents("php://input");
//     // $dataTest = json_decode($content, true);

//     $getSearchRequest = $_GET['query'];
//     $sessionName = 'SEARCH_QUERY';

//     $query = new RecipeView($getSearchRequest);
//     $params = [
//         "limit" => $_GET["_limit"],
//         // "query" => $_GET["query"],
//         "resetState" => $_GET["_reset"],
//         "fields" => ["r.recipe_id", "r.title", "r.author", "i.img_path", "i.youtubeID"],
//         "date" => ["DATE_FORMAT(i.created_at, '%d/%m/%Y') as image_date"],
//         "match" => [
//             "fields" => "r.title",
//             "against" => ":word"
//         ],
//         "join" => [
//             "images i" => "i.recipe_id = r.recipe_id"
//         ],
//         "where" => [
//             "conditions" => [
//                 "r.is_enabled" => "= 1",
//                 "r.recipe_id" => "> :recipe_id"
//             ],
//             "logic" => "AND"
//         ],
//         "order_by" => "r.recipe_id ASC",
//         // "word" => $getSearchRequest,
//         "table" => ["recipes r"],
//         "searchMode" => true,
//         "silentMode" => true,
//         "error" => ["Fetch search Error"]
//     ];
//     // Retourne un array d'objets contenant :
//     // author: "email",
//     // img_path: "img/path/to/images"
//     // recipe_id: int
//     // score: int
//     // title: "titre de ma recette"
//     // youtubeID: "idvideoyoutube"
//     // $recipe = $query->getRecipesTitle();
//     $recipe = $query->retrieveFromTable($params, $sessionName);

//     if (isset($_SESSION[$sessionName])) {
//         echo json_encode($recipe);
//         unset($_SESSION[$sessionName]);
//         exit();
//     } else {
//         die(json_encode(['error' => $params['error'][0]]));
//     }
// }

// if (isset($_GET['id']) && is_numeric($_GET['id'])) {
//     /**
//      * LORS D'UNE MISE A JOUR :
//      * Récupère et renvoi l'ID de la recette au script JS 'RecipePreparation.js'.
//      * Cela permet d'afficher les ingrédients dynamiques liés à la recette.
//      * L'ID de la recette DOIT être le même que lors de l'initialisation de la page.
//      */

//     if (
//         $fetchData && isset($_SESSION['INFO_RECIPE']) &&
//         $_SESSION['INFO_RECIPE']['INFO_RECIPE'] === $_GET['id'] &&
//         !isset($_SESSION['CUSTOM_INGREDIENTS'])
//     ) {

//         // Destroy previews Session cookie
//         unset($_SESSION['INFO_RECIPE']);

//         // Prepare the new SQL params
//         $getID = $_GET['id'];
//         $sessionName = 'CUSTOM_INGREDIENTS';
//         $params = [
//             'fields' => ['custom_ingredients'],
//             'table' => ['recipes r'],
//             "where" => [
//                 "conditions" => [
//                     'r.is_enabled' => '= 1',
//                     'r.recipe_id' => '= :recipe_id'
//                 ],
//             ],
//             'error' => ["Erreur dans la récupération d'ingrédients"],
//         ];
//         // Prepare the controller for JavaScript submit handler
//         $id = new RecipeView($getID, 'reply_Client');

//         // Send the SQL request
//         $getInfos = $id->retrieveFromTable($params, $sessionName);
//         if (isset($_SESSION[$sessionName])) {
//             //Destroy previews session cookie
//             unset($_SESSION[$sessionName]);
//             // Send the datas to JavaScript in JSON then kills the script
//             die($getInfos);
//         }
//     }

//     /**
//      * IMPORTANT !!
//      * LORS DE L'ENVOI DU FORMULAIRE POUR UNE MISE A JOUR :
//      * Récupère à nouveau l'ID de la recette pour la passer au serveur
//      */

//     // Grab ID from url
//     $id = $_GET['id'];
//     // Setting the UPDATE RECIPE intention from the user
//     $is_Post = false;
//     // Setting information to pass inside the SESSION Cookie
//     // This will allow the server to display the page content
//     // die(var_dump($_SESSION));
//     $session = 'UPDATED_RECIPE';
//     $_SESSION[$session] = $session;
// }

/**
 * Renvoi les informations formulaires vers le serveur
 */
// if (!isset($_SESSION[$session]) && $data && isset($_POST)) {
// if ($data && isset($_POST)) {
//     // die(var_dump($fetchData, $data));

//     // Sets the type of header content type to talk to JavaScript
//     header('Content-Type: application/json; charset=utf-8');
//     // Grab all input datas
//     $client_Side_Datas = file_get_contents("php://input");
//     // Decoding JSON data
//     $data = json_decode($client_Side_Datas, true);

//     // Voir si on récupère les fichiers du dessus
//     $process_Ingredients = new Process_Ajax($data ?? $_POST, $_FILES, $is_Post, $session, $id ?? null);
//     // Remove session user cookies
//     unset($_SESSION[$session]);

//     $err = CheckInput::getErrorMessages();

//     if (count($err) > 0) {
//         $errorMessage = CheckInput::showErrorMessage();
//         $successMessage = '';
//         //if (isset($_SESSION['REGISTERED_USER'])) {
//         //ob_start();
//         $successMessage = '<div>';
//         $successMessage .= '<p class="alert-error"> ' . strip_tags($errorMessage) . '</p>';
//         $successMessage .= '</div>';
//         // echo json_encode($successMessage);
//         // print $getDatas['failed'] = true;
//         // echo $getDatas['failed'] = 1;
//         // echo json_encode($getDatas['failed'] = 1);
//         // echo 'window.location.href = ../index.php?success=recipe-shared';
//         // session_destroy();
//     }
//     // }
// } else {
//     // return;
// }
// echo json_encode($_GET);
// die();
// header('Content-Type: application/json; charset=utf-8');
// $client_Side_Datas = file_get_contents("php://input");
// $data = json_decode($client_Side_Datas, associative: true);
// echo json_encode($_POST);
class Process_PreparationList
{

    private $id;
    private bool $is_Post = true;
    private bool $searchMode = false;
    private bool $getReviews = false;
    private string $search;
    private string $session = 'REGISTERED_RECIPE';
    private bool $get;
    private bool $post;
    public function __construct(
        // private $searchParams,
    )
    {
        $this->get = $_SERVER['REQUEST_METHOD'] === 'GET';
        $this->post = $_SERVER['REQUEST_METHOD'] === 'POST';
        // header('Content-Type: application/json; charset=utf-8');
        // Activer le tampon de sortie pour s'assurer qu'aucun contenu n'est envoyé avant les en-têtes JSON 
        // ob_start();
        // $client_Side_Datas = file_get_contents("php://input");
        // $data = json_decode($client_Side_Datas, true);
        // Vider le tampon de sortie et envoyer la réponse JSON 
        // ob_end_clean();
        // echo json_encode($_POST);
        if ($this->get && isset($_GET)) {

            $this->getUrlParams($_GET);

            if ($this->searchMode) {
                // $this->searchReviews($this->search);
                $this->newSearch($this->search);
            }

            // if ($this->getReviews) {
            //     $this->searchReviews($this->search);
            // }
            // Default GET behavior
            // die(var_dump(
            //     $_SESSION
            // ));
            $this->is_Get();
            return;
        }

        if ($this->post && isset($_POST)) {

            // Verify if a searchParam is set to define the update status
            if (isset($_GET)) $this->getUrlParams($_GET);
            if ($this->searchMode) {
                $this->searchReviews($this->search);
            } else {
                // die(var_dump($this->id));
                // Retrieve post Datas
                $data = $this->retrievePostDatas();

                if (!empty($data['any_post'])) {
                    $this->post_This($data);
                }

                if (isset($_POST['AJAX']) && $_POST['AJAX']) {
                    $this->is_Post($data);
                }
            }
        }
    }

    protected function getUrlParams(array $urlParams)
    {

        foreach ($urlParams as $param => $value) {

            if ($param === 'query') {

                $this->searchMode = true;
                $this->search = $value;
            }

            // if ($param === 'reviews') {
            //     $this->getReviews = true;
            //     $this->search = $value;
            // }

            if ($param === 'id') {
                $this->id = $value;
                if ($this->post) {
                    /**
                     * IMPORTANT !!
                     * LORS DE L'ENVOI DU FORMULAIRE POUR UNE MISE A JOUR :
                     * Récupère à nouveau l'ID de la recette pour la passer au serveur
                     */
                    // Setting the UPDATE RECIPE intention from the user
                    $this->is_Post = false;
                    // Setting information to pass inside the SESSION Cookie
                    // This will allow the server to display the page content
                    $this->session = 'UPDATED_RECIPE';
                    $_SESSION[$this->session] = $this->session;
                }
            }

            if ($param === 'review') {
            }
        }
    }

    private function is_Get()
    {

        if (isset($this->id) && is_numeric($this->id)) {

            if (
                isset($_SESSION['INFO_RECIPE']) &&
                $_SESSION['INFO_RECIPE']['INFO_RECIPE'] === $this->id &&
                !isset($_SESSION['CUSTOM_INGREDIENTS'])
            ) {
                // Destroy previews Session cookie
                unset($_SESSION['INFO_RECIPE']);
                // Prepare the new SQL params
                $sessionName = 'CUSTOM_INGREDIENTS';
                $params = [
                    'fields' => ['custom_ingredients'],
                    'table' => ['recipes r'],
                    "where" => [
                        "conditions" => [
                            // 'r.is_enabled' => '= 1',
                            'r.recipe_id' => '= :recipe_id'
                        ],
                    ],
                    'error' => ["Erreur dans la récupération d'ingrédients"],
                ];

                // Prepare the controller for JavaScript submit handler
                $id = new RecipeView($this->id, 'reply_Client');
                // echo (json_encode($id));

                // Send the SQL request
                $getInfos = $id->retrieveFromTable($params, $sessionName);
                if (isset($_SESSION[$sessionName])) {
                    //Destroy previews session cookie
                    unset($_SESSION[$sessionName]);
                    // Send the datas to JavaScript in JSON then kills the script
                    die($getInfos);
                }
            }
        }
        // break;
    }

    /**
     * Paramètre la requête de recherche des reviews
     * @param int|string $request
     * @return void
     */
    private function searchReviews(int|string $request)
    {
        $sessionName = 'REVIEWS_QUERY';

        $params = $this->retrievePostDatas();

        if (isset($params['session_name'])) {
            $sessionName = $params['session_name'];
        } else {
            $sessionName = 'REVIEWS_QUERY';
        }

        if (isset($_GET["_limit"])) {
            $params['limit'] = $_GET["_limit"];
        }

        if (isset($_GET["_reset"]) && $_GET["_reset"] == 1) {
            $params['resetState'] = $_GET["_reset"];
        }

        // die(var_dump($params));
        // $params = [
        //     "fields" => ['comment_id', 'comment', 'user_id', 'c.title', 'review'],
        //     "date" => ['DATE_FORMAT(c.created_at, "%d/%m/%Y") as comment_date'],
        //     "join" => [
        //         'comments c' => 'c.recipe_id = r.recipe_id',
        //     ],
        //     "where" => [
        //         "conditions" => [
        //             'r.is_enabled' => '= 1',
        //             'r.recipe_id' => '= :recipe_id',
        //             'c.review' => '= ' . $request,
        //         ],
        //     ],
        //     "table" => ["recipes r"],
        //     "error" => ["La filtration de commentaire n'a pas fonctionné"],
        //     "fetchAll" => true
        // ];
        $this->SQLRequest($this->id, $sessionName, $params);
    }

    /**
     * Paramètre la requête de recherche d'items
     * @param mixed $param
     * @return void
     */
    private function newSearch($request)
    {
        $sessionName = 'SEARCH_QUERY';

        $params = [
            "limit" => $_GET["_limit"],
            // "query" => $_GET["query"],
            "resetState" => $_GET["_reset"],
            "fields" => ["r.recipe_id", "r.title", "r.description", "r.author", "i.img_path", "i.youtubeID"],
            "date" => ["DATE_FORMAT(i.created_at, '%d/%m/%Y') as image_date"],
            "match" => [
                "fields" => "r.title",
                "against" => ":word"
            ],
            "join" => [
                "images i" => "i.recipe_id = r.recipe_id"
            ],
            "where" => [
                "conditions" => [
                    "r.is_enabled" => "= 1",
                    "r.recipe_id" => "> :recipe_id"
                ],
                "logic" => "AND"
            ],
            "order_by" => "r.recipe_id ASC",
            // "word" => $getSearchRequest,
            "table" => ["recipes r"],
            "save_this_last_id" => "recipe_id",
            "searchMode" => true,
            "silentMode" => true,
            "error" => ["Fetch search Error"]
        ];
        // die(json_encode($params));
        $this->SQLRequest($request, $sessionName, $params);
    }

    /**
     * Renvoi les informations formulaires vers le serveur
     * Vérifie le param "any_post" pour définir le type de demande à effectuer
     * @param mixed $data
     * @return void
     */
    private function post_This($data)
    {
        $loggedUser = LoginController::checkLoggedStatus();
        if (!isset($loggedUser)) {
            echo 'Vous devez être authentifié pour soumettre un commentaire ';
            return;
        }

        $this->session = $data['session_name'];

        $newView = new RecipeView(
            $data
        );

        if ($data['any_post'] === 'insert_comment') $response = $newView->insertComment($data);
        if ($data['any_post'] === 'update_comment') $response = $newView->updateComment($data);
        if ($data['any_post'] === 'delete_comment') $response = $newView->deleteComment($data);
        if ($data['any_post'] === 'enable_recipe') $response = $newView->activateOrDeactivateRecipe();
        // echo json_encode($_SESSION);

        // Remove session user cookies
        unset($_SESSION[$this->session]);

        die(json_encode($response));
        // $this->displayError();
    }

    /**
     * Renvoi les informations formulaires vers le serveur
     * dans le cas d'une requête avec login obligatoire
     * @return void
     */
    private function is_Post($data)
    {
        // die(var_dump($this->post, 'je var DUMP'));

        unset($_SESSION['UPDATED_RECIPE']);
        // Traitement d'une requête avec fichiers pour
        // la création / mise à jour des recettes
        $process_Ingredients = new Process_Ajax($data ?? $_POST, $_FILES, $this->is_Post, $this->session, $this->id ?? null);

        // Remove session user cookies
        unset($_SESSION[$this->session]);

        $this->displayError();
    }

    private function retrievePostDatas()
    {
        // Sets the type of header content type to talk to JavaScript
        header('Content-Type: application/json; charset=utf-8');

        // Grab all input datas
        $client_Side_Datas = file_get_contents("php://input");

        // Decoding JSON data
        $data = json_decode($client_Side_Datas, true);
        return $data;
    }

    private function displayError()
    {
        $err = CheckInput::getErrorMessages();

        if (count($err) > 0) {
            $errorMessage = CheckInput::showErrorMessage();
            $successMessage = '';
            $successMessage = '<div>';
            $successMessage .= '<p class="alert-error"> ' . strip_tags($errorMessage) . '</p>';
            $successMessage .= '</div>';
            echo $successMessage;
        }
    }

    private function SQLRequest($request, $sessionName, $params)
    {
        // Retourne un array d'objets contenant :
        // author: "email",
        // img_path: "img/path/to/images"
        // recipe_id: int
        // score: int
        // title: "titre de ma recette"
        // youtubeID: "idvideoyoutube"
        // $recipe = $query->getRecipesTitle();

        $query = new RecipeView($request);
        // die(var_dump($request));
        $response = $query->retrieveFromTable($params, $sessionName);
        // die(var_dump($response));

        if (isset($_SESSION[$sessionName])) {
            unset($_SESSION[$sessionName]);

            $loggedUser = LoginController::checkLoggedStatus();

            foreach ($response as $key => $value) {
                if (isset($loggedUser["userId"]) && isset($value["user_id"]) && (int) $loggedUser["userId"] === (int) $value["user_id"]) {
                    // if ($recipe['error']) {
                    //     // echo (json_encode(['error' => $params['error'][0]]));
                    //     echo 'test';
                    // }
                    $response[$key]["canCreateTooltips"] = true;
                }
            }

            echo json_encode($response);
        } else {
            echo json_encode($response);
            // die(json_encode(['error' => $params['error'][0]]));
        }
        // $query = new RecipeView($request);
        // // die(var_dump($request));

        // $recipe = $query->retrieveFromTable($params, $sessionName);
        // // die(json_encode($recipe));

        // if (isset($_SESSION[$sessionName])) {
        //     echo json_encode($recipe);
        //     unset($_SESSION[$sessionName]);
        // } else {
        //     die(json_encode(['error' => $params['error'][0]]));
        // }
    }
}

new Process_PreparationList();
