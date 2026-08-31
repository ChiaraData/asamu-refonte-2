<?php
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Carte des campus et sections';
$pageDescription = 'Repérez les campus d’Aix-en-Provence et de Marseille, puis trouvez la section AS amU qui vous correspond.';

$campusGroups = [];
foreach ($sections as $section) {
    $campus = (string) ($section['campus'] ?? 'Autre');
    $campusGroups[$campus][] = $section;
}

$campusLabels = [
    'Aix' => 'Aix-en-Provence',
    'Marseille' => 'Marseille',
];

$firstSection = $sections[0] ?? null;
$firstAddress = (string) ($firstSection['address'] ?? $site['address']);
$firstMapQuery = trim((string) ($firstSection['map_query'] ?? '')) ?: $firstAddress;
$firstEmbedUrl = 'https://www.google.com/maps?output=embed&q=' . rawurlencode($firstMapQuery);
$firstRouteUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($firstMapQuery);

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact campus-hero">
    <div class="container">
        <p class="eyebrow">Se repérer</p>
        <h1>Carte des campus et sections.</h1>
        <p class="lead">Choisis ton campus, retrouve ta section et accède à ses informations pratiques en un coup d’œil.</p>
    </div>
</section>

<section class="section container campus-map-section">
    <div class="section-heading inline-heading">
        <div>
            <p class="eyebrow">Repères GPS</p>
            <h2>Trouve ton lieu de pratique.</h2>
        </div>
        <a class="text-link" href="sections-appartenance">Je ne connais pas ma section →</a>
    </div>

    <div class="campus-map-layout" data-campus-map>
        <aside class="campus-map-directory" aria-label="Choisir un lieu">
            <p class="campus-map-directory-title"><span aria-hidden="true">⌖</span> <?= count($sections) ?> lieux à retrouver</p>
            <div class="campus-map-filters" aria-label="Filtrer les lieux par campus">
                <button class="campus-map-filter is-active" type="button" data-campus-map-filter="all" aria-pressed="true">Tous</button>
                <?php foreach ($campusLabels as $campusKey => $campusLabel): ?>
                    <button class="campus-map-filter" type="button" data-campus-map-filter="<?= e($campusKey) ?>" aria-pressed="false"><?= e($campusLabel) ?></button>
                <?php endforeach; ?>
            </div>
            <div class="campus-map-list">
                <?php foreach ($sections as $index => $section): ?>
                    <?php
                        $address = (string) $section['address'];
                        $mapQuery = trim((string) ($section['map_query'] ?? '')) ?: $address;
                        $embedUrl = 'https://www.google.com/maps?output=embed&q=' . rawurlencode($mapQuery);
                        $routeUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($mapQuery);
                    ?>
                    <button
                        class="campus-map-place<?= $index === 0 ? ' is-active' : '' ?>"
                        type="button"
                        data-campus-map-place
                        data-campus="<?= e($section['campus']) ?>"
                        data-map-embed="<?= e($embedUrl) ?>"
                        data-map-route="<?= e($routeUrl) ?>"
                        data-map-title="<?= e($section['name']) ?>"
                        data-map-campus="<?= e($section['city']) ?>"
                        aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>"
                    >
                        <strong><?= e($section['name']) ?></strong>
                        <span><?= e($section['component']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </aside>

        <div class="campus-gps-stage">
            <div class="campus-gps-toolbar">
                <div>
                    <p class="eyebrow" data-campus-map-campus><?= e($firstSection['city'] ?? '') ?></p>
                    <h3 data-campus-map-title><?= e($firstSection['name'] ?? '') ?></h3>
                </div>
                <a class="btn btn-primary campus-map-route-link" href="<?= e($firstRouteUrl) ?>" target="_blank" rel="noopener" data-campus-map-route>Itinéraire GPS <span aria-hidden="true">↗</span></a>
            </div>
            <iframe
                class="campus-gps-map"
                title="Carte GPS de <?= e($firstSection['name'] ?? 'la section sélectionnée') ?>"
                data-src="<?= e($firstEmbedUrl) ?>"
                loading="lazy"
                referrerpolicy="no-referrer"
                data-campus-map-frame
                hidden
            ></iframe>
            <div class="campus-map-consent" data-campus-map-consent>
                <div>
                    <span class="campus-map-consent-icon" aria-hidden="true">⌖</span>
                    <h3>Afficher la carte Google Maps ?</h3>
                    <p>La carte est bloquée par défaut pour protéger ta vie privée. En l’affichant, ton navigateur se connectera à Google.</p>
                    <button class="btn btn-primary" type="button" data-campus-map-load>Afficher la carte Google Maps</button>
                    <a href="confidentialite">En savoir plus sur la confidentialité</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php foreach ($campusLabels as $campusKey => $campusLabel): ?>
    <?php $campusSections = $campusGroups[$campusKey] ?? []; ?>
    <section class="section<?= $campusKey === 'Marseille' ? ' section-muted' : '' ?>" id="campus-<?= e(strtolower($campusKey)) ?>">
        <div class="container">
            <div class="section-heading inline-heading">
                <div>
                    <p class="eyebrow">Campus</p>
                    <h2><?= e($campusLabel) ?></h2>
                    <p><?= count($campusSections) ?> section<?= count($campusSections) > 1 ? 's' : '' ?> AS amU à découvrir.</p>
                </div>
                <a class="text-link" href="sections#section-list">Voir toutes les sections →</a>
            </div>

            <div class="campus-section-grid">
                <?php foreach ($campusSections as $section): ?>
                    <?php
                        $mapQuery = trim((string) ($section['map_query'] ?? '')) ?: (string) $section['address'];
                        $mapUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($mapQuery);
                    ?>
                    <article class="card campus-section-card">
                        <span class="badge"><?= e($section['name']) ?></span>
                        <h3><?= e($section['component']) ?></h3>
                        <p class="campus-section-address"><strong>Adresse :</strong> <?= e($section['address']) ?></p>
                        <p class="campus-section-hours"><strong>Permanences :</strong> <?= e($section['office_hours']) ?></p>
                        <div class="card-actions">
                            <button
                                class="text-link campus-map-focus"
                                type="button"
                                data-campus-map-focus
                                data-map-embed="<?= e('https://www.google.com/maps?output=embed&q=' . rawurlencode($mapQuery)) ?>"
                                data-map-route="<?= e($mapUrl) ?>"
                                data-map-title="<?= e($section['name']) ?>"
                                data-map-campus="<?= e($section['city']) ?>"
                            >Voir sur la carte</button>
                            <a class="text-link" href="<?= e(section_url($section)) ?>">Fiche section →</a>
                            <a class="text-link" href="<?= e($mapUrl) ?>" target="_blank" rel="noopener">Itinéraire GPS ↗</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
