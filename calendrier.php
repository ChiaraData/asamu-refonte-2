<?php
$pageTitle = 'Calendrier des compétitions';
$pageDescription = 'Calendrier des compétitions universitaires AS amU : dates, lieux, niveaux et statuts.';
require_once __DIR__ . '/includes/header.php';

$calendarAssetPrefix = '';
$frenchMonths = [
    1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin',
    7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre',
];
$seasonStartYear = (int) substr((string) $site['season'], 0, 4);
$calendarMonths = [];

for ($offset = 0; $offset < 12; $offset++) {
    $monthNumber = (($offset + 7) % 12) + 1;
    $year = $seasonStartYear + ($monthNumber < 8 ? 1 : 0);
    $calendarMonths[] = [
        'value' => sprintf('%04d-%02d', $year, $monthNumber),
        'label' => ucfirst($frenchMonths[$monthNumber]) . ' ' . $year,
    ];
}

$calendarEvents = array_values(array_filter($competitionCalendar, static function ($event): bool {
    return is_array($event)
        && !empty($event['start_date'])
        && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $event['start_date']) === 1;
}));

usort($calendarEvents, static function (array $first, array $second): int {
    return strcmp((string) $first['start_date'], (string) $second['start_date']);
});

$calendarOptions = ['sports' => [], 'levels' => [], 'statuses' => []];
foreach ($calendarEvents as $event) {
    foreach (['sport' => 'sports', 'level' => 'levels', 'status' => 'statuses'] as $key => $group) {
        $value = trim((string) ($event[$key] ?? ''));
        if ($value !== '' && !in_array($value, $calendarOptions[$group], true)) {
            $calendarOptions[$group][] = $value;
        }
    }
}

