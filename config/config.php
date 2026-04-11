<?php
// ================================================
// Configuration StudentHousing
// ================================================

// Base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'studenthousing_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);

// Application
define('APP_NAME', 'StudentHousing');
define('APP_URL', 'http://localhost/StudentHousing');
define('APP_ROOT', __DIR__ . '/..');

// Chemin uploads
define('UPLOADS_DIR', APP_ROOT . '/uploads');
define('PROFILES_DIR', UPLOADS_DIR . '/profiles');
define('ANNONCES_DIR', UPLOADS_DIR . '/annonces');

// Sessions
define('SESSION_TIMEOUT', 3600); // 1 heure
define('SESSION_NAME', 'studenthousing_session');

// Sécurité
define('HASH_ALGO', 'sha256');

?>
