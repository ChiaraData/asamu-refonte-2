<?php
$pageTitle = 'Documents';
$pageDescription = 'Tous les documents utiles AS amU : association, adhésion, sections et compétitions.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">L’association</p>
        <h1>Documents utiles.</h1>
        <p class="lead">Une page unique pour retrouver les documents de gouvernance, les formulaires et les fichiers nécessaires aux compétitions.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="adhesion.php">Adhésion HelloAsso</a>
            <a class="btn btn-secondary" href="competitions.php#documents">Documents compétition</a>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-heading inline-heading">
        <div>
            <p class="eyebrow">Gouvernance</p>
            <h2>Documents de l’association</h2>
        </div>
    </div>
    <div class="document-grid">
        <?php foreach ($associationDocuments as $doc): ?>
            <article class="card document-card">
                <span class="badge"><?= e($doc['type']) ?></span>
                <h3><?= e($doc['label']) ?></h3>
                <p><?= e($doc['description']) ?></p>
                <a class="text-link" href="<?= e($doc['url']) ?>" target="_blank" rel="noopener">Ouvrir →</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <div class="section-heading inline-heading">
            <div>
                <p class="eyebrow">Compétitions</p>
                <h2>Formulaires et fichiers sportifs</h2>
            </div>
            <a class="text-link" href="competitions.php">Préparer une compétition →</a>
        </div>
        <div class="doc-list">
            <?php foreach ($competitionDocs as $doc): ?>
                <a href="<?= e($doc['url']) ?>" target="_blank" rel="noopener"><?= e($doc['label']) ?><span>↗</span></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
