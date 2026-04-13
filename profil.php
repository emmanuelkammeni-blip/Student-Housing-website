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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = htmlspecialchars(trim($_POST['full_name'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $city = htmlspecialchars(trim($_POST['city'] ?? ''));
    $bio = htmlspecialchars(trim($_POST['bio'] ?? ''));

    if (empty($full_name)) {
        $error = 'Le nom complet est requis.';
    } else {
        $stmt = $pdo->prepare('UPDATE users SET full_name = ?, phone = ?, city = ?, bio = ?, updated_at = NOW() WHERE id = ?');
        if ($stmt->execute([$full_name, $phone, $city, $bio, $user_id])) {
            $success = 'Profil mis à jour avec succès.';
            $_SESSION['user_name'] = $full_name;
        } else {
            $error = 'Impossible de mettre à jour le profil.';
        }
    }
}

$stmt = $pdo->prepare('SELECT email, full_name, role, phone, city, bio, profile_picture FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$page_title = 'Mon profil';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section style="max-width: 700px; margin: var(--spacing-xl) auto;">
        <h1>Mon profil</h1>

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

        <section class="card" style="padding: var(--spacing-xl);">
            <div style="display: flex; gap: var(--spacing-lg); flex-wrap: wrap; align-items: center; margin-bottom: var(--spacing-lg);">
                <div style="width: 100px; height: 100px; border-radius: 50%; background-color: var(--color-primary); display: flex; align-items: center; justify-content: center; color: var(--color-white); font-size: 32px;"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
                <div>
                    <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
                    <p style="color: var(--color-gray-medium); margin: 0;"><?php echo htmlspecialchars($user['email']); ?></p>
                    <p style="margin: var(--spacing-sm) 0 0;">Rôle : <strong><?php echo ucfirst($user['role']); ?></strong></p>
                </div>
            </div>

            <form method="POST">
                <div style="margin-bottom: var(--spacing-lg);">
                    <label for="full_name">Nom complet</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div style="margin-bottom: var(--spacing-lg);">
                    <label for="phone">Téléphone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                <div style="margin-bottom: var(--spacing-lg);">
                    <label for="city">Ville</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
                </div>
                <div style="margin-bottom: var(--spacing-lg);">
                    <label for="bio">À propos de moi</label>
                    <textarea id="bio" name="bio" rows="5"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Enregistrer</button>
            </form>
        </section>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
