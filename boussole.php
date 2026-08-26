<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/boussole-data.php';

$pageTitle = 'La boussole du sport à AMU';
$pageDescription = "Dis-nous ton besoin, on t'oriente vers le bon interlocuteur.";
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact boussole-hero">
    <div class="container">
        <h1>La boussole du sport à AMU</h1>
        <p class="lead">Dis-nous ton besoin, on t'oriente vers le bon interlocuteur.</p>
    </div>
</section>

<section class="section container boussole-section">
    <div class="boussole-intro">
        <span class="boussole-compass" aria-hidden="true">⌖</span>
        <div>
            <h2>Quel est ton besoin ?</h2>
        </div>
    </div>

    <div class="boussole-list">
        <?php foreach ($sportCompass as $category): ?>
            <?php $panelId = 'boussole-' . $category['id']; ?>
            <article class="boussole-category theme-<?= e($category['theme']) ?>">
                <button class="boussole-toggle" type="button" data-compass-toggle aria-expanded="false" aria-controls="<?= e($panelId) ?>">
                    <span class="boussole-emoji" aria-hidden="true"><?= e($category['emoji']) ?></span>
                    <span>
                        <strong><?= e($category['title']) ?></strong>
                        <small><?= e($category['subtitle']) ?></small>
                    </span>
                    <span class="boussole-toggle-label">Voir les options</span>
                    <span class="boussole-chevron" aria-hidden="true">⌄</span>
                </button>

                <div class="boussole-options" id="<?= e($panelId) ?>" hidden>
                    <p class="boussole-options-intro">Choisis ce qui te ressemble le plus.</p>
                    <div class="boussole-option-grid">
                        <?php foreach ($category['options'] as $option): ?>
                            <?php $actor = $sportCompassActors[$option['actor']] ?? ['name' => (string) ($option['actor'] ?? 'AS amU'), 'description' => '']; ?>
                            <article class="boussole-option-card">
                                <span class="boussole-actor actor-<?= e(strtolower($option['actor'])) ?>"><?= e($actor['name']) ?></span>
                                <p class="boussole-option-label">« <?= e($option['label']) ?> »</p>
                                <h3><?= e($option['title']) ?></h3>
                                <p><?= e($option['description']) ?></p>
                                <?php if (!empty($option['info'])): ?>
                                    <p class="boussole-option-info"><?= e($option['info']) ?></p>
                                <?php endif; ?>
                                <?php $isExternal = str_starts_with($option['url'], 'http'); ?>
                                <a class="text-link" href="<?= e($option['url']) ?>"<?= $isExternal ? ' target="_blank" rel="noopener"' : '' ?>>
                                    Y aller
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <details class="boussole-actors">
        <summary>Qui sont les 4 acteurs du sport à AMU ?</summary>
        <ul>
            <?php foreach ($sportCompassActors as $actor): ?>
                <li><strong><?= e($actor['name']) ?></strong> — <?= e($actor['description']) ?></li>
            <?php endforeach; ?>
        </ul>
    </details>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
