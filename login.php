<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['user_id'])) {
    $dashboard = $_SESSION['user_role'] === 'loueur' ? 'dashboard-loueur.php' : 'dashboard-etudiant.php';
    header('Location: ' . APP_URL . '/' . $dashboard);
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email et mot de passe sont obligatoires.';
    } else {
        // Chercher l'utilisateur
        $stmt = $pdo->prepare('SELECT id, email, password, full_name, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Email ou mot de passe incorrect.';
        } else {
            // Connecter l'utilisateur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['login_time'] = time();

            // Rediriger vers le dashboard approprié
            $dashboard = $user['role'] === 'loueur' ? 'dashboard-loueur.php' : 'dashboard-etudiant.php';
            header('Location: ' . APP_URL . '/' . $dashboard);
            exit;
        }
    }
}

$page_title = 'Connexion';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div style="max-width: 500px; margin: var(--spacing-xl) auto;">
        <h1 style="text-align: center; color: var(--color-primary);">Se connecter</h1>
        
        <?php if ($error): ?>
            <div style="background-color: #ffe6e6; color: #cc0000; padding: var(--spacing-md); border-radius: var(--border-radius); margin-bottom: var(--spacing-lg); border-left: 4px solid #cc0000;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="card">
            <div style="margin-bottom: var(--spacing-lg);">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div style="margin-bottom: var(--spacing-lg);">
                <a href="mot-de-passe-oublie.php" style="color: var(--color-primary); font-size: var(--font-size-small);">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: var(--spacing-md);">Se connecter</button>
        </form>

        <div style="text-align: center; margin-top: var(--spacing-lg);">
            <p>Pas encore de compte ? 
                <a href="register.php?role=etudiant" style="color: var(--color-secondary); font-weight: 600;">S'inscrire comme étudiant</a> 
                ou 
                <a href="register.php?role=loueur" style="color: var(--color-secondary); font-weight: 600;">comme loueur</a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
