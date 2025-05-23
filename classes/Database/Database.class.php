<?php

class Database
{
    private string $joinClause = '';
    private string $limitClause = '';
    private mixed $whereClause = '';
    private string $matchClause = '';
    private string $orderByClause = '';
    private bool $silentMode;
    private bool $fetchAll;
    private bool $searchMode;
    private bool $permission;
    private array $loggedUser = [];
    private bool $silentExecute;
    private string $lastIdKey;

    public function __construct(
        private array $options = [],
        private mixed $optionnalData = null,
    ) {

        // Valeurs par défaut des options
        $defaults = [
            'silentMode' => false,
            'fetchAll' => false,
            'searchMode' => false,
            'silentExecute' => false,
            'permission' => false,
        ];
        // Fusionner les options par défaut avec les options fournies
        $this->options = array_merge($defaults, $options);

        // No errors will be thrown to not kill the script
        $this->silentMode = $this->options['silentMode'];
        // SearchBar fetch : It will keep continue searching by limit until it reaches the maximum requests
        $this->searchMode = $this->options['searchMode'];
        // Recipe read page : gets ALL datas; comments will all be append as array
        $this->fetchAll = $this->options['fetchAll'];
        // In the case where no conditions are needed, execute an SQLQuery without executeParams
        // Exemple :    "fields" => ["*"],
        //              "table" => ["comments"],
        $this->silentExecute = $this->options['silentExecute'];
        // Verify user permissions : user_id MUST MATCH the one inside request
        $this->permission = $this->options['permission'];
        // die(var_dump($this->silentMode, $this->silentExecute));
        if ($this->permission) {
            $this->loggedUser = LoginController::checkLoggedStatus();
        }
    }

    public function __createGetQuery(array $params, int|string|null $id, PDO $database)
    {
        return $this->createGetQuery($params, $id, $database);
    }
    public function __createDeleteQuery(array $params, int $id, PDO $database)
    {
        return $this->createDeleteQuery($params, $id, $database);
    }

