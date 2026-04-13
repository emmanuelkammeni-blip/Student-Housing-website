<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'loueur') {
    header('Location: ' . APP_URL . '/login.php');
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

    if (empty($title) || empty($description) || empty($city) || empty($address) || $price <= 0) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO annonces (user_id, title, description, price, city, address, postal_code, bedrooms, bathrooms, surface, furnished, pets_allowed, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        if ($stmt->execute([$_SESSION['user_id'], $title, $description, $price, $city, $address, $postal_code, $bedrooms, $bathrooms, $surface, $furnished, $pets_allowed, 'active'])) {
            $success = 'Annonce créée avec succès.';
        } else {
            $error = 'Impossible de créer l\'annonce.';
        }
    }
}

$page_title = 'Publier une annonce';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section style="max-width: 800px; margin: var(--spacing-xl) auto;">
        <h1>Publier une annonce</h1>

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
                <input type="text" id="title" name="title" required>
            </div>
            <div style="margin-bottom: var(--spacing-lg);">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="6" required></textarea>
            </div>
            <div style="display: grid; gap: var(--spacing-lg); grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: var(--spacing-lg);">
                <div>
                    <label for="city">Ville</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div>
                    <label for="address">Adresse</label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>
            <div style="display: grid; gap: var(--spacing-lg); grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-bottom: var(--spacing-lg);">
                <div>
                    <label for="postal_code">Code postal</label>
                    <input type="text" id="postal_code" name="postal_code">
                </div>
                <div>
                    <label for="price">Prix (€)</label>
                    <input type="number" step="0.01" id="price" name="price" required>
                </div>
            </div>
            <div style="display: grid; gap: var(--spacing-lg); grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-bottom: var(--spacing-lg);">
                <div>
                    <label for="bedrooms">Chambres</label>
                    <input type="number" id="bedrooms" name="bedrooms">
                </div>
                <div>
                    <label for="bathrooms">Salles d'eau</label>
                    <input type="number" id="bathrooms" name="bathrooms">
                </div>
                <div>
                    <label for="surface">Surface (m²)</label>
                    <input type="number" step="0.1" id="surface" name="surface">
                </div>
            </div>
            <div style="display: flex; gap: var(--spacing-lg); flex-wrap: wrap; margin-bottom: var(--spacing-lg);">
                <label><input type="checkbox" name="furnished"> Meublé</label>
                <label><input type="checkbox" name="pets_allowed"> Animaux autorisés</label>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Publier</button>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
