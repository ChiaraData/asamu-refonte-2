<?php
$pageTitle = 'Statuts, règlement intérieur et charte';
$pageDescription = 'Documents de gouvernance de l’AS amU : statuts, règlement intérieur et charte.';
require_once __DIR__ . '/includes/header.php';
$governanceDocs = array_filter($associationDocuments, fn($doc) => in_array($doc['type'], ['Gouvernance', 'Engagement'], true));
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">L’association</p>
        <h1>Statuts, règlement intérieur et charte.</h1>
        <p class="lead">Un espace dédié aux règles de fonctionnement, engagements et documents officiels de l’association.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="documents.php">Tous les documents</a>
            <a class="btn btn-secondary" href="commissions.php">Voir les commissions</a>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-heading">
        <p class="eyebrow">Documents officiels</p>
        <h2>Consulter les documents de référence.</h2>
        <p>Retrouve les statuts, le règlement intérieur et la charte des valeurs de l’AS amU.</p>
    </div>

    <div class="document-grid">
        <?php foreach ($governanceDocs as $doc): ?>
            <article class="card document-card">
                <span class="badge badge-yellow"><?= e($doc['type']) ?></span>
                <h3><?= e($doc['label']) ?></h3>
                <p><?= e($doc['description']) ?></p>
                <a class="text-link" href="<?= e($doc['url']) ?>" target="_blank" rel="noopener">Consulter le document →</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section section-muted">
    <div class="container cards three">
        <article class="card">
            <h2>Statuts</h2>
            <p>Ils posent le cadre légal : objet de l’association, instances, adhésion, assemblées et responsabilités.</p>
        </article>
        <article class="card">
            <h2>Règlement intérieur</h2>
            <p>Il précise les règles concrètes : fonctionnement interne, sections, dépenses, justificatifs et comportements attendus.</p>
        </article>
        <article class="card">
            <h2>Charte</h2>
            <p>Elle rappelle les engagements : respect, inclusion, esprit sportif, représentation d’AMU et lutte contre les discriminations.</p>
        </article>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
