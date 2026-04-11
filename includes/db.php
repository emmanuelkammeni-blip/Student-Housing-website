<?php
// ================================================
// Connexion à la base de données
// ================================================

require_once __DIR__ . '/../config/config.php';

class Database {
    private $pdo;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die('Erreur de connexion à la base de données : ' . $this->error);
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getError() {
        return $this->error;
    }
}

// Instance globale de la base de données
$db = new Database();
$pdo = $db->getConnection();

?>
