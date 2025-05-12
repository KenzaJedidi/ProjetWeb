<?php
class config {
    private static $pdo = NULL;

    public static function getConnexion() {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=localoo', 
                    'root', 
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                );
            } catch(Exception $e) {
                die('Connection Error: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

define('BASE_URL', 'http://localhost/amal/');
?>
