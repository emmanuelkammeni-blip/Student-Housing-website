<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

$city = htmlspecialchars(trim($_GET['city'] ?? ''));
$where = 'WHERE a.status = ?';
$params = ['active'];

if (!empty($city)) {
    $where .= ' AND a.city LIKE ?';
    $params[] = '%' . $city . '%';
}

$sql = "SELECT a.*, u.full_name, u.city AS owner_city FROM annonces a JOIN users u ON a.user_id = u.id $where ORDER BY a.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Annonces';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section style="margin-bottom: var(--spacing-xl);">
        <h1>Rechercher un logement</h1>
        <form method="GET" style="display: flex; gap: var(--spacing-md); flex-wrap: wrap; align-items: center;">
            <input type="text" name="city" placeholder="Ville ou quartier" value="<?php echo htmlspecialchars($city); ?>" style="flex: 1; min-width: 220px; padding: var(--spacing-sm); border-radius: var(--border-radius); border: 1px solid var(--border-color);">
            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </section>

    <section class="annonces-grid">
        <?php if (count($annonces) === 0): ?>
            <div class="card" style="grid-column: 1 / -1; text-align: center; padding: var(--spacing-xl);">
                <h2>Aucune annonce trouvée</h2>
                <p>Essayez une autre ville ou rechargez la page.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($annonces as $annonce): ?>
            <article class="annonce-card">
                <div class="annonce-card-image"><?php echo strtoupper(substr($annonce['title'], 0, 1)); ?></div>
                <div class="annonce-card-body">
                    <h3><?php echo htmlspecialchars($annonce['title']); ?></h3>
                    <div class="annonce-card-meta">
                        <span>📍 <?php echo htmlspecialchars($annonce['city']); ?></span>
                        <span class="annonce-price"><?php echo number_format($annonce['price'], 0, ',', ' '); ?> €/mois</span>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars(substr($annonce['description'], 0, 120))); ?>...</p>
                    <a href="annonce.php?id=<?php echo $annonce['id']; ?>" class="btn-secondary" style="display: inline-block; margin-top: var(--spacing-md);">Voir l'annonce</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