    /**
     * Récupère dynamiquement une ou plusieurs ROWS depuis la TABLE 'Recipes'
     *
     * @param array $params Tableau contenant les champs, les jointures et la table.
     * ```php
     * Exemple :
     *  $params = [
     *      'fields' => [
     *          'r.title',
     *          'r.author',
     *          'i.img_id',
     *          'i.youtubeID'
     *      ],
     *      "date" => ['DATE_FORMAT(c.created_at, "%d/%m/%Y") as comment_date'],
     *      'join' => [
     *          'images i' => 'r.recipe_id = i.recipe_id'
     *      ],
     *      'table' => [
     *          'recipes r'
     *      ],
     *      'where' => [
     *          'conditions' => [
     *              'r.full_name' => ':full_name',
     *              'r.email' => ':email'
     *          ],
     *          'logic' => 'OR'
     *      ],
     *      'limit' => 10,
     *      'resetState' => 1,
     *      'order_by' => 'r.recipe_id ASC',
     *      'word' => 'recipe',
     *      'match' => [
     *          'fields' => 'r.title',
     *          'against' => ':word'
     *      ],
     *      "error" => ["Ce commentaire n'existe pas"],
     *      "save_this_last_id" => "comment_id",
     *      "searchMode" => true,
     *      "silenceMode" => true,
     *      "fetchAll" => true,
     *  ];
     * ```
     * @param int|string $id ID / Titre de la recette (peut être ignoré si non nécessaire)
     * @param bool $silentMode Permet de ne pas renvoyer d'erreur si nécessaire (par défaut : false)
     * @param PDO $database Objet PDO pour la connexion à la base de données
     * @throws \Error si la requête échoue ou si aucune ligne n'est trouvée
     * @return mixed Tableau associatif contenant les informations de la recette
     */
    private function createGetQuery(array $params, int|string $id = null, PDO $database)
    {
        // die(var_dump($this->permission, $id, $this->loggedUser));
        if ($this->permission) {
            $id = $this->loggedUser['userId'];
        }
        // Handle request reset state
        if (!empty($params['resetState']) && $params['resetState'] == 1) {
            $_SESSION['LAST_ID'] = 0;
        }

        // Key to check in order to save the last ID from the SQL Request
        if (!empty($params['save_this_last_id'])) {
            $this->lastIdKey = $params['save_this_last_id'];
        }

        // Extract array $params's fields
        $fields = implode(', ', $params['fields']);
        $fromTable = implode(', ', $params['table']);
        $error = !empty($params['error']) ? implode(', ', $params['error']) : "Cette $fromTable n'existe pas";
        // Extract alias from the table name (assuming only one table in 'table' array)
        // $tableAlias = explode(' ', $params['table'][0])[1];
        // Construct JOIN dynamic clause if $params['join'] is NOT NULL
        if (!empty($params['join'])) {
            $this->addJoinClause($params['join']);
        }

        // Adds DATE_FORMAT to the request if 'images i' is set
        if (!empty($params['date'])) {
            $date = implode(', ', $params['date']);
            $fields .= ", $date";
        }

        // Construct dynamic WHERE clause
        if (!empty($params['where']['conditions'])) {
            $this->whereClause = $this->addWhereClause($params);
        }
        // elseif ($id !== null) {
        //     $this->whereClause = "WHERE $tableAlias.recipe_id = :recipe_id";
        // }

        // Add a MATCH to the request if set
        if (!empty($params['match'])) {
            $matchFields = $params['match']['fields'];
            $matchAgainst = $params['match']['against'];
            $fields .= ", MATCH ($matchFields) AGAINST ($matchAgainst IN BOOLEAN MODE) AS score";
            $this->whereClause .= " HAVING score > 0";
        }

        // Gestion de la limite et de l'état de réinitialisation
        if (!empty($params['limit'])) {
            // $this->limitClause = "LIMIT $limit";
            $this->limitClause = "LIMIT " . intval($params['limit']);
        }

        // Construction de la clause MATCH si un mot clé est fourni
        if (!empty($params['word'])) {
            // $matchClause = "MATCH(title) AGAINST(:word IN BOOLEAN MODE) AS score";
            // $fields .= ", $matchClause";
        }

        // Ajout de la clause ORDER BY dynamique
        if (!empty($params['order_by'])) {
            $this->orderByClause = 'ORDER BY ' . $params['order_by'];
        }

        // die(var_dump($params));


        // SQL Request Construction
        $sqlQuery = "SELECT $fields
            FROM $fromTable
            $this->joinClause
            $this->whereClause
            $this->orderByClause
            $this->limitClause;";
        // $sqlQuery = " SELECT r.recipe_id, r.title, r.author, i.img_path, i.youtubeID, DATE_FORMAT(i.created_at, '%d/%m/%Y') as image_date, MATCH (r.title) AGAINST (:word IN BOOLEAN MODE) AS score FROM recipes r LEFT JOIN images i ON i.recipe_id = r.recipe_id WHERE r.is_enabled = 1 AND r.recipe_id > :recipe_id HAVING score > 0 ORDER BY r.recipe_id ASC LIMIT 5;";
        // die(var_dump($sqlQuery));

        // Prepare Statement
        $getRecipeStatement = $database->prepare($sqlQuery);
        // echo (var_dump($sqlQuery, $this->searchMode));
        // Prepare execute parameters
        // If number $id
        if ($id !== null && is_numeric($id)) {
            // !! IMPORTANT !! Force int on the ID

            $id = (int) $id;
            $executeParams['recipe_id'] = $id;
        }

        // If string $id
        if ($id !== null && is_string($id)) {
            // Construct clause MATCH if a keyword is found

            $executeParams['word'] = strip_tags($id) . '*';
            // $executeParams['word'] = strip_tags($id) . '*';
            // if (isset($_SESSION['LAST_ID']) && isset($this->lastIdKey)) {
            //     $executeParams[$this->lastIdKey] = $_SESSION['LAST_ID'];
            //     // $executeParams['recipe_id'] = $_SESSION['LAST_ID'];
            // }
        }

        if (isset($_SESSION['LAST_ID']) && isset($this->lastIdKey)) {
            $executeParams[$this->lastIdKey] = $_SESSION['LAST_ID'];
        }

        // die(var_dump($params));

        if ($this->silentExecute) {
            $executeParams = [];
        }

        if (!empty($params['login']) && $params['login']) {
            $executeParams = [];
            $executeParams['email'] = $id;
            $executeParams['full_name'] = $id;
        }
        // if (!empty($params['word'])) {
        //     $executeParams['word'] = $params['word'] . '*';
        //     if (isset($_SESSION['LAST_ID'])) {
        //         $executeParams['recipe_id'] = $_SESSION['LAST_ID'];
        //     }
        // }
        // die(var_dump($getRecipeStatement));
        // $executeParams = [
        //     'word' => 'test',
        //     'recipe_id' => '0'
        // ];
        // $getRecipeStatement->execute($executeParams);
        // $results = $getRecipeStatement->fetchAll(PDO::FETCH_ASSOC);
        // die(var_dump($getRecipeStatement));
        // Execute SQLRequest
        // die(var_dump($getRecipeStatement));
        // die(var_dump($executeParams));
        // die(var_dump($sqlQuery, $executeParams, $params, $id));
        // die(var_dump($sqlQuery));
        // die(var_dump($getRecipeStatement));
        try {
            if (!$getRecipeStatement->execute($executeParams)) {

                $getRecipeStatement = null;
                $errorCode = 403;
                // require_once(dirname(__DIR__, 2).'/config/altertable.sql')
                throw new Error("STMTGET - Failed");
            }


            // echo json_encode($_SESSION['LAST_ID']);
            // If no row exists, fail
            if ($getRecipeStatement->rowCount() == 0) {
                // die(var_dump($_SESSION['LAST_ID']));
                if (isset($_SESSION['LAST_ID'])) {
                    unset($_SESSION['LAST_ID']);
                }
                $getRecipeStatement = null;
                if ($this->silentMode) {
                    // Return empty
                    return [];
                }
                // if ($this->optionnalData === "reply_Client") {
                //     // return $error;
                // }
                // Send a first Error that will be caught
                // $errorCode = 403;

                throw new Error($error);
                // echo json_encode($error);
            }
            // $this->searchMode = false;
            // $this->fetchAll = true;

            if ($this->searchMode) {

                // die(var_dump($this->searchMode));
                // Grab all results from the searchbar
                $data = [];
                // echo var_dump($_SESSION['LAST_ID']);

                while ($recipeInfos = $getRecipeStatement->fetch(PDO::FETCH_ASSOC)) {
                    if (is_array($recipeInfos) && isset($recipeInfos[$this->lastIdKey]) && $recipeInfos[$this->lastIdKey] > $_SESSION['LAST_ID']) {
                        // die($getRecipeStatement);

                        // $lastItem = end($recipeInfos);
                        // $_SESSION['LAST_ID'] = $lastItem['recipe_id'];
                        $_SESSION['LAST_ID'] = $recipeInfos[$this->lastIdKey];
                        $data[] = $recipeInfos;
                    }
                }
                // while ($recipeInfos = $getRecipeStatement->fetch(PDO::FETCH_ASSOC)) {
                //     if (is_array($recipeInfos) && isset($recipeInfos['recipe_id']) && $recipeInfos['recipe_id'] > $_SESSION['LAST_ID']) {
                //         // $lastItem = end($recipeInfos);
                //         // $_SESSION['LAST_ID'] = $lastItem['recipe_id'];
                //         $_SESSION['LAST_ID'] = $recipeInfos['recipe_id'];
                //         $data[] = $recipeInfos;
                //     }
                // }
                return $data;
            }

            if ($this->fetchAll) {

                // Grab 1 entry result
                $datas = $getRecipeStatement->fetchAll(PDO::FETCH_ASSOC);
                // If it's an UPDATE RECIPE Request - JS Client submit handler
                // die(var_dump($params['limit']));
                if ($this->optionnalData === 'reply_Client') {
                    return json_encode($datas);
                }
                return $datas;
            }

            // Grab 1 entry result
            $data = $getRecipeStatement->fetch(PDO::FETCH_ASSOC);
            // If it's an UPDATE RECIPE Request - JS Client submit handler
            if ($this->optionnalData === 'reply_Client') {
                return json_encode($data);
            }
            // print(var_dump($data));
            // die(var_dump($data));

            return $data;
        } catch (\Throwable $th) {
            $errorCode = 403;
            $response = [
                'status' => $errorCode,
                'message' => "Cette requête ne peut aboutir",
                // 'message' => $th->getMessage(),
                'ok' => false
            ];
            http_response_code($errorCode);
            return $response;
        }
    }

