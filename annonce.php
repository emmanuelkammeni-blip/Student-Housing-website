<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . APP_URL . '/annonces.php');
    exit;
}

$sql = 'SELECT a.*, u.email, u.full_name, u.city AS owner_city FROM annonces a JOIN users u ON a.user_id = u.id WHERE a.id = ? AND a.status = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, 'active']);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    header('Location: ' . APP_URL . '/annonces.php');
    exit;
}

$page_title = htmlspecialchars($annonce['title']);
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section class="card" style="padding: var(--spacing-xl);">
        <h1><?php echo htmlspecialchars($annonce['title']); ?></h1>
        <div class="annonce-card-meta" style="margin: var(--spacing-md) 0; justify-content: flex-start; gap: var(--spacing-lg);">
            <span>📍 <?php echo htmlspecialchars($annonce['city']); ?></span>
            <span>💶 <?php echo number_format($annonce['price'], 0, ',', ' '); ?> €/mois</span>
            <span>🛏️ <?php echo max(1, intval($annonce['bedrooms'])); ?> chambre(s)</span>
        </div>
        <p style="margin-bottom: var(--spacing-lg); line-height: 1.8; color: var(--color-text-dark);">
            <?php echo nl2br(htmlspecialchars($annonce['description'])); ?>
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--spacing-md); margin-bottom: var(--spacing-xl);">
            <div class="card" style="background-color: #fff8f3; border: 1px solid rgba(255, 105, 180, 0.15);">
                <strong>Adresse</strong>
                <p><?php echo htmlspecialchars($annonce['address']); ?></p>
            </div>
            <div class="card" style="background-color: #f3f6ff; border: 1px solid rgba(128, 147, 241, 0.15);">
                <strong>Surface</strong>
                <p><?php echo htmlspecialchars($annonce['surface'] ?? '—'); ?> m²</p>
            </div>
            <div class="card" style="background-color: #f9f1ff; border: 1px solid rgba(255, 105, 180, 0.15);">
                <strong>Loueur</strong>
                <p><?php echo htmlspecialchars($annonce['full_name']); ?>, <?php echo htmlspecialchars($annonce['owner_city']); ?></p>
            </div>
        </div>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'etudiant'): ?>
            <a href="candidatures.php?announce_id=<?php echo $annonce['id']; ?>" class="btn-primary">Postuler à cette annonce</a>
        <?php else: ?>
            <p style="color: var(--color-gray-medium);">Connectez-vous en tant qu'étudiant pour postuler.</p>
        <?php endif; ?>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
