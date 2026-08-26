<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('media');

$library = media_library_read();
$editingId = admin_text($_GET['edit'] ?? $_POST['media_id'] ?? '');
$editingMedia = $editingId !== '' ? media_library_find($editingId) : null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $action = admin_text($_POST['action'] ?? 'upload');
        $mediaId = admin_text($_POST['media_id'] ?? '');
        $existing = $mediaId !== '' ? media_library_find($mediaId) : null;

        if ($action === 'archive' && $existing) {
            foreach ($library['items'] as &$item) {
                if (($item['id'] ?? '') === $existing['id']) {
                    $item['archived_at'] = gmdate('c');
                    $item['updated_at'] = gmdate('c');
                    break;
                }
            }
            unset($item);
            $saved = media_library_write($library);
            if ($saved) {
                admin_record_audit('Média retiré de la bibliothèque', (string) ($existing['title'] ?? $existing['filename'] ?? ''));
            }
            admin_flash($saved ? 'success' : 'error', $saved ? 'Média retiré de la bibliothèque. Le fichier est conservé par sécurité.' : admin_storage_error());
        } elseif ($action === 'update' && $existing) {
            foreach ($library['items'] as &$item) {
                if (($item['id'] ?? '') !== $existing['id']) {
                    continue;
                }
                $item['title'] = admin_text($_POST['title'] ?? '');
                $item['alt'] = admin_text($_POST['alt'] ?? '');
                $item['description'] = admin_text($_POST['description'] ?? '');
                $item['folder'] = admin_text($_POST['folder'] ?? '') ?: 'Non classé';
                $item['focal_x'] = min(100, max(0, (int) ($_POST['focal_x'] ?? 50)));
                $item['focal_y'] = min(100, max(0, (int) ($_POST['focal_y'] ?? 50)));
                $item['updated_at'] = gmdate('c');
                break;
            }
            unset($item);
            $saved = media_library_write($library);
            if ($saved) {
                admin_record_audit('Média mis à jour', (string) ($existing['title'] ?? $existing['filename'] ?? ''));
            }
            admin_flash($saved ? 'success' : 'error', $saved ? 'Informations du média enregistrées.' : admin_storage_error());
        } else {
            $folder = admin_text($_POST['folder'] ?? '') ?: 'Non classé';
            $uploaded = admin_store_media((array) ($_FILES['media_file'] ?? []), $folder);
            if ($uploaded['error'] !== '') {
                admin_flash('error', $uploaded['error']);
            } elseif ($uploaded['path'] === '') {
                admin_flash('error', 'Choisis un fichier avant de l’envoyer.');
            } else {
                $originalName = admin_text($_FILES['media_file']['name'] ?? '');
                $library['items'][] = [
                    'id' => 'media-' . bin2hex(random_bytes(8)),
                    'path' => $uploaded['path'],
                    'filename' => $uploaded['filename'],
                    'original_name' => $originalName,
                    'mime' => $uploaded['mime'],
                    'size' => $uploaded['size'],
                    'width' => $uploaded['width'],
                    'height' => $uploaded['height'],
                    'optimized' => $uploaded['optimized'],
                    'folder' => $folder,
                    'title' => admin_text($_POST['title'] ?? '') ?: pathinfo($originalName, PATHINFO_FILENAME),
                    'alt' => admin_text($_POST['alt'] ?? ''),
                    'description' => admin_text($_POST['description'] ?? ''),
                    'focal_x' => min(100, max(0, (int) ($_POST['focal_x'] ?? 50))),
                    'focal_y' => min(100, max(0, (int) ($_POST['focal_y'] ?? 50))),
                    'created_at' => gmdate('c'),
                    'updated_at' => gmdate('c'),
                ];
                $saved = media_library_write($library);
                if ($saved) {
                    admin_record_audit('Média ajouté', $originalName);
                }
                admin_flash($saved ? 'success' : 'error', $saved ? 'Média ajouté. Il est prêt à être utilisé dans la photothèque ou les pages.' : admin_storage_error());
            }
        }
    }
    admin_redirect('media.php');
}

$folders = media_library_folders();
$filterFolder = admin_text($_GET['folder'] ?? '');
$items = media_library_items();
if ($filterFolder !== '') {
    $items = array_values(array_filter($items, static fn (array $item): bool => ($item['folder'] ?? '') === $filterFolder));
}
$media = $editingMedia ?? [
    'id' => '', 'title' => '', 'alt' => '', 'description' => '', 'folder' => $filterFolder ?: 'Photothèque', 'focal_x' => 50, 'focal_y' => 50,
];
admin_page_start('Médiathèque', 'media.php');
?>
<section class="admin-form-intro">
    <p class="admin-kicker">Images et documents</p>
    <h2>Une bibliothèque claire, prête à publier.</h2>
    <p>Dépose une photo ou un PDF, classe-le dans un dossier et renseigne sa description. Les images sont optimisées automatiquement lorsque le serveur le permet.</p>
</section>

