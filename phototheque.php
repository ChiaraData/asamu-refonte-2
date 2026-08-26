<?php
$pageTitle = 'Photothèque';
$pageDescription = 'Photos des équipes, compétitions, événements et moments de vie de l’AS amU.';
require_once __DIR__ . '/includes/header.php';

$galleryAssetPrefix = '';
?>
<section class="page-hero compact photo-hero">
    <div class="container">
        <p class="eyebrow">Moments AS amU</p>
        <h1>La photothèque.</h1>
        <p class="lead">Retrouve ici les équipes, compétitions et événements qui font vivre l’association tout au long de la saison.</p>
    </div>
</section>

<section class="section container photo-gallery-section">
    <div class="section-heading inline-heading">
        <div>
            <p class="eyebrow">Galerie</p>
            <h2>Les souvenirs de la saison</h2>
        </div>
        <a class="text-link" href="mailto:<?= e($site['communication_email']) ?>?subject=<?= rawurlencode('Photo pour la photothèque AS amU') ?>">Envoyer une photo à l’AS amU →</a>
    </div>

    <?php if ($photoGallery): ?>
        <div class="photo-gallery-grid">
            <?php foreach ($photoGallery as $photo): ?>
                <?php
                $image = (string) ($photo['image'] ?? '');
                $photoUrl = str_starts_with($image, 'http') ? $image : $galleryAssetPrefix . ltrim($image, '/');
                ?>
                <figure class="photo-card">
                    <img src="<?= e($photoUrl) ?>" alt="<?= e((string) ($photo['alt'] ?? $photo['title'] ?? 'Photo AS amU')) ?>" loading="lazy" decoding="async">
                    <figcaption>
                        <?php if (!empty($photo['category'])): ?><span><?= e((string) $photo['category']) ?></span><?php endif; ?>
                        <h3><?= e((string) ($photo['title'] ?? 'Équipe AS amU')) ?></h3>
                        <?php if (!empty($photo['description'])): ?><p><?= e((string) $photo['description']) ?></p><?php endif; ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="photo-gallery-empty">
            <span class="photo-gallery-empty-icon" aria-hidden="true">✦</span>
            <div>
                <h2>La première photo arrive bientôt.</h2>
                <p>Les prochains souvenirs de l’AS amU seront bientôt à découvrir ici.</p>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
