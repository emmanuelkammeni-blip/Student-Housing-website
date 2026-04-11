<?php
// ================================================
// Gestion de l'authentification
// ================================================

require_once __DIR__ . '/../config/config.php';

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Vérifie si un utilisateur est connecté
     */
    public function isLogged() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Obtient l'ID de l'utilisateur connecté
     */
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Obtient le rôle de l'utilisateur
     */
    public function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Vérifie si l'utilisateur est étudiant
     */
    public function isStudent() {
        return $this->getUserRole() === 'etudiant';
    }

    /**
     * Vérifie si l'utilisateur est loueur
     */
    public function isLandlord() {
        return $this->getUserRole() === 'loueur';
    }

    /**
     * Enregistre l'utilisateur en session
     */
    public function login($user_id, $user_role, $user_email) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_role'] = $user_role;
        $_SESSION['user_email'] = $user_email;
        $_SESSION['login_time'] = time();
        return true;
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout() {
        session_destroy();
        return true;
    }

    /**
     * Vérifie la session
     */
    public function checkSession() {
        if (!$this->isLogged()) {
            return false;
        }

        // Vérifier le timeout
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > SESSION_TIMEOUT)) {
            $this->logout();
            return false;
        }

        return true;
    }

    /**
     * Requiert une connexion
     */
    public function requireLogin() {
        if (!$this->isLogged()) {
            header('Location: ' . APP_URL . '/login.php');
            exit;
        }
    }

    /**
     * Requiert un rôle spécifique
     */
    public function requireRole($role) {
        if (!$this->isLogged() || $this->getUserRole() !== $role) {
            header('Location: ' . APP_URL . '/');
            exit;
        }
    }
}

$auth = new Auth($pdo);

?>
