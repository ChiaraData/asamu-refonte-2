<?php
require_once __DIR__ . '/config.php';

$pageTitle = $pageTitle ?? $site['name'];
$pageDescription = $pageDescription ?? $site['tagline'];
$canonicalPath = $pageCanonical ?? current_page();
$pageUrl = rtrim($site['site_url'], '/') . '/' . ltrim($canonicalPath, '/');
$pageImage = $pageImage ?? $site['social_image'];
$documentTitle = $pageTitle . ' · ' . $site['name'];
$stylesheetVersion = (string) filemtime(__DIR__ . '/../assets/css/style.css');
$v2StylesheetVersion = (string) filemtime(__DIR__ . '/../assets/css/style-v2.css');

$v2MenuGroups = [
    'Découvrir' => [
        ['label' => 'Accueil', 'url' => './'],
        ['label' => 'Actualités', 'url' => 'actualites'],
        ['label' => 'La boussole du sport', 'url' => 'boussole'],
        ['label' => 'Photothèque', 'url' => 'phototheque'],
    ],
    'Pratiquer' => [
        ['label' => 'Toutes les sections', 'url' => 'sections'],
        ['label' => 'Identifier ma section', 'url' => 'sections-appartenance'],
        ['label' => 'Carte des campus', 'url' => 'campus'],
    ],
    'Participer' => [
        ['label' => 'Adhésion', 'url' => 'adhesion'],
        ['label' => 'Compétitions', 'url' => 'competitions'],
        ['label' => 'Calendrier', 'url' => 'calendrier'],
        ['label' => 'Palmarès', 'url' => 'palmares'],
        ['label' => 'Coachs', 'url' => 'coachs'],
    ],
    'L’association' => [
        ['label' => 'Organisation', 'url' => 'association'],
        ['label' => 'Commissions', 'url' => 'commissions'],
        ['label' => 'Documents', 'url' => 'documents'],
        ['label' => 'Statuts et charte', 'url' => 'statuts'],
        ['label' => 'Contact', 'url' => 'contact'],
    ],
];
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e($pageUrl) ?>">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e($site['full_name']) ?>">
    <meta property="og:title" content="<?= e($documentTitle) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:url" content="<?= e($pageUrl) ?>">
    <meta property="og:image" content="<?= e($pageImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($documentTitle) ?>">
    <meta name="twitter:description" content="<?= e($pageDescription) ?>">
    <meta name="twitter:image" content="<?= e($pageImage) ?>">
    <title><?= e($documentTitle) ?></title>
    <link rel="icon" href="assets/img/logo-asamu-v2.png" type="image/png">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= e($stylesheetVersion) ?>">
    <link rel="stylesheet" href="assets/css/style-v2.css?v=<?= e($v2StylesheetVersion) ?>">
</head>

<body class="v2-site">
<a class="skip-link" href="#contenu">Aller au contenu</a>

<header class="v2-header">
    <div class="container v2-navbar">
        <a class="v2-brand" href="./" aria-label="Retour à l’accueil AS amU">
            <img src="assets/img/logo-asamu-v2.png" alt="Logo AS amU">
            <span>
                <strong>AS amU</strong>
                <small>Association sportive universitaire</small>
            </span>
        </a>

        <div class="v2-header-actions">
            <a class="v2-compass-link" href="boussole">La boussole</a>
            <a class="v2-join-link" href="<?= e($site['helloasso_url']) ?>" target="_blank" rel="noopener">Adhérer <span aria-hidden="true">↗</span></a>
            <button class="v2-join-link" type="button" title="Boutique bientôt disponible">Boutique <span aria-hidden="true">↗</span></button>
            <button class="v2-menu-toggle" type="button" data-v2-menu-toggle aria-expanded="false" aria-controls="v2-menu-panel">
                <span>Menu</span>
                <span class="v2-menu-icon" aria-hidden="true"><i></i><i></i></span>
            </button>
        </div>
    </div>

    <div class="v2-menu-panel" id="v2-menu-panel" data-v2-menu-panel aria-hidden="true">
        <nav class="container v2-menu-grid" aria-label="Explorer le site">
            <?php foreach ($v2MenuGroups as $group => $links): ?>
                <section>
                    <p><?= e($group) ?></p>
                    <ul>
                        <?php foreach ($links as $link): ?>
                            <li><a href="<?= e($link['url']) ?>"<?= is_url_active($link['url']) ? ' aria-current="page"' : '' ?>><?= e($link['label']) ?><span aria-hidden="true">↗</span></a></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        </nav>
    </div>
</header>

<main id="contenu">
