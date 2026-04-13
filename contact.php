<?php
$page_title = 'Contact';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 900px; margin: var(--spacing-xl) auto;">
    <section class="card" style="padding: var(--spacing-xl);">
        <h1>Contact</h1>
        <p>Besoin d'aide ou d'informations ? Envoyez-nous un message et nous vous répondrons rapidement.</p>

        <form method="POST" style="display: grid; gap: var(--spacing-lg);">
            <div>
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="subject">Sujet</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div>
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Envoyer</button>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
