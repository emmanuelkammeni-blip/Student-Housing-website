<?php
$page_title = 'Accueil';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Bienvenue sur StudentHousing</h1>
            <p>Trouvez votre logement étudiant idéal ou publiez une annonce en quelques minutes</p>
            <div>
                <?php if (!$is_logged): ?>
                    <a href="register.php" class="btn-primary">Je suis étudiant</a>
                    <a href="register.php" class="btn-secondary">Je suis loueur</a>
                <?php else: ?>
                    <a href="annonces.php" class="btn-primary">Explorer les annonces</a>
                    <?php if ($user_role === 'loueur'): ?>
                        <a href="create-annonce.php" class="btn-secondary">Publier une annonce</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features container">
        <div class="feature-card">
            <h3>🏠 Pour les étudiants</h3>
            <p>Parcourez des centaines d'annonces de logements adaptés à vos besoins et à votre budget.</p>
        </div>
        <div class="feature-card">
            <h3>📋 Candidatez facilement</h3>
            <p>Envoyez vos candidatures directement aux propriétaires et gérez vos favoris en un clic.</p>
        </div>
        <div class="feature-card">
            <h3>🎯 Pour les loueurs</h3>
            <p>Publiez vos annonces, recevez des candidatures et gérez vos logements simplement.</p>
        </div>
        <div class="feature-card">
            <h3>⚡ Rapide et gratuit</h3>
            <p>Vous avez besoin d'un logement ? StudentHousing vous simplifie la recherche !</p>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="container" style="text-align: center; margin: calc(var(--spacing-xl) * 2) 0;">
        <h2>Comment ça marche ?</h2>
        <div class="annonces-grid" style="margin-top: var(--spacing-xl);">
            <div class="card">
                <div style="font-size: 36px; margin-bottom: var(--spacing-md);">1️⃣</div>
                <h3 style="color: var(--color-primary);">Inscrivez-vous</h3>
                <p>Créez votre profil en tant qu'étudiant ou loueur.</p>
            </div>
            <div class="card">
                <div style="font-size: 36px; margin-bottom: var(--spacing-md);">2️⃣</div>
                <h3 style="color: var(--color-primary);">Explorez ou publiez</h3>
                <p>Cherchez le logement parfait ou publiez votre annonce.</p>
            </div>
            <div class="card">
                <div style="font-size: 36px; margin-bottom: var(--spacing-md);">3️⃣</div>
                <h3 style="color: var(--color-primary);">Postulez ou louez</h3>
                <p>Contactez les propriétaires ou recevez des candidatures.</p>
            </div>
        </div>
    </section>

    <!-- Annonces Preview -->
    <section class="container">
        <h2 style="text-align: center; margin: var(--spacing-xl) 0;">Dernières annonces</h2>
        <div class="annonces-grid">
            <div class="annonce-card">
                <div class="annonce-card-image">🏢</div>
                <div class="annonce-card-body">
                    <h3>Studio moderne au centre-ville</h3>
                    <div class="annonce-card-meta">
                        <span>📍 Paris 5e</span>
                        <span class="annonce-price">650€/mois</span>
                    </div>
                    <p>Petit studio cosy et lumineux, proche des transports.</p>
                    <a href="annonces.php" class="btn-primary" style="display: inline-block; width: 100%; text-align: center;">Voir plus</a>
                </div>
            </div>
            <div class="annonce-card">
                <div class="annonce-card-image">🏘️</div>
                <div class="annonce-card-body">
                    <h3>Chambre en colocation</h3>
                    <div class="annonce-card-meta">
                        <span>📍 Lyon</span>
                        <span class="annonce-price">450€/mois</span>
                    </div>
                    <p>Chambre spacieuse dans un T4 avec colocataires sympas.</p>
                    <a href="annonces.php" class="btn-primary" style="display: inline-block; width: 100%; text-align: center;">Voir plus</a>
                </div>
            </div>
            <div class="annonce-card">
                <div class="annonce-card-image">🏠</div>
                <div class="annonce-card-body">
                    <h3>Appartement T2 calme</h3>
                    <div class="annonce-card-meta">
                        <span>📍 Toulouse</span>
                        <span class="annonce-price">550€/mois</span>
                    </div>
                    <p>Bel appartement au calme, idéal pour étudier.</p>
                    <a href="annonces.php" class="btn-primary" style="display: inline-block; width: 100%; text-align: center;">Voir plus</a>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin: var(--spacing-xl) 0;">
            <a href="annonces.php" class="btn-primary">Voir toutes les annonces</a>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
