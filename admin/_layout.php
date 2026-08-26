<?php
declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';

function admin_page_start(string $title, string $active = ''): void
{
    $items = [
        'index.php' => ['label' => 'Tableau de bord', 'icon' => '⌂', 'permission' => 'dashboard'],
        'sections.php' => ['label' => 'Fiches sections', 'icon' => '◫', 'permission' => 'sections'],
        'calendar.php' => ['label' => 'Calendrier', 'icon' => '◷', 'permission' => 'calendar'],
        'coaches.php' => ['label' => 'Coachs', 'icon' => '♙', 'permission' => 'coaches'],
        'gallery.php' => ['label' => 'Photothèque', 'icon' => '▣', 'permission' => 'gallery'],
        'media.php' => ['label' => 'Médiathèque', 'icon' => '◉', 'permission' => 'media'],
        'news.php' => ['label' => 'Actualités', 'icon' => '✦', 'permission' => 'news'],
        'palmares.php' => ['label' => 'Palmarès', 'icon' => '★', 'permission' => 'palmares'],
        'google-sheets.php' => ['label' => 'Google Sheets', 'icon' => '↗', 'permission' => 'google_sheets'],
        'settings.php' => ['label' => 'Informations du site', 'icon' => '⚙', 'permission' => 'settings'],
        'content.php' => ['label' => 'Autres contenus', 'icon' => '☷', 'permission' => 'content'],
        'users.php' => ['label' => 'Utilisateurs et rôles', 'icon' => '♧', 'permission' => 'users'],
    ];
    $currentUser = admin_current_user();
    $roleDefinitions = admin_role_definitions();
    $roleLabel = $roleDefinitions[(string) ($currentUser['role'] ?? 'viewer')]['label'] ?? 'Compte';
    $flashSuccess = admin_flash('success');
    $flashError = admin_flash('error');
    $flashInfo = admin_flash('info');
    $adminStylesheetVersion = (string) filemtime(__DIR__ . '/assets/admin.css');
    $adminScriptVersion = (string) filemtime(__DIR__ . '/assets/admin.js');
    ?>
    <!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title><?= e($title) ?> · Administration AS amU</title>
        <link rel="stylesheet" href="assets/admin.css?v=<?= e($adminStylesheetVersion) ?>">
    </head>
    <body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar" id="admin-sidebar">
            <a class="admin-brand" href="index.php" aria-label="Tableau de bord AS amU">
                <img src="../assets/img/logo-asamu-v2.png" alt="AS amU">
                <span>Administration</span>
            </a>
            <nav class="admin-nav" aria-label="Navigation d’administration">
                <?php foreach ($items as $url => $item): ?>
                    <?php if (!admin_can((string) $item['permission'])) continue; ?>
                    <a href="<?= e($url) ?>"<?= $active === $url ? ' class="is-active" aria-current="page"' : '' ?>><i aria-hidden="true"><?= e($item['icon']) ?></i><?= e($item['label']) ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="admin-sidebar-bottom">
                <div class="admin-user-card"><strong><?= e((string) ($currentUser['display_name'] ?? 'Compte AS amU')) ?></strong><span><?= e($roleLabel) ?></span></div>
                <a href="../index.php" target="_blank" rel="noopener">↗ Voir le site</a>
                <a href="password.php">Changer le mot de passe</a>
                <a class="admin-logout" href="logout.php">Se déconnecter</a>
            </div>
        </aside>
        <main class="admin-main">
            <header class="admin-topbar">
                <button class="admin-menu-button" type="button" data-menu-toggle aria-controls="admin-sidebar" aria-label="Ouvrir le menu">☰</button>
                <div>
                    <p>AS amU · espace de mise à jour</p>
                    <h1><?= e($title) ?></h1>
                </div>
                <div class="admin-topbar-actions">
                    <a class="admin-site-link" href="../index.php" target="_blank" rel="noopener">Voir le site ↗</a>
                    <a class="admin-logout-button" href="logout.php">Se déconnecter</a>
                </div>
            </header>
            <?php if ($flashSuccess): ?><div class="admin-alert success" role="status">✓ <?= e($flashSuccess) ?></div><?php endif; ?>
            <?php if ($flashError): ?><div class="admin-alert error" role="alert"><?= e($flashError) ?></div><?php endif; ?>
            <?php if ($flashInfo): ?><div class="admin-alert info" role="status">ℹ <?= e($flashInfo) ?></div><?php endif; ?>
    <?php
}

function admin_page_end(): void
{
    $adminScriptVersion = (string) filemtime(__DIR__ . '/assets/admin.js');
    ?>
        </main>
    </div>
    <script src="assets/admin.js?v=<?= e($adminScriptVersion) ?>"></script>
    </body>
    </html>
    <?php
}
