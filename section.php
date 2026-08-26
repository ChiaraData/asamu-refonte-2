<?php
require_once __DIR__ . '/includes/config.php';
$slug = trim($_GET['slug'] ?? '');
$section = find_section_by_slug($sections, $slug);

if (!$section) {
    http_response_code(404);
    $pageTitle = 'Section introuvable';
    $pageDescription = 'La section demandée est introuvable.';
    require_once __DIR__ . '/includes/header.php';
    ?>
    <section class="page-hero compact">
        <div class="container">
            <p class="eyebrow">Erreur 404</p>
            <h1>Section introuvable.</h1>
            <p class="lead">La fiche demandée n’existe pas ou le lien est incorrect.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="sections.php">Retour aux sections</a>
            </div>
        </div>
    </section>
    <?php require_once __DIR__ . '/includes/footer.php'; exit;
}

$pageTitle = $section['name'];
$pageDescription = 'Fiche section ' . $section['name'] . ' : adresse, bureau, permanences, adhérents, licenciés, compétitions et événements.';
$pageCanonical = 'section.php?slug=' . rawurlencode($slug);
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero section-hero compact">
    <div class="container">
        <p class="eyebrow">Fiche section</p>
        <h1><?= e($section['name']) ?></h1>
        <p class="lead"><?= e($section['component']) ?> · <?= e($section['city']) ?></p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= e($site['helloasso_url']) ?>" target="_blank" rel="noopener">Adhérer via HelloAsso</a>
            <a class="btn btn-secondary" href="mailto:<?= e($section['email']) ?>">Contacter la section</a>
        </div>
    </div>
</section>

<section class="section container section-stats-grid" aria-label="Chiffres de la section">
    <div class="section-stats-heading">
        <p class="eyebrow">Chiffres 2025-2026</p>
        <h2>La section en chiffres</h2>
    </div>
    <article class="section-stat-card color-yellow">
        <span>Adhérent·es</span>
        <strong><?= e($section['adherents_count'] ?? 'Non communiqué') ?></strong>
    </article>
    <article class="section-stat-card color-blue">
        <span>Licencié·es</span>
        <strong><?= e($section['licensees_count'] ?? 'Non communiqué') ?></strong>
    </article>
    <article class="section-stat-card color-green">
        <span>Campus</span>
        <strong><?= e($section['campus']) ?></strong>
    </article>
    <?php foreach (($section['activity_stats'] ?? []) as $index => $stat): ?>
        <?php $statColor = $index % 2 === 0 ? 'color-blue' : 'color-yellow'; ?>
        <article class="section-stat-card <?= $statColor ?>">
            <span><?= e($stat['label']) ?></span>
            <strong><?= e($stat['number']) ?></strong>
        </article>
    <?php endforeach; ?>
</section>

<section class="section container section-detail-grid">
    <article class="card detail-card">
        <span class="badge badge-blue">Adresse</span>
        <h2>Où trouver la section ?</h2>
        <p><?= e($section['address']) ?></p>
    </article>

    <article class="card detail-card">
        <span class="badge badge-green">Permanences</span>
        <h2>Quand passer ?</h2>
        <p><?= e($section['office_hours']) ?></p>
    </article>

    <article class="card detail-card">
        <span class="badge badge-purple">Contact</span>
        <h2>Écrire à la section</h2>
        <p><a href="mailto:<?= e($section['email']) ?>"><?= e($section['email']) ?></a></p>
    </article>
</section>

<section class="section section-muted">
    <div class="container split">
        <div>
            <p class="eyebrow">Bureau</p>
            <h2>Les référent·es de la section</h2>
        </div>
        <div class="bureau-list">
            <?php foreach ($section['bureau'] as $person): ?>
                <article>
                    <span><?= e($person['role']) ?></span>
                    <strong><?= e($person['name']) ?></strong>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section container section-content-flow">
    <?php foreach (($section['content_blocks'] ?? []) as $block): ?>
        <article class="section-editorial-card">
            <?php if (!empty($block['kicker'])): ?>
                <p class="eyebrow"><?= e($block['kicker']) ?></p>
            <?php endif; ?>
            <h2><?= e($block['title']) ?></h2>
            <?php foreach (($block['paragraphs'] ?? []) as $paragraph): ?>
                <?= rich_text_render((string) $paragraph) ?>
            <?php endforeach; ?>
        </article>
    <?php endforeach; ?>
</section>

<section class="section section-muted">
    <div class="container">
        <div class="section-heading inline-heading">
            <div>
                <p class="eyebrow">Événements de section</p>
                <h2>Les rendez-vous de la section</h2>
                <p>Retrouve les temps forts annoncés par la section. Contacte-la pour confirmer les dates et les inscriptions.</p>
            </div>
            <a class="text-link" href="calendrier.php">Voir le calendrier général →</a>
        </div>

        <div class="section-event-grid">
            <?php foreach (($section['events'] ?? []) as $event): ?>
                <article class="card section-event-card">
                    <span class="badge badge-yellow"><?= e($event['date']) ?></span>
                    <h3><?= e($event['title']) ?></h3>
                    <p><?= e($event['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section container">
    <div class="notice section-note">
        <strong>Informations complémentaires :</strong> <?= e($section['notes']) ?>
    </div>
    <div class="section-nav-actions">
        <a class="btn btn-secondary" href="sections.php">Toutes les sections</a>
        <a class="btn btn-primary" href="adhesion.php">Comprendre l’adhésion</a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
