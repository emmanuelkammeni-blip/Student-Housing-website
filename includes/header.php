<?php
// ================================================
// En-tête du site
// ================================================

session_start();
require_once __DIR__ . '/../config/config.php';

$is_logged = isset($_SESSION['user_id']);
$user_role = $_SESSION['user_role'] ?? null;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . APP_NAME : APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/variables.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <a href="<?php echo APP_URL; ?>/">
                        <span class="logo"><?php echo APP_NAME; ?></span>
                    </a>
                </div>
                <ul class="navbar-menu">
                    <li><a href="<?php echo APP_URL; ?>/annonces.php">Annonces</a></li>
                    
                    <?php if ($is_logged): ?>
                        <li><a href="<?php echo APP_URL; ?>/favoris.php">Favoris</a></li>
                        <li><a href="<?php echo APP_URL; ?>/candidatures.php">Mes candidatures</a></li>
                        <li><a href="<?php echo APP_URL; ?>/profil.php">Profil</a></li>
                        <li><a href="<?php echo APP_URL; ?>/logout.php" class="btn-logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo APP_URL; ?>/login.php" class="btn-primary-link">Connexion</a></li>
                        <li><a href="<?php echo APP_URL; ?>/register.php" class="btn-secondary-link">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main class="main-content">