    /**
     * Ajoute une clause JOIN dynamique à la requête SQL.
     *
     * @param array $params Tableau associatif contenant les tables à joindre et les conditions de jointure.
     * ```php
     * Exemple :
     *  $params = [
     *      'images i' => 'r.recipe_id = i.recipe_id',
     *      'categories c' => 'r.category_id = c.category_id'
     *  ];
     * ```
     * @return void
     */
    private function addJoinClause(array $params)
    {
        foreach ($params as $table => $condition) {
            $this->joinClause .= "LEFT JOIN $table ON $condition ";
        }
    }

    /**
     * Construit et retourne une clause WHERE dynamique basée sur les conditions fournies.
     *
     * @param array $params Tableau associatif contenant les conditions de la clause WHERE et la logique (ET/OU).
     * ```php
     * Exemple :
     *  $params = [
     *      'where' => [
     *          'conditions' => [
     *              'r.full_name' => ':full_name',
     *              'r.email' => ':email'
     *          ],
     *          'logic' => 'OR'
     *      ]
     *  ];
     * ```
     * @return string La clause WHERE construite.
     */
    private function addWhereClause(array $params)
    {
        $conditions = [];
        foreach ($params['where']['conditions'] as $column => $value) {
            $conditions[] = "$column $value";
        }
        $logic = $params['where']['logic'] ?? 'AND';
        $whereClause = 'WHERE ' . implode(" $logic ", $conditions);
        return $whereClause;
    }