<section class="admin-media-layout">
    <form method="post" enctype="multipart/form-data" class="admin-form admin-media-form">
        <?= admin_form_token() ?>
        <input type="hidden" name="media_id" value="<?= e((string) ($media['id'] ?? '')) ?>">
        <input type="hidden" name="action" value="<?= $editingMedia ? 'update' : 'upload' ?>">
        <section class="admin-form-card">
            <div class="admin-card-heading"><div><p class="admin-kicker"><?= $editingMedia ? 'Modifier le média' : 'Nouveau média' ?></p><h2><?= $editingMedia ? e((string) ($media['title'] ?? 'Média')) : 'Ajouter un fichier' ?></h2></div><?php if ($editingMedia): ?><a href="media.php">+ Ajouter un média</a><?php endif; ?></div>
            <?php if ($editingMedia && str_starts_with((string) ($media['mime'] ?? ''), 'image/')): ?><img class="admin-media-edit-preview" src="../<?= e((string) $media['path']) ?>" alt="<?= e((string) ($media['alt'] ?? '')) ?>" style="object-position: <?= (int) ($media['focal_x'] ?? 50) ?>% <?= (int) ($media['focal_y'] ?? 50) ?>%;"><?php elseif ($editingMedia): ?><div class="admin-document-preview">PDF<br><small><?= e((string) ($media['original_name'] ?? $media['filename'] ?? 'Document')) ?></small></div><?php endif; ?>
            <div class="admin-fields two">
                <?php if (!$editingMedia): ?><div class="admin-field full"><label for="media_file">Fichier</label><label class="admin-dropzone" for="media_file" data-media-dropzone><input id="media_file" type="file" name="media_file" accept="image/jpeg,image/png,image/webp,application/pdf" required data-media-input><strong>Glisse ton image ou document ici</strong><span>ou choisis un fichier · JPG, PNG, WEBP ou PDF · 12 Mo max.</span></label></div><?php endif; ?>
                <div class="admin-field"><label for="folder">Dossier</label><input id="folder" name="folder" list="media-folders" value="<?= e((string) ($media['folder'] ?? 'Photothèque')) ?>" required placeholder="Ex. Compétitions 2026/2027"><datalist id="media-folders"><?php foreach ($folders as $folder): ?><option value="<?= e($folder) ?>"><?php endforeach; ?></datalist></div>
                <div class="admin-field"><label for="title">Nom du média</label><input id="title" name="title" value="<?= e((string) ($media['title'] ?? '')) ?>" placeholder="Ex. Équipe de handball · FISU 2026"></div>
                <div class="admin-field full"><label for="alt">Description de l’image (accessibilité)</label><input id="alt" name="alt" value="<?= e((string) ($media['alt'] ?? '')) ?>" placeholder="Décris brièvement ce qui est visible sur l’image."></div>
                <div class="admin-field full"><label for="description">Légende / information interne</label><textarea id="description" name="description" rows="4" placeholder="Résultat, lieu, saison, droits d’utilisation…"><?= e((string) ($media['description'] ?? '')) ?></textarea></div>
                <div class="admin-field"><label for="focal_x">Cadrage horizontal</label><input id="focal_x" name="focal_x" type="range" min="0" max="100" value="<?= (int) ($media['focal_x'] ?? 50) ?>" data-focal-input="x"><small>Déplace le sujet dans les aperçus.</small></div>
                <div class="admin-field"><label for="focal_y">Cadrage vertical</label><input id="focal_y" name="focal_y" type="range" min="0" max="100" value="<?= (int) ($media['focal_y'] ?? 50) ?>" data-focal-input="y"><small>Le fichier original reste intact.</small></div>
            </div>
        </section>
        <div class="admin-form-actions"><button class="admin-button primary" type="submit"><?= $editingMedia ? 'Enregistrer le média' : 'Ajouter à la médiathèque' ?></button><?php if ($editingMedia): ?><button class="admin-button danger" type="submit" name="action" value="archive" data-confirm="Retirer ce média de la bibliothèque ? Le fichier restera conservé par sécurité.">Retirer de la bibliothèque</button><?php endif; ?></div>
    </form>

    <aside class="admin-media-library" aria-label="Fichiers de la médiathèque">
        <div class="admin-card-heading"><div><p class="admin-kicker">Bibliothèque</p><h2><?= count($items) ?> fichier<?= count($items) > 1 ? 's' : '' ?></h2></div></div>
        <nav class="admin-media-filters"><a href="media.php"<?= $filterFolder === '' ? ' class="is-active"' : '' ?>>Tous</a><?php foreach ($folders as $folder): ?><a href="media.php?folder=<?= urlencode($folder) ?>"<?= $filterFolder === $folder ? ' class="is-active"' : '' ?>><?= e($folder) ?></a><?php endforeach; ?></nav>
        <div class="admin-media-grid">
            <?php foreach ($items as $item): ?>
                <a href="media.php?edit=<?= e((string) ($item['id'] ?? '')) ?>" class="admin-media-item<?= ($media['id'] ?? '') === ($item['id'] ?? '') ? ' is-active' : '' ?>">
                    <?php if (str_starts_with((string) ($item['mime'] ?? ''), 'image/')): ?><img src="../<?= e((string) $item['path']) ?>" alt="<?= e((string) ($item['alt'] ?? '')) ?>" loading="lazy" style="object-position: <?= (int) ($item['focal_x'] ?? 50) ?>% <?= (int) ($item['focal_y'] ?? 50) ?>%;"><?php else: ?><span class="admin-media-pdf">PDF</span><?php endif; ?>
                    <span><small><?= e((string) ($item['folder'] ?? 'Non classé')) ?></small><strong><?= e((string) ($item['title'] ?? $item['filename'] ?? 'Média')) ?></strong></span>
                </a>
            <?php endforeach; ?>
            <?php if (!$items): ?><p class="admin-media-empty">Aucun fichier dans ce dossier pour l’instant.</p><?php endif; ?>
        </div>
    </aside>
</section>
<?php admin_page_end(); ?>