$calendarEventsJson = json_encode(
    $calendarEvents,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?: '[]';

$formatFrenchDate = static function (string $date) use ($frenchMonths): string {
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return $date;
    }

    return (string) ((int) date('j', $timestamp)) . ' ' . $frenchMonths[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
};
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Calendrier</p>
        <h1>Calendrier des compétitions.</h1>
        <p class="lead">Un espace unique pour voir les compétitions à venir, les lieux, les niveaux et les informations de départ.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="competitions.php">Préparer mon départ</a>
            <a class="btn btn-secondary" href="mailto:<?= e($site['competition_email']) ?>">Signaler une compétition</a>
        </div>
    </div>
</section>

<section class="section container calendar-section">
    <div class="section-heading inline-heading">
        <div>
            <p class="eyebrow">Saison <?= e($site['season']) ?></p>
            <h2>Le calendrier en un coup d’œil</h2>
            <p>Filtre par sport, niveau ou statut, puis sélectionne une date pour retrouver les informations utiles.</p>
        </div>
        <a class="text-link" href="competitions.php#documents">Documents compétition →</a>
    </div>

    <div class="competition-calendar-shell" data-competition-calendar data-season-start="<?= e($calendarMonths[0]['value']) ?>" data-season-end="<?= e($calendarMonths[count($calendarMonths) - 1]['value']) ?>">
        <div class="calendar-toolbar">
            <div>
                <span class="calendar-toolbar-kicker">Planning AS amU</span>
                <strong data-calendar-month-title><?= e($calendarMonths[0]['label']) ?></strong>
            </div>
            <div class="calendar-navigation" aria-label="Naviguer dans le calendrier">
                <button class="calendar-nav-button" type="button" data-calendar-previous aria-label="Mois précédent">←</button>
                <label class="sr-only" for="calendar-month-select">Choisir un mois</label>
                <select id="calendar-month-select" data-calendar-month-select>
                    <?php foreach ($calendarMonths as $month): ?>
                        <option value="<?= e($month['value']) ?>"><?= e($month['label']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="calendar-nav-button" type="button" data-calendar-next aria-label="Mois suivant">→</button>
                <button class="calendar-today-button" type="button" data-calendar-today>Aujourd’hui</button>
            </div>
        </div>

        <div class="calendar-filter-bar" aria-label="Filtres du calendrier">
            <div class="calendar-filter-title">
                <span aria-hidden="true">⌘</span>
                <strong>Affiner</strong>
            </div>
            <label>
                <span>Sport</span>
                <select data-calendar-sport<?= $calendarOptions['sports'] ? '' : ' disabled' ?>>
                    <option value="all">Tous les sports</option>
                    <?php foreach ($calendarOptions['sports'] as $sport): ?><option value="<?= e($sport) ?>"><?= e($sport) ?></option><?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Niveau</span>
                <select data-calendar-level<?= $calendarOptions['levels'] ? '' : ' disabled' ?>>
                    <option value="all">Tous les niveaux</option>
                    <?php foreach ($calendarOptions['levels'] as $level): ?><option value="<?= e($level) ?>"><?= e($level) ?></option><?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Statut</span>
                <select data-calendar-status<?= $calendarOptions['statuses'] ? '' : ' disabled' ?>>
                    <option value="all">Tous les statuts</option>
                    <?php foreach ($calendarOptions['statuses'] as $status): ?><option value="<?= e($status) ?>"><?= e($status) ?></option><?php endforeach; ?>
                </select>
            </label>
            <button class="calendar-reset-button" type="button" data-calendar-reset>Réinitialiser</button>
        </div>

        <div class="competition-calendar-layout">
            <div class="calendar-month-panel">
                <div class="calendar-weekdays" aria-hidden="true">
                    <span>Lun</span><span>Mar</span><span>Mer</span><span>Jeu</span><span>Ven</span><span>Sam</span><span>Dim</span>
                </div>
                <div class="calendar-grid" data-calendar-grid aria-live="polite"></div>
            </div>
        </div>

        <p class="calendar-result-count" data-calendar-result-count role="status"></p>
        <script type="application/json" data-calendar-events><?= $calendarEventsJson ?></script>
    </div>

    <noscript>
        <p class="calendar-no-script">Active JavaScript pour afficher le calendrier interactif. Les rendez-vous restent listés ci-dessous.</p>
    </noscript>

    <div class="calendar-event-directory" id="calendar-events">
        <div class="calendar-directory-heading">
            <div>
                <p class="eyebrow">Tous les rendez-vous</p>
                <h2>Les détails des compétitions</h2>
            </div>
            <span><?= count($calendarEvents) ?> événement<?= count($calendarEvents) > 1 ? 's' : '' ?> publié<?= count($calendarEvents) > 1 ? 's' : '' ?></span>
        </div>

        <?php if ($calendarEvents): ?>
            <div class="calendar-event-list" data-calendar-event-list>
                <?php foreach ($calendarEvents as $event): ?>
                    <?php
                    $endDate = !empty($event['end_date']) ? (string) $event['end_date'] : (string) $event['start_date'];
                    $dateLabel = $formatFrenchDate((string) $event['start_date']);
                    $eventImage = trim((string) ($event['image'] ?? ''));
                    $eventImageUrl = str_starts_with($eventImage, 'http') ? $eventImage : $calendarAssetPrefix . ltrim($eventImage, '/');
                    if ($endDate !== $event['start_date']) {
                        $dateLabel .= ' au ' . $formatFrenchDate($endDate);
                    }
                    ?>
                    <article
                        class="calendar-event-card<?= $eventImage !== '' ? ' has-poster' : '' ?>"
                        id="event-<?= e((string) ($event['id'] ?? md5((string) $event['title']))) ?>"
                        data-calendar-event
                        data-calendar-start="<?= e((string) $event['start_date']) ?>"
                        data-calendar-end="<?= e($endDate) ?>"
                        data-calendar-sport-value="<?= e((string) ($event['sport'] ?? '')) ?>"
                        data-calendar-level-value="<?= e((string) ($event['level'] ?? '')) ?>"
                        data-calendar-status-value="<?= e((string) ($event['status'] ?? '')) ?>"
                    >
                        <div class="calendar-event-date">
                            <span><?= e($formatFrenchDate((string) $event['start_date'])) ?></span>
                            <?php if ($endDate !== $event['start_date']): ?><small>jusqu’au <?= e($formatFrenchDate($endDate)) ?></small><?php endif; ?>
                        </div>
                        <?php if ($eventImage !== ''): ?>
                            <div class="calendar-event-poster">
                                <img src="<?= e($eventImageUrl) ?>" alt="<?= e((string) ($event['image_alt'] ?? $event['title'] ?? 'Affiche de la compétition')) ?>" loading="lazy" decoding="async">
                            </div>
                        <?php endif; ?>
                        <div class="calendar-event-content">
                            <div class="calendar-tags">
                                <?php if (!empty($event['sport'])): ?><span><?= e((string) $event['sport']) ?></span><?php endif; ?>
                                <?php if (!empty($event['level'])): ?><span><?= e((string) $event['level']) ?></span><?php endif; ?>
                                <?php if (!empty($event['status'])): ?><span><?= e((string) $event['status']) ?></span><?php endif; ?>
                            </div>
                            <h3><?= e((string) ($event['title'] ?? 'Compétition AS amU')) ?></h3>
                            <?php if (!empty($event['description'])): ?><p><?= e((string) $event['description']) ?></p><?php endif; ?>
                            <dl class="event-details">
                                <div><dt>Lieu</dt><dd><?= e((string) ($event['place'] ?? 'À confirmer')) ?></dd></div>
                                <div><dt>Public</dt><dd><?= e((string) ($event['section'] ?? 'AS amU')) ?></dd></div>
                                <?php if (!empty($event['registration_deadline'])): ?><div><dt>Inscription avant</dt><dd><?= e($formatFrenchDate((string) $event['registration_deadline'])) ?></dd></div><?php endif; ?>
                            </dl>
                            <?php if (!empty($event['url'])): ?><a class="text-link" href="<?= e((string) $event['url']) ?>" target="_blank" rel="noopener">Informations de la compétition →</a><?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="calendar-filter-empty" data-calendar-filter-empty hidden>Aucune compétition ne correspond à ces filtres.</p>
        <?php else: ?>
            <div class="calendar-empty-directory">
                <span aria-hidden="true">+</span>
                <div>
                    <h3>Les prochains rendez-vous arrivent.</h3>
                    <p>Le calendrier se complète au fil des confirmations. Signale une date à la commission sportive pour la publier ici.</p>
                </div>
                <a class="btn btn-secondary" href="mailto:<?= e($site['competition_email']) ?>">Contacter la commission</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section-muted">
    <div class="container split">
        <div>
            <p class="eyebrow">Avant de participer</p>
            <h2>Prépare ton déplacement.</h2>
            <p>Avant tout engagement, vérifie les conditions avec ton ou ta coach et la commission sportive. Aucun frais ne doit être avancé sans validation.</p>
        </div>
        <div class="highlight-panel">
            <h3>À vérifier</h3>
            <ul class="check-list">
                <li>Ton inscription et ta licence FFSU.</li>
                <li>Le niveau, la date et le lieu.</li>
                <li>Le ou la coach référent·e.</li>
                <li>Le transport et l’hébergement si besoin.</li>
                <li>Les documents nécessaires au remboursement.</li>
            </ul>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