    /**
     * Supprime une entrée d'une ou plusieurs tables dans la base de données en fonction de l'ID de la recette.
     *
     * Cette fonction prend un tableau de paramètres et un identifiant de recette, et supprime la ligne correspondante
     * dans la ou les tables spécifiées.
     *
     * @param array $params Tableau contenant le nom de la table.
     * @param int $id L'identifiant de la recette.
     * @param PDO $database Fonction connect() de la database
     * @throws Error Si la requête SQL échoue ou si aucune ligne n'est affectée par la suppression.
     */
    private function createDeleteQuery(array $params, int $id, PDO $database)
    {
        // Extraction des champs du tableau $params
        $fromTable = implode(', ', $params['table']);
        $error = implode(', ', $params['error']);

        // Construct dynamic WHERE clause
        if (!empty($params['where']['conditions'])) {
            $this->whereClause = $this->addWhereClause($params);
        }

        // Construction de la requête SQL
        // $sqlQuery = "DELETE
        //     FROM $fromTable
        //     WHERE recipe_id = :recipe_id;";
        $sqlQuery = "DELETE
            FROM $fromTable
            $this->whereClause;";


        if (!empty($params['where']['conditions']["comment_id"])) {
            $execParams = ['comment_id' => $id];
        } else {
            $execParams = ['recipe_id' => $id];
        }

        // die(var_dump($execParams));

        // Préparation de la requête
        $deleteStatement = $database->prepare($sqlQuery);

        // Exécution de la requête SQL en recherchant l'ID à partir des paramètres
        if (!$deleteStatement->execute($execParams)) {
            $deleteStatement = null;
            throw new Error("STMTDLT - Failed");
        }
        // Si aucune ligne n'existe, lancer une exception
        if (!$this->silentMode && $deleteStatement->rowCount() == 0) {
            $deleteStatement = null;
            throw new Error($error);
        }
    }
}
