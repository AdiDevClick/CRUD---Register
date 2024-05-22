<?php

// $rootUrl = Functions::getRootUrl();

class Mysql
{
    private static $MYSQL_HOST = "localhost";
    private static $MYSQL_PORT = "3306";
    private static $MYSQL_USER = "root";
    private static $MYSQL_PASS = "";
    private static $MYSQL_DB = "we_love_food";
    protected static function connect()
    {
        try {
            $db = new PDO(
                sprintf('mysql:host=%s;dbname=%s;post=%s', self::$MYSQL_HOST, self::getUrl(self::$MYSQL_DB), self::$MYSQL_PORT),
                self::getUrl(self::$MYSQL_USER),
                self::getUrl(self::$MYSQL_PASS)
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (Error $e) {
            // En cas d'erreur, on affiche un message et on arrête le script
            die('Erreur : '. $e->getMessage());
        }
    }

    private static function getUrl($sqlData)
    {
        $rootUrl = Functions::getRootUrl();
        // $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        if ($rootUrl === 'https://adi.ezaya.fr/') {
            if ($sqlData === self::$MYSQL_HOST) {
                self::$MYSQL_DB = "adi_we_love_food";
            }
            if ($sqlData === self::$MYSQL_USER) {
                self::$MYSQL_DB = "adi_clicrepare";
            }
            if ($sqlData === self::$MYSQL_PASS) {
                self::$MYSQL_DB = "Funnyspac3#";
            }
        } else {
            return $sqlData;
        }
    }
}

/* const MYSQL_HOST = "localhost";
const MYSQL_PORT = "3306";
const MYSQL_USER = "root";
const MYSQL_PASS = "";
const MYSQL_DB = "we_love_food";

try {
    // Souvent on identifie cet objet par la variable $conn ou $db
    $db = new PDO(
        sprintf('mysql:host=%s;dbname=%s;post=%s', MYSQL_HOST, MYSQL_DB, MYSQL_PORT),
        MYSQL_USER,
        MYSQL_PASS
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Error $e) {
    // En cas d'erreur, on affiche un message et on arrête le script
    die('Erreur : '. $e->getMessage());
} */
