<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);

if (isset($_GET['remove'])) {
    $fav_id = intval($_GET['remove']);
    $stmt = $pdo->prepare('DELETE FROM favoris WHERE id = ? AND user_id = ?');
    $stmt->execute([$fav_id, $user_id]);
    header('Location: ' . APP_URL . '/favoris.php');
    exit;
}

$stmt = $pdo->prepare('SELECT f.id AS fav_id, a.* FROM favoris f JOIN annonces a ON f.annonce_id = a.id WHERE f.user_id = ? ORDER BY f.created_at DESC');
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Favoris';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section>
        <h1>Mes favoris</h1>
        <?php if (count($favorites) === 0): ?>
            <div class="card" style="text-align: center; padding: var(--spacing-xl);">
                <p>Vous n'avez pas encore de favoris.</p>
                <a href="annonces.php" class="btn-secondary" style="margin-top: var(--spacing-md); display: inline-block;">Voir les annonces</a>
            </div>
        <?php else: ?>
            <div class="annonces-grid">
                <?php foreach ($favorites as $annonce): ?>
                    <article class="annonce-card">
                        <div class="annonce-card-image"><?php echo strtoupper(substr($annonce['title'], 0, 1)); ?></div>
                        <div class="annonce-card-body">
                            <h3><?php echo htmlspecialchars($annonce['title']); ?></h3>
                            <div class="annonce-card-meta">
                                <span>📍 <?php echo htmlspecialchars($annonce['city']); ?></span>
                                <span class="annonce-price"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> €/mois</span>
                            </div>
                            <a href="annonce.php?id=<?php echo $annonce['id']; ?>" class="btn-secondary" style="display: inline-block; margin-top: var(--spacing-md);">Voir</a>
                            <a href="favoris.php?remove=<?php echo $annonce['fav_id']; ?>" class="btn-primary" style="display: inline-block; margin-top: var(--spacing-md); background-color: var(--color-secondary);">Retirer</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
