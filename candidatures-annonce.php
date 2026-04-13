<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'loueur') {
    header('Location: ' . APP_URL . '/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);

$stmt = $pdo->prepare('SELECT c.*, a.title AS annonce_title, u.full_name AS candidate_name, u.email AS candidate_email FROM candidatures c JOIN annonces a ON c.annonce_id = a.id JOIN users u ON c.user_id = u.id WHERE a.user_id = ? ORDER BY c.created_at DESC');
$stmt->execute([$user_id]);
$candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Candidatures reçues';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section>
        <h1>Candidatures reçues</h1>
        <?php if (count($candidatures) === 0): ?>
            <div class="card" style="text-align:center; padding: var(--spacing-xl);">
                <p>Aucune candidature reçue pour le moment.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: var(--spacing-lg);">
                <?php foreach ($candidatures as $candidature): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($candidature['annonce_title']); ?></h3>
                        <p style="margin: var(--spacing-sm) 0;"><strong>Candidat :</strong> <?php echo htmlspecialchars($candidature['candidate_name']); ?> (<?php echo htmlspecialchars($candidature['candidate_email']); ?>)</p>
                        <p><strong>Message :</strong><br><?php echo nl2br(htmlspecialchars($candidature['message'])); ?></p>
                        <p><strong>Statut :</strong> <?php echo ucfirst($candidature['status']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
