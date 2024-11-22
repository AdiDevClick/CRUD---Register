<?php

// Charger la configuration
require_once dirname(__DIR__, 2) . '/config/config.php';
require_once ROOT_PATH . "includes" . DIRECTORY_SEPARATOR ."class-autoloader.inc.php";

use Dotenv\Dotenv;

class Mysql
{
    private static $MYSQL_HOST;
    private static $MYSQL_PORT;
    private static $MYSQL_USER;
    private static $MYSQL_PASS;
    private static $MYSQL_DB;

    private static function init()
    {
        // Déterminer l'environnement courant
        $appEnv = getenv('APP_ENV') ?: 'production';

        // Charger le fichier .env approprié en fonction de l'environnement
        $envFile = $appEnv === 'dev' ? '.env.dev' : '.env';
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2), $envFile);
        $dotenv->load();

        self::$MYSQL_HOST = $_ENV['MYSQL_HOST'];
        self::$MYSQL_PORT = $_ENV['MYSQL_PORT'];
        self::$MYSQL_USER = $_ENV['MYSQL_USER'];
        self::$MYSQL_PASS = $_ENV['MYSQL_PASS'];
        self::$MYSQL_DB = $_ENV['MYSQL_DB'];
    }

    protected static function connect()
    {
        try {
            // Initialiser les variables d'environnement
            self::init();
            $db = new PDO(
                sprintf('mysql:host=%s;dbname=%s;port=%s', self::$MYSQL_HOST, self::$MYSQL_DB, self::$MYSQL_PORT),
                self::$MYSQL_USER,
                self::$MYSQL_PASS
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
