<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

$error = '';
$success = '';
$role = $_GET['role'] ?? 'etudiant'; // étudiant ou loueur par défaut

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $full_name = htmlspecialchars(trim($_POST['full_name'] ?? ''));
    $role = htmlspecialchars(trim($_POST['role'] ?? 'etudiant'));

    // Validations
    if (empty($email) || empty($password) || empty($password_confirm) || empty($full_name)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'L\'adresse email n\'est pas valide.';
    } elseif ($password !== $password_confirm) {
        $error = 'Les deux mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            // Insérer le nouvel utilisateur
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (email, password, full_name, role, created_at) VALUES (?, ?, ?, ?, NOW())');
            
            if ($stmt->execute([$email, $password_hash, $full_name, $role])) {
                $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                // Rediriger après 2 secondes
                header('refresh:2;url=login.php');
            } else {
                $error = 'Erreur lors de l\'inscription. Veuillez réessayer.';
            }
        }
    }
}

$page_title = 'Inscription';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div style="max-width: 500px; margin: var(--spacing-xl) auto;">
        <h1 style="text-align: center; color: var(--color-primary);">Créer un compte</h1>
        
        <?php if ($error): ?>
            <div style="background-color: #ffe6e6; color: #cc0000; padding: var(--spacing-md); border-radius: var(--border-radius); margin-bottom: var(--spacing-lg); border-left: 4px solid #cc0000;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background-color: #e6ffe6; color: #00cc00; padding: var(--spacing-md); border-radius: var(--border-radius); margin-bottom: var(--spacing-lg); border-left: 4px solid #00cc00;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="card">
            <div style="margin-bottom: var(--spacing-lg);">
                <label for="full_name">Nom complet</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <label for="role">Vous êtes</label>
                <select id="role" name="role" required>
                    <option value="etudiant" <?php echo $role === 'etudiant' ? 'selected' : ''; ?>>Étudiant(e)</option>
                    <option value="loueur" <?php echo $role === 'loueur' ? 'selected' : ''; ?>>Loueur</option>
                </select>
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <small style="color: var(--color-gray-medium);">Minimum 6 caractères</small>
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: var(--spacing-md);">S'inscrire</button>
        </form>

        <div style="text-align: center; margin-top: var(--spacing-lg);">
            <p>Vous avez déjà un compte ? <a href="login.php" style="color: var(--color-secondary); font-weight: 600;">Connectez-vous</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
