<?php
$pageTitle = 'Sections';
$pageDescription = 'Liste des 12 sections AS amU avec pages dédiées, bureaux, adresses et permanences.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Sections AS amU</p>
        <h1>Trouve ta section sportive AS amU</h1>
        <p class="lead">Repère la section qui correspond à ton campus, contacte les responsables et prépare ta participation aux compétitions universitaires.</p>
    </div>
</section>

<section class="section container">
    <div class="section-heading inline-heading">
        <div>
            <h2>Les 12 sections</h2>
            <p>Filtre rapidement entre Aix et Marseille, puis ouvre la fiche de ta section.</p>
        </div>
        <a class="btn btn-primary" href="<?= e($site['helloasso_url']) ?>" target="_blank" rel="noopener">Adhérer via HelloAsso</a>
    </div>

    <div class="filters" aria-label="Filtrer les sections">
        <button type="button" class="filter-btn active" data-filter="all" aria-controls="section-list" aria-pressed="true">Toutes</button>
        <button type="button" class="filter-btn" data-filter="Aix" aria-controls="section-list" aria-pressed="false">Aix</button>
        <button type="button" class="filter-btn" data-filter="Marseille" aria-controls="section-list" aria-pressed="false">Marseille</button>
    </div>

    <div class="cards section-cards" id="section-list">
        <?php foreach ($sections as $section): ?>
            <article class="card section-card" data-campus="<?= e($section['campus']) ?>">
                <span class="badge"><?= e($section['city']) ?></span>
                <h3><?= e($section['name']) ?></h3>
                <p><?= e($section['component']) ?></p>
                <ul class="card-meta">
                    <li><strong>Adresse :</strong> <?= e($section['address']) ?></li>
                    <li><strong>Permanences :</strong> <?= e($section['office_hours']) ?></li>
                    <li><strong>Adhérent·es :</strong> <?= e($section['adherents_count'] ?? 'Non communiqué') ?></li>
                    <li><strong>Licencié·es :</strong> <?= e($section['licensees_count'] ?? 'Non communiqué') ?></li>
                </ul>
                <div class="card-actions">
                    <a href="<?= e(section_url($section)) ?>">Voir la fiche section</a>
                    <a href="mailto:<?= e($section['email']) ?>">Contacter</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <p class="filter-empty" id="section-filter-empty" role="status" hidden>Aucune section ne correspond à ce filtre.</p>
</section>

<section class="section section-muted" id="sports">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Disciplines</p>
            <h2>Les familles de sports représentées</h2>
        </div>
        <div class="sport-grid">
            <?php foreach ($sports as $group => $items): ?>
                <article class="sport-block">
                    <h3><?= e($group) ?></h3>
                    <ul>
                        <?php foreach ($items as $sport): ?>
                            <li><?= e($sport) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
