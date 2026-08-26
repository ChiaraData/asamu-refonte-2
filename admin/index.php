<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('dashboard');

$recentChanges = array_slice(admin_audit_read(), 0, 6);
$mediaCount = count(media_library_items());
$dashboardCards = [
    ['permission' => 'sections', 'url' => 'sections.php', 'icon' => '◫', 'number' => count($sections), 'label' => 'sections à jour', 'action' => 'Gérer les sections', 'tone' => 'blue'],
    ['permission' => 'calendar', 'url' => 'calendar.php', 'icon' => '◷', 'number' => count($competitionCalendar), 'label' => 'rendez-vous publiés', 'action' => 'Gérer le calendrier', 'tone' => 'yellow'],
    ['permission' => 'gallery', 'url' => 'gallery.php', 'icon' => '▣', 'number' => count($photoGallery), 'label' => 'photos publiées', 'action' => 'Gérer la photothèque', 'tone' => 'green'],
    ['permission' => 'palmares', 'url' => 'palmares.php', 'icon' => '★', 'number' => count($palmares), 'label' => 'podiums publiés', 'action' => 'Gérer le palmarès', 'tone' => 'blue'],
];

admin_page_start('Tableau de bord', 'index.php');
?>
<section class="admin-dashboard-hero">
    <div>
        <p class="admin-kicker">Bonjour <?= e((string) (admin_current_user()['display_name'] ?? '')) ?></p>
        <h2>Tout le site, sans toucher au code.</h2>
        <p>Choisis une rubrique, mets à jour le contenu puis vérifie le résultat en direct. Chaque enregistrement est sécurisé et publié immédiatement sur le site.</p>
    </div>
    <div class="admin-dashboard-actions">
        <?php if (admin_can('calendar')): ?><a class="admin-button primary" href="calendar.php">+ Ajouter un événement</a><?php endif; ?>
        <?php if (admin_can('gallery')): ?><a class="admin-button secondary" href="gallery.php">+ Ajouter une photo</a><?php endif; ?>
        <a class="admin-button admin-button-light" href="../index.php" target="_blank" rel="noopener">Prévisualiser le site ↗</a>
    </div>
</section>

<section class="admin-dashboard-metrics" aria-label="Vue d’ensemble du site">
    <?php foreach ($dashboardCards as $card): ?>
        <?php if (!admin_can($card['permission'])) continue; ?>
        <a class="admin-dashboard-metric <?= e($card['tone']) ?>" href="<?= e($card['url']) ?>"><span><?= e($card['icon']) ?></span><strong><?= e((string) $card['number']) ?></strong><p><?= e($card['label']) ?></p><em><?= e($card['action']) ?> →</em></a>
    <?php endforeach; ?>
    <?php if (admin_can('media')): ?><a class="admin-dashboard-metric purple" href="media.php"><span>◉</span><strong><?= $mediaCount ?></strong><p>fichier<?= $mediaCount > 1 ? 's' : '' ?> dans la médiathèque</p><em>Ouvrir la médiathèque →</em></a><?php endif; ?>
</section>

<section class="admin-dashboard-grid">
    <article class="admin-dashboard-panel admin-form-card">
        <div class="admin-card-heading"><div><p class="admin-kicker">Raccourcis</p><h2>Les actions fréquentes</h2></div></div>
        <div class="admin-quick-actions">
            <?php if (admin_can('sections')): ?><a href="sections.php"><b>01</b><span><strong>Mettre à jour une section</strong><small>Horaires, personnes référentes, chiffres et événements.</small></span><i>→</i></a><?php endif; ?>
            <?php if (admin_can('media')): ?><a href="media.php"><b>02</b><span><strong>Ajouter une image ou un PDF</strong><small>Classe et optimise tes médias avant publication.</small></span><i>→</i></a><?php endif; ?>
            <?php if (admin_can('palmares')): ?><a href="palmares.php"><b>03</b><span><strong>Actualiser les podiums</strong><small>Par saison, sport et compétition.</small></span><i>→</i></a><?php endif; ?>
            <?php if (admin_can('settings')): ?><a href="settings.php"><b>04</b><span><strong>Modifier les informations générales</strong><small>Contacts, chiffres et organisation de l’AS amU.</small></span><i>→</i></a><?php endif; ?>
        </div>
    </article>
    <article class="admin-dashboard-panel admin-form-card">
        <div class="admin-card-heading"><div><p class="admin-kicker">Historique</p><h2>Dernières modifications</h2></div></div>
        <?php if ($recentChanges): ?><ol class="admin-activity-feed"><?php foreach ($recentChanges as $change): ?><li><span></span><div><strong><?= e((string) ($change['action'] ?? 'Mise à jour')) ?></strong><?php if (!empty($change['details'])): ?><p><?= e((string) $change['details']) ?></p><?php endif; ?><small><?= e((string) ($change['user'] ?? '')) ?> · <?= e(date('d/m/Y à H:i', strtotime((string) ($change['created_at'] ?? 'now')))) ?></small></div></li><?php endforeach; ?></ol><?php else: ?><div class="admin-empty-state"><strong>Les futures mises à jour apparaîtront ici.</strong><p>Le journal permet de savoir qui a modifié quoi, et quand.</p></div><?php endif; ?>
    </article>
</section>

<section class="admin-dashboard-note"><span>✓</span><div><strong>Avant de publier</strong><p>Vérifie les noms, dates, liens et descriptions d’images. Le bouton « Voir le site » ouvre toujours une prévisualisation dans un nouvel onglet.</p></div></section>
<?php admin_page_end(); ?>

