<?php

// readonly class Functions {
class Functions
{
    public static function getUrl()
    {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = array_pop($url);
        return $url;
    }

    public static function getRootUrl()
    {
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return $rootUrl;
    }

    public static function actualServer($rootUrl): string
    {
        // $rootUrl = Functions::getRootUrl();
        // $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        if ($rootUrl === 'https://adi.ezaya.fr/') {
            return 'ClicRepare';
        } else {
            return 'recettes';
        }
    }

    /**
     * Récupère les données d'une table de la base de données en fonction de l'ID de la recette.
     *
     * Cette méthode génère une requête SQL pour obtenir des données à partir de la table spécifiée,
     * utilise une instance de la classe `Database` pour exécuter la requête et retourne les résultats.
     *
     * @param array $params Tableau contenant les paramètres de la requête, y compris les champs et les tables.
     * @param int|string $recipeId L'identifiant de la recette.
     * @param bool $silentMode Mode silencieux pour la récupération des données (par défaut : false).
     * @param PDO $pdo PDO object.
     * @param mixed $optionnalData Données optionnelles, surtout utilisée par string.
     * @return array Les données SQL récupérées.
     * @throws Error Si la recette n'existe pas.
     */
    public static function getFromTable(array $params, int|string $recipeId, $pdo, $optionnalData = null)
    {
        // Option du constructeur

        $options = [
            "fetchAll" => $params["fetchAll"] ?? false,
            "searchMode" => $params["searchMode"] ?? false,
            "silentMode" => $params["silentMode"] ?? false,
            "silentExecute" => $params["silentExecute"] ?? false,
            "permission" => $params["permission"] ?? false
        ];
        // Crée une instance de la classe Database avec des données optionnelles
        $Fetch = new Database($options, $optionnalData);

        // Génère et exécute la requête SQL pour récupérer les données
        $SQLData = $Fetch->__createGetQuery($params, $recipeId, $pdo);

        // Retourne les données SQL récupérées
        return $SQLData;
    }

    public function getThisUrl()
    {
        echo $this->getUrl();
    }
    public function getThisRootUrl()
    {
        return $this->getRootUrl();
    }
    public function getThisActualServer($rootUrl): string
    {
        return $this->actualServer($rootUrl);
    }
    // public static function _getFromTable(array $params, int|string $recipeId, $pdo): array
    // {
    //     return self::getFromTable($params, $recipeId, $pdo);
    // }
}
