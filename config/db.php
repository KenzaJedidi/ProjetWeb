<?php
class Config {
    private static ?PDO $pdo = null;

    public static function getConnexion(): PDO {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=localoo;charset=utf8',
                    'root',  // Utilisateur MySQL
                    '',       // Mot de passe MySQL
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                error_log("✅ Connexion DB réussie");
            } catch (PDOException $e) {
                error_log("💥 ERREUR CONNEXION : " . $e->getMessage());
                die("Erreur de connexion à la base de données");
            }
        }
        return self::$pdo;
    }
}
?>