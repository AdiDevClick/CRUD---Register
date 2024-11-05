<?php

class Database {
    private bool $includeDateFormat = false;
    private string $joinClause = '';
    private string $limitClause = '';
    private mixed $whereClause = '';
    private string $matchClause = '';
    private string $orderByClause = '';
    private bool $silentMode;
    private bool $fetchAll;

    public function __construct(
        private mixed $optionnalData = null,
        private array $options = []
        )
    {
        
        // Valeurs par défaut des options
        $defaults = [
            'silentMode' => false,
            'fetchAll' => false
        ];
        // Fusionner les options par défaut avec les options fournies
        $this->options = array_merge($defaults, $options);

        $this->silentMode = $this->options['silentMode'];
        $this->fetchAll = $this->options['fetchAll'];

        // die(var_dump($this->silentMode, $this->optionnal));
    }

    public function __createGetQuery(array $params, int|null $id, PDO $database, array $optionnal = null) {
        return $this->createGetQuery( $params, $id, $database, $optionnal);
    }
    public function __createDeleteQuery(array $params, int $id, PDO $database) {
        return $this->createDeleteQuery( $params, $id, $database );
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
     *      'word' => 'recipe'
     *      'match' => [
     *          'fields' => 'r.title',
     *          'against' => ':word'
     *      ]
     *  ];
     * ```
     * @param int $recipeId ID de la recette (peut être ignoré si non nécessaire)
     * @param bool $silentMode Permet de ne pas renvoyer d'erreur si nécessaire (par défaut : false)
     * @param PDO $database Objet PDO pour la connexion à la base de données
     * @throws \Error si la requête échoue ou si aucune ligne n'est trouvée
     * @return mixed Tableau associatif contenant les informations de la recette
     */
    private function createGetQuery(array $params, int $recipeId = null, PDO $database, array $optionnal = null) 
    {
        // Handle state reset
        // $limit = intval($optionnal['limit'] ?? 10);
        if (isset($optionnal['resetState']) && $optionnal['resetState'] == 1) {
            $_SESSION['LAST_ID'] = 0;
        }

        // Extract array $params's fields
        $fields = implode(', ', $params['fields']);
        $fromTable = implode(', ', $params['table']);
        $error = !empty($params['error']) ? implode(', ', $params['error']) : "Cette $fromTable n'existe pas";
        // Extract alias from the table name (assuming only one table in 'table' array)
        $tableAlias = explode(' ', $params['table'][0])[1];
        // Construct JOIN dynamic clause if $params['join'] is NOT NULL
        if (!empty($params['join'])) {
            $this->addJoinClause($params['join']);
        }

        // Adds DATE_FORMAT to the request if 'images i' is set
        if ($this->includeDateFormat) {
            $fields .= ", DATE_FORMAT(i.created_at, '%d/%m/%Y') as image_date";
        }

        // Construct dynamic WHERE clause
        if (!empty($params['where']['conditions'])) {
            $this->whereClause = $this->addWhereClause($params);
        } elseif ($recipeId !== null) {
            $this->whereClause = "WHERE $tableAlias.recipe_id = :recipe_id";
        }

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
        // $orderBy = !empty($params['order_by']) ? 'ORDER BY ' . $params['order_by'] : 'ORDER BY r.recipe_id ASC';
        
        // SQL Request Construction
        $sqlQuery = "SELECT $fields
            FROM $fromTable
            $this->joinClause
            $this->whereClause
            $this->orderByClause
            $this->limitClause;";

        // Prepare Statement
        $getRecipeStatement = $database->prepare($sqlQuery);
        // die(var_dump($getRecipeStatement));
        // Préparation des paramètres pour l'exécution de la requête
        // $executeParams = $params['where']['conditions'] ?? [];
        if ($recipeId !== null) {
            $executeParams['recipe_id'] = $recipeId;
        }

        if (!empty($params['word'])) {
            $executeParams['word'] = $params['word'] . '*';
            if (isset($_SESSION['LAST_ID'])) $executeParams['recipe_id'] = $_SESSION['LAST_ID'];
        }

        // die(var_dump($executeParams));

        // Execute SQLRequest
        if (!$getRecipeStatement->execute($executeParams)) {
            $getRecipeStatement = null;
            // require_once(dirname(__DIR__, 2).'/config/altertable.sql')
            throw new Error("stmt Failed");
        }

        // If no row exists, fail
        if ($getRecipeStatement->rowCount() == 0) {
            if (isset($_SESSION['LAST_ID'])) $_SESSION['LAST_ID'] = 0;
            $getRecipeStatement = null;
            if ($this->silentMode) {
                // Return empty
                return [];
                // return $data = [];
            }
            // Send a first Error that will be caught
            throw new Error($error);
        }

        if ($this->fetchAll) {
            // Grab all results from the searchbar
            $data = [];
            // if ($getRecipeStatement->rowCount() > 0) {
            while ($recipeInfos = $getRecipeStatement->fetch(PDO::FETCH_ASSOC)) {
                if (is_array($recipeInfos) && isset($recipeInfos['recipe_id']) && $recipeInfos['recipe_id'] > $_SESSION['LAST_ID']) {
                    // $lastItem = end($recipeInfos);
                    // $_SESSION['LAST_ID'] = $lastItem['recipe_id'];
                    $_SESSION['LAST_ID'] = $recipeInfos['recipe_id'];
                    $data[] = $recipeInfos;
                    // die(var_dump($recipeInfos['recipe_id'], $_SESSION['LAST_ID']));
                }
            }
            return $data;
            // }
        } else {
            // Grab 1 entry result
            $data = $getRecipeStatement->fetch(PDO::FETCH_ASSOC);
            // If it's an UPDATE RECIPE Request - JS Client submit handler
            if ($this->optionnalData === 'reply_Client') return json_encode($data);
            return $data;
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
    private function addJoinClause(array $params) {
        foreach ($params as $table => $condition) {
            $this->joinClause .= "LEFT JOIN $table ON $condition ";
            if ($table === 'images i') $this->includeDateFormat = true;
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
    private function addWhereClause(array $params) {
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
    private function createDeleteQuery(array $params, int $id, PDO $database) {
        // Extraction des champs du tableau $params
        $fromTable = implode(', ', $params['table']);
        $error = implode(', ', $params['error']);
        // Construction de la requête SQL
        $sqlQuery = "DELETE
            FROM $fromTable
            WHERE recipe_id = :recipe_id;";
        
        // Préparation de la requête
        $deleteStatement = $database->prepare($sqlQuery);

        // Exécution de la requête SQL en recherchant l'ID à partir des paramètres
        if (!$deleteStatement->execute(['recipe_id' => $id])) {
            $deleteStatement = null;
            throw new Error("stmt Failed");
        }
        // Si aucune ligne n'existe, lancer une exception
        if (!$this->silentMode && $deleteStatement->rowCount() == 0) {
            $deleteStatement = null;
            throw new Error($error);
            // throw new Error("Cette $fromTable ne peut pas être supprimée, elle n'existe pas.");
        }
    }
}