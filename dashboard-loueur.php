<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'loueur') {
    header('Location: ' . APP_URL . '/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM annonces WHERE user_id = ?');
$stmt->execute([$user_id]);
$annonces_count = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM candidatures c JOIN annonces a ON c.annonce_id = a.id WHERE a.user_id = ?');
$stmt->execute([$user_id]);
$candidatures_count = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT a.* FROM annonces a WHERE a.user_id = ? ORDER BY a.created_at DESC LIMIT 3');
$stmt->execute([$user_id]);
$my_annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Tableau de bord loueur';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section style="margin-bottom: var(--spacing-xl);">
        <h1>Bonjour, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Votre espace loueur pour gérer vos annonces et vos candidatures reçues.</p>
    </section>

    <section class="features">
        <div class="feature-card">
            <h3>Mes annonces</h3>
            <p><?php echo intval($annonces_count); ?> annonces publiées</p>
            <a href="create-annonce.php" class="btn-primary">Publier une annonce</a>
        </div>
        <div class="feature-card">
            <h3>Candidatures reçues</h3>
            <p><?php echo intval($candidatures_count); ?> demandes reçues</p>
            <a href="candidatures-annonce.php" class="btn-primary">Voir les candidatures</a>
        </div>
        <div class="feature-card">
            <h3>Profil loueur</h3>
            <p>Modifiez vos informations et restez visible.</p>
            <a href="profil.php" class="btn-primary">Voir mon profil</a>
        </div>
    </section>

    <section>
        <h2>Mes dernières annonces</h2>
        <div class="annonces-grid">
            <?php foreach ($my_annonces as $annonce): ?>
                <article class="annonce-card">
                    <div class="annonce-card-image"><?php echo strtoupper(substr($annonce['title'], 0, 1)); ?></div>
                    <div class="annonce-card-body">
                        <h3><?php echo htmlspecialchars($annonce['title']); ?></h3>
                        <div class="annonce-card-meta">
                            <span>📍 <?php echo htmlspecialchars($annonce['city']); ?></span>
                            <span class="annonce-price"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> €/mois</span>
                        </div>
                        <a href="edit-annonce.php?id=<?php echo $annonce['id']; ?>" class="btn-secondary">Modifier</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
