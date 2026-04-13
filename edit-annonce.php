<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'loueur') {
    header('Location: ' . APP_URL . '/login.php');
    exit;
}

$annonce_id = intval($_GET['id'] ?? 0);
if ($annonce_id <= 0) {
    header('Location: ' . APP_URL . '/dashboard-loueur.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM annonces WHERE id = ? AND user_id = ?');
$stmt->execute([$annonce_id, $_SESSION['user_id']]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    header('Location: ' . APP_URL . '/dashboard-loueur.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $city = htmlspecialchars(trim($_POST['city'] ?? ''));
    $address = htmlspecialchars(trim($_POST['address'] ?? ''));
    $postal_code = htmlspecialchars(trim($_POST['postal_code'] ?? ''));
    $price = floatval($_POST['price'] ?? 0);
    $bedrooms = intval($_POST['bedrooms'] ?? 0);
    $bathrooms = intval($_POST['bathrooms'] ?? 0);
    $surface = floatval($_POST['surface'] ?? 0);
    $furnished = isset($_POST['furnished']) ? 1 : 0;
    $pets_allowed = isset($_POST['pets_allowed']) ? 1 : 0;
    $status = in_array($_POST['status'] ?? '', ['active', 'inactive', 'rented']) ? $_POST['status'] : 'active';

    if (empty($title) || empty($description) || empty($city) || empty($address) || $price <= 0) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        $stmt = $pdo->prepare('UPDATE annonces SET title = ?, description = ?, price = ?, city = ?, address = ?, postal_code = ?, bedrooms = ?, bathrooms = ?, surface = ?, furnished = ?, pets_allowed = ?, status = ?, updated_at = NOW() WHERE id = ? AND user_id = ?');
        if ($stmt->execute([$title, $description, $price, $city, $address, $postal_code, $bedrooms, $bathrooms, $surface, $furnished, $pets_allowed, $status, $annonce_id, $_SESSION['user_id']])) {
            $success = 'Annonce mise à jour avec succès.';
            $stmt = $pdo->prepare('SELECT * FROM annonces WHERE id = ? AND user_id = ?');
            $stmt->execute([$annonce_id, $_SESSION['user_id']]);
            $annonce = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Impossible de mettre à jour l\'annonce.';
        }
    }
}

$page_title = 'Modifier l\'annonce';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section style="max-width: 800px; margin: var(--spacing-xl) auto;">
        <h1>Modifier l'annonce</h1>

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

        <form method="POST" class="card">
            <div style="margin-bottom: var(--spacing-lg);">
                <label for="title">Titre de l'annonce</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($annonce['title']); ?>" required>
            </div>
            <div style="margin-bottom: var(--spacing-lg);">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($annonce['description']); ?></textarea>
            </div>
            <div style="display: grid; gap: var(--spacing-lg); grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: var(--spacing-lg);">
                <div>
                    <label for="city">Ville</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($annonce['city']); ?>" required>
                </div>
                <div>
                    <label for="address">Adresse</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($annonce['address']); ?>" required>
                </div>
            </div>
            <div style="display: grid; gap: var(--spacing-lg); grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-bottom: var(--spacing-lg);">
                <div>
                    <label for="postal_code">Code postal</label>
                    <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($annonce['postal_code']); ?>">
                </div>
                <div>
                    <label for="price">Prix (€)</label>
                    <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($annonce['price']); ?>" required>
                </div>
            </div>
            <div style="display: grid; gap: var(--spacing-lg); grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-bottom: var(--spacing-lg);">
                <div>
                    <label for="bedrooms">Chambres</label>
                    <input type="number" id="bedrooms" name="bedrooms" value="<?php echo htmlspecialchars($annonce['bedrooms']); ?>">
                </div>
                <div>
                    <label for="bathrooms">Salles d'eau</label>
                    <input type="number" id="bathrooms" name="bathrooms" value="<?php echo htmlspecialchars($annonce['bathrooms']); ?>">
                </div>
                <div>
                    <label for="surface">Surface (m²)</label>
                    <input type="number" step="0.1" id="surface" name="surface" value="<?php echo htmlspecialchars($annonce['surface']); ?>">
                </div>
            </div>
            <div style="display: flex; gap: var(--spacing-lg); flex-wrap: wrap; margin-bottom: var(--spacing-lg);">
                <label><input type="checkbox" name="furnished" <?php echo $annonce['furnished'] ? 'checked' : ''; ?>> Meublé</label>
                <label><input type="checkbox" name="pets_allowed" <?php echo $annonce['pets_allowed'] ? 'checked' : ''; ?>> Animaux autorisés</label>
            </div>
            <div style="margin-bottom: var(--spacing-lg);">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="active" <?php echo $annonce['status'] === 'active' ? 'selected' : ''; ?>>Actif</option>
                    <option value="inactive" <?php echo $annonce['status'] === 'inactive' ? 'selected' : ''; ?>>Inactif</option>
                    <option value="rented" <?php echo $annonce['status'] === 'rented' ? 'selected' : ''; ?>>Loué</option>
                </select>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Mettre à jour</button>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
