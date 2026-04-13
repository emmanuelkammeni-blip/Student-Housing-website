<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user_role'] === 'etudiant') {
    $annonce_id = intval($_POST['annonce_id'] ?? 0);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    if ($annonce_id <= 0 || empty($message)) {
        $error = 'Veuillez compléter le message de candidature.';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM candidatures WHERE annonce_id = ? AND user_id = ?');
        $stmt->execute([$annonce_id, $user_id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Vous avez déjà postulé à cette annonce.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO candidatures (annonce_id, user_id, message, status, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
            if ($stmt->execute([$annonce_id, $user_id, $message, 'pending'])) {
                $success = 'Candidature envoyée.';
            } else {
                $error = 'Impossible d\'envoyer la candidature.';
            }
        }
    }
}

if ($_SESSION['user_role'] === 'etudiant') {
    $stmt = $pdo->prepare('SELECT c.*, a.title, a.city, a.price FROM candidatures c JOIN annonces a ON c.annonce_id = a.id WHERE c.user_id = ? ORDER BY c.created_at DESC');
    $stmt->execute([$user_id]);
    $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare('SELECT c.*, a.title, u.full_name AS candidate_name FROM candidatures c JOIN annonces a ON c.annonce_id = a.id JOIN users u ON c.user_id = u.id WHERE a.user_id = ? ORDER BY c.created_at DESC');
    $stmt->execute([$user_id]);
    $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$page_title = 'Candidatures';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section style="margin-bottom: var(--spacing-xl);">
        <h1>Candidatures</h1>
        <?php if ($error): ?>
            <div style="background-color: #ffe6e6; color: #cc0000; padding: var(--spacing-md); border-radius: var(--border-radius); margin-bottom: var(--spacing-lg); border-left: 4px solid #cc0000;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background-color: #e6ffe6; color: #00aa00; padding: var(--spacing-md); border-radius: var(--border-radius); margin-bottom: var(--spacing-lg); border-left: 4px solid #00aa00;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (count($candidatures) === 0): ?>
            <div class="card" style="text-align:center; padding: var(--spacing-xl);">
                <p>Aucune candidature pour le moment.</p>
                <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                    <a href="annonces.php" class="btn-secondary" style="margin-top: var(--spacing-md); display:inline-block;">Voir les annonces</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: var(--spacing-lg);">
                <?php foreach ($candidatures as $candidature): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($candidature['title']); ?></h3>
                        <p style="margin: var(--spacing-sm) 0;"><strong>Ville :</strong> <?php echo htmlspecialchars($candidature['city'] ?? ''); ?> - <strong>Prix :</strong> <?php echo number_format($candidature['price'] ?? 0, 0, ',', ' '); ?> €/mois</p>
                        <?php if ($_SESSION['user_role'] === 'loueur'): ?>
                            <p><strong>Candidat :</strong> <?php echo htmlspecialchars($candidature['candidate_name']); ?></p>
                        <?php endif; ?>
                        <p><strong>Message :</strong><br><?php echo nl2br(htmlspecialchars($candidature['message'])); ?></p>
                        <p><strong>Statut :</strong> <?php echo ucfirst($candidature['status']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
        <section class="card" style="padding: var(--spacing-xl);">
            <h2>Postuler à une annonce</h2>
            <p>Collez ici l'ID de l'annonce et votre message pour postuler.</p>
            <form method="POST" style="display: grid; gap: var(--spacing-lg);">
                <div>
                    <label for="annonce_id">ID de l'annonce</label>
                    <input type="number" id="annonce_id" name="annonce_id" required>
                </div>
                <div>
                    <label for="message">Message de candidature</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Envoyer ma candidature</button>
            </form>
        </section>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
