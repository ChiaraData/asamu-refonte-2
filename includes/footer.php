<?php
require_once __DIR__ . '/config.php';
$mainScriptVersion = (string) filemtime(__DIR__ . '/../assets/js/main.js');
$v2ScriptVersion = (string) filemtime(__DIR__ . '/../assets/js/v2.js');
?>
</main>
<section class="partners-section" aria-labelledby="partners-title">
    <div class="container">
        <div class="section-heading inline-heading">
            <div>
                <p class="eyebrow">Ils nous accompagnent</p>
                <h2 id="partners-title">Nos partenaires précieux</h2>
            </div>
        </div>

        <div class="partners-grid">
            <?php foreach ($partners as $partner): ?>
                <a
                    class="partner-card"
                    href="<?= e($partner['url']) ?>"
                    <?= $partner['url'] !== '#' ? 'target="_blank" rel="noopener"' : '' ?>
                >
                    <?php if (!empty($partner['logo'])): ?>
                        <img
                            class="partner-logo"
                            src="<?= e($partner['logo']) ?>"
                            alt="Logo <?= e($partner['name']) ?>"
                            loading="lazy"
                            decoding="async"
                        >
                    <?php else: ?>
                        <span><?= e(strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $partner['name']), 0, 2))) ?></span>
                    <?php endif; ?>

                    <strong><?= e($partner['name']) ?></strong>
                    <small><?= e($partner['description']) ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<footer class="v2-footer">
    <div class="container v2-footer-top">
        <div class="v2-footer-intro">
            <a class="v2-footer-brand" href="index.php">
                <img src="assets/img/logo-asamu-v2.png" alt="Logo AS amU">
                <span>AS amU</span>
            </a>
            <p>Apprendre, participer, gagner — dans une pratique sportive ouverte, encadrée et conviviale.</p>
        </div>
        <div class="v2-footer-column v2-footer-quick-links">
            <h2>Accès rapides</h2>
            <a href="association.php">Organisation de l’association</a>
            <a href="<?= e($site['helloasso_url']) ?>" target="_blank" rel="noopener">Adhérer via HelloAsso</a>
            <a href="sections.php">Trouver sa section</a>
            <a href="calendrier.php">Calendrier des compétitions</a>
            <a href="coachs.php">Coachs <?= e($site['season']) ?></a>
            <a href="documents.php">Documents utiles</a>
        </div>
        <div class="v2-footer-column">
            <h2>Pour commencer</h2>
            <a href="boussole.php">La boussole du sport</a>
            <a href="adhesion.php">Adhérer via HelloAsso</a>
        </div>
        <div class="v2-footer-column">
            <h2>Nous joindre</h2>
            <p><?= e($site['address']) ?></p>
            <a href="tel:+33623928914"><?= e($site['phone']) ?></a>
            <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
        </div>
    </div>
    <div class="container v2-footer-bottom">
        <small>© <?= date('Y') ?> <?= e($site['full_name']) ?>.</small>
        <nav class="v2-footer-legal" aria-label="Informations légales">
            <a href="mentions-legales.php">Mentions légales</a>
            <a href="confidentialite.php">Confidentialité</a>
            <a href="<?= e($site['instagram_url']) ?>" target="_blank" rel="noopener">Instagram <span aria-hidden="true">↗</span></a>
        </nav>
    </div>
</footer>
<script src="assets/js/main.js?v=<?= e($mainScriptVersion) ?>"></script>
<script src="assets/js/v2.js?v=<?= e($v2ScriptVersion) ?>"></script>
</body>
</html>
