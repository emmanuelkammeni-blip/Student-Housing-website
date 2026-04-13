<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'etudiant') {
    header('Location: ' . APP_URL . '/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM favoris WHERE user_id = ?');
$stmt->execute([$user_id]);
$favoris_count = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM candidatures WHERE user_id = ?');
$stmt->execute([$user_id]);
$candidatures_count = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT a.* FROM annonces a ORDER BY a.created_at DESC LIMIT 3');
$stmt->execute();
$recent_annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Tableau de bord étudiant';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section style="margin-bottom: var(--spacing-xl);">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Votre espace étudiant pour suivre vos favoris, candidatures et annonces.</p>
    </section>

    <section class="features">
        <div class="feature-card">
            <h3>Favoris</h3>
            <p><?php echo intval($favoris_count); ?> annonces enregistrées</p>
            <a href="favoris.php" class="btn-primary">Voir mes favoris</a>
        </div>
        <div class="feature-card">
            <h3>Candidatures</h3>
            <p><?php echo intval($candidatures_count); ?> candidatures en cours</p>
            <a href="candidatures.php" class="btn-primary">Voir mes candidatures</a>
        </div>
        <div class="feature-card">
            <h3>Rechercher</h3>
            <p>Explorez les dernières annonces disponibles.</p>
            <a href="annonces.php" class="btn-primary">Rechercher</a>
        </div>
    </section>

    <section>
        <h2>Dernières annonces</h2>
        <div class="annonces-grid">
            <?php foreach ($recent_annonces as $annonce): ?>
                <article class="annonce-card">
                    <div class="annonce-card-image"><?php echo strtoupper(substr($annonce['title'], 0, 1)); ?></div>
                    <div class="annonce-card-body">
                        <h3><?php echo htmlspecialchars($annonce['title']); ?></h3>
                        <div class="annonce-card-meta">
                            <span>📍 <?php echo htmlspecialchars($annonce['city']); ?></span>
                            <span class="annonce-price"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> €/mois</span>
                        </div>
                        <a href="annonce.php?id=<?php echo $annonce['id']; ?>" class="btn-secondary" style="display: inline-block; margin-top: var(--spacing-md);">Voir</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
