<?php
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Palmarès';
$pageDescription = 'Les résultats, podiums et moments forts des équipes AS amU.';

$palmaresForDisplay = array_values(array_filter(
    $palmares,
    static fn (array $entry): bool => in_array((int) ($entry['place'] ?? 0), [1, 2, 3], true)
));

usort($palmaresForDisplay, static function (array $left, array $right): int {
    $placeComparison = ((int) ($left['place'] ?? 0)) <=> ((int) ($right['place'] ?? 0));
    if ($placeComparison !== 0) {
        return $placeComparison;
    }

    $sportComparison = strcasecmp((string) ($left['sport'] ?? ''), (string) ($right['sport'] ?? ''));
    if ($sportComparison !== 0) {
        return $sportComparison;
    }

    return strcasecmp(palmares_person_name($left), palmares_person_name($right));
});

$palmaresBySeason = [];
$sports = [];
$teamCount = 0;

foreach ($palmaresForDisplay as $entry) {
    $season = trim((string) ($entry['season'] ?? '')) ?: '2025/2026';
    $sport = trim((string) ($entry['sport'] ?? '')) ?: 'Autre discipline';
    $palmaresBySeason[$season][$sport][] = $entry;
    $sports[$sport] = true;
    $teamCount += !empty($entry['team']) ? 1 : 0;
}

$seasons = array_keys($palmaresBySeason);
usort($seasons, static fn (string $left, string $right): int => strnatcasecmp($right, $left));

$palmaresBySeasonSorted = [];
foreach ($seasons as $season) {
    $sportsForSeason = $palmaresBySeason[$season];
    uksort($sportsForSeason, 'strnatcasecmp');
    $palmaresBySeasonSorted[$season] = $sportsForSeason;
}

$palmaresInitialSportCount = 3;
$disciplineCount = count(array_filter(array_keys($sports)));

function palmares_medal_class(int $place): string
{
    return match ($place) {
        1 => 'gold',
        2 => 'silver',
        3 => 'bronze',
        default => 'finish',
    };
}

function palmares_place_label(int $place): string
{
    return $place > 0 ? $place . ($place === 1 ? 'er' : 'e') : 'Résultat';
}

function palmares_medal_label(int $place): string
{
    return match ($place) {
        1 => 'Or',
        2 => 'Argent',
        3 => 'Bronze',
        default => 'Podium',
    };
}

function palmares_person_name(array $entry): string
{
    if (!empty($entry['team'])) {
        $teamName = trim((string) ($entry['team_name'] ?? ''));
        return $teamName !== '' ? $teamName : 'Équipe AS amU';
    }

    $firstName = trim((string) ($entry['first_name'] ?? ''));
    $lastName = trim((string) ($entry['last_name'] ?? ''));
    $name = trim($lastName . ' ' . $firstName);

    return $name !== '' ? $name : 'Athlète AS amU';
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact palmares-hero">
    <div class="container">
        <p class="eyebrow">Résultats AS amU</p>
        <h1>Le palmarès.</h1>
        <p class="lead">Les podiums de l’AS amU, organisés par saison et par sport.</p>
    </div>
</section>

<?php if ($palmaresForDisplay): ?>
    <section class="section palmares-dashboard-section">
        <div class="container">
            <div class="palmares-dashboard">
                <div class="palmares-dashboard-copy">
                    <p class="eyebrow">Bilan national</p>
                    <h2>Les réussites de chaque sport.</h2>
                </div>
                <dl class="palmares-figures" aria-label="Chiffres du palmarès">
                    <div><dt><?= count($palmaresForDisplay) ?></dt><dd>podiums <span>statistiques depuis 2023</span></dd></div>
                    <div><dt><?= $disciplineCount ?></dt><dd>disciplines</dd></div>
                    <div><dt><?= $teamCount ?></dt><dd>équipes</dd></div>
                </dl>
            </div>

            <?php if (count($palmaresBySeasonSorted) > 1): ?>
                <nav class="palmares-season-nav" aria-label="Choisir une saison">
                    <?php foreach (array_keys($palmaresBySeasonSorted) as $season): ?>
                        <a href="#<?= e('saison-' . strtolower(str_replace(['/', ' '], ['-', '-'], $season))) ?>"><?= e($season) ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </div>
    </section>

    <?php foreach ($palmaresBySeasonSorted as $season => $sportsForSeason): ?>
        <?php $seasonSlug = strtolower(str_replace(['/', ' '], ['-', '-'], $season)); ?>
        <section class="section palmares-season" id="<?= e('saison-' . $seasonSlug) ?>">
            <div class="container">
                <div class="palmares-season-heading">
                    <div>
                        <p class="eyebrow">Saison</p>
                        <h2><?= e($season) ?></h2>
                    </div>
                    <p><?= count($sportsForSeason) ?> sport<?= count($sportsForSeason) > 1 ? 's' : '' ?> · <?= array_sum(array_map('count', $sportsForSeason)) ?> podium<?= array_sum(array_map('count', $sportsForSeason)) > 1 ? 's' : '' ?></p>
                </div>

                <div id="<?= e('palmares-sports-' . $seasonSlug) ?>" class="palmares-sports-grid" data-palmares-sports-list>
                    <?php foreach ($sportsForSeason as $sport => $entries): ?>
                        <article class="palmares-sport-card" data-palmares-sport-card>
                            <header>
                                <div>
                                    <p>Discipline</p>
                                    <h3><?= e($sport) ?></h3>
                                </div>
                                <strong><?= count($entries) ?></strong>
                            </header>
                            <ul class="palmares-sport-results">
                                <?php foreach ($entries as $entry): ?>
                                    <?php $place = (int) $entry['place']; ?>
                                    <li class="medal-<?= e(palmares_medal_class($place)) ?>">
                                        <span class="palmares-medal-chip"><?= e(palmares_medal_label($place)) ?></span>
                                        <div>
                                            <h4><?= e(palmares_person_name($entry)) ?></h4>
                                            <p>
                                                <?php if (!empty($entry['competition'])): ?><span><?= e((string) $entry['competition']) ?></span><?php endif; ?>
                                                <?= e((string) ($entry['result'] ?? 'Médaille nationale')) ?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endforeach; ?>
                </div>
                <?php if (count($sportsForSeason) > $palmaresInitialSportCount): ?>
                    <div class="palmares-load-more">
                        <button class="btn btn-primary" type="button" data-palmares-load-more data-palmares-target="<?= e('palmares-sports-' . $seasonSlug) ?>" data-palmares-step="3" hidden>Voir plus de disciplines</button>
                        <p class="sr-only" data-palmares-status aria-live="polite"></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
<?php else: ?>
    <section class="section container palmares-empty-section">
        <div class="palmares-empty">
            <div class="palmares-empty-podium" aria-hidden="true">
                <span>2</span><strong>1</strong><span>3</span>
            </div>
            <div>
                <p class="eyebrow">Prêt à remplir</p>
                <h2>Le prochain podium est à raconter.</h2>
                <p>Les prochains résultats viendront bientôt enrichir ce palmarès.</p>
                <ul class="check-list">
                    <li>Le sport, puis le nom et le prénom de l’athlète.</li>
                    <li>Le résultat obtenu.</li>
                    <li>Le rang de 1 à 3, pour indiquer une médaille d’or, d’argent ou de bronze.</li>
                </ul>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
