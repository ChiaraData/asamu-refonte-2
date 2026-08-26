<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('gallery');

$gallery = array_values($photoGallery);
$mediaImages = media_library_images();
$editIndex = filter_var($_GET['edit'] ?? $_POST['edit'] ?? null, FILTER_VALIDATE_INT);
$editIndex = $editIndex !== false && isset($gallery[$editIndex]) ? $editIndex : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        $action = (string) ($_POST['action'] ?? 'save');
        if ($action === 'reset') {
            $store['gallery'] = null;
            $saved = content_store_write($store);
            admin_flash($saved ? 'success' : 'error', $saved ? 'La photothèque a retrouvé son contenu d’origine.' : admin_storage_error());
        } elseif ($action === 'delete' && $editIndex !== null) {
            array_splice($gallery, $editIndex, 1);
            $store['gallery'] = array_values($gallery);
            $saved = content_store_write($store);
            admin_flash($saved ? 'success' : 'error', $saved ? 'Photo retirée de la photothèque.' : admin_storage_error());
        } else {
            $image = admin_text($_POST['image_existing'] ?? '');
            $libraryMedia = media_library_find(admin_text($_POST['media_id'] ?? ''));
            if ($libraryMedia && str_starts_with((string) ($libraryMedia['mime'] ?? ''), 'image/')) {
                $image = (string) ($libraryMedia['path'] ?? $image);
            }
            $upload = $_FILES['image_upload'] ?? null;
            if (is_array($upload) && (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $errorCode = (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE);
                if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file((string) ($upload['tmp_name'] ?? ''))) {
                    $uploadError = 'Le fichier image n’a pas pu être envoyé.';
                } elseif ((int) ($upload['size'] ?? 0) > 5 * 1024 * 1024) {
                    $uploadError = 'L’image dépasse la limite de 5 Mo.';
                } else {
                    $imageInfo = @getimagesize((string) $upload['tmp_name']);
                    $mime = is_array($imageInfo) ? ($imageInfo['mime'] ?? '') : '';
                    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                    if (!isset($extensions[$mime])) {
                        $uploadError = 'Utilise une image JPG, PNG ou WEBP.';
                    } else {
                        $filename = 'gallery-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extensions[$mime];
                        $destination = __DIR__ . '/../assets/img/gallery/' . $filename;
                        if (move_uploaded_file((string) $upload['tmp_name'], $destination)) {
                            $image = 'assets/img/gallery/' . $filename;
                        } else {
                            $uploadError = 'Impossible de placer l’image sur le serveur.';
                        }
                    }
                }
            }

            if (!empty($uploadError)) {
                admin_flash('error', $uploadError);
            } elseif ($image === '') {
                admin_flash('error', 'Ajoute une image avant d’enregistrer.');
            } else {
                $entry = [
                    'image' => $image,
                    'alt' => admin_text($_POST['alt'] ?? ''),
                    'category' => admin_text($_POST['category'] ?? ''),
                    'title' => admin_text($_POST['title'] ?? ''),
                    'description' => admin_text($_POST['description'] ?? ''),
                ];
                if ($editIndex === null) {
                    $gallery[] = $entry;
                    $message = 'Photo ajoutée à la photothèque.';
                } else {
                    $gallery[$editIndex] = $entry;
                    $message = 'Photo modifiée.';
                }
                $store['gallery'] = array_values($gallery);
                $saved = content_store_write($store);
                admin_flash($saved ? 'success' : 'error', $saved ? $message : admin_storage_error());
            }
        }
    }
    admin_redirect('gallery.php');
}

$entry = $editIndex === null ? ['image' => '', 'alt' => '', 'category' => '', 'title' => '', 'description' => ''] : $gallery[$editIndex];
admin_page_start('Photothèque', 'gallery.php');
?>
<section class="admin-form-intro"><p class="admin-kicker">Galerie photos</p><h2>Ajoute les moments forts de l’AS amU.</h2><p>Choisis une image depuis l’ordinateur, puis renseigne la légende qui apparaîtra sous la photo. Formats acceptés : JPG, PNG et WEBP (5 Mo maximum).</p></section>

<div class="admin-gallery-layout">
    <form method="post" enctype="multipart/form-data" class="admin-form admin-gallery-form">
        <?= admin_form_token() ?>
        <?php if ($editIndex !== null): ?><input type="hidden" name="edit" value="<?= $editIndex ?>"><?php endif; ?>
        <input type="hidden" name="image_existing" value="<?= e((string) ($entry['image'] ?? '')) ?>">
        <section class="admin-form-card">
            <div class="admin-card-heading"><div><p class="admin-kicker"><?= $editIndex === null ? 'Nouvelle photo' : 'Modifier la photo' ?></p><h2><?= $editIndex === null ? 'Ajouter une photo' : e((string) ($entry['title'] ?? 'Photo')) ?></h2></div><?php if ($editIndex !== null): ?><a href="gallery.php">+ Nouvelle photo</a><?php endif; ?></div>
            <?php if (!empty($entry['image'])): ?><img class="admin-gallery-preview" src="../<?= e((string) $entry['image']) ?>" alt="<?= e((string) ($entry['alt'] ?? '')) ?>"><?php endif; ?>
            <div class="admin-fields two">
                <div class="admin-field full"><label for="image_upload">Image <?= $editIndex === null ? '' : '(facultatif : laisse vide pour conserver celle-ci)' ?></label><input id="image_upload" type="file" name="image_upload" accept="image/jpeg,image/png,image/webp"></div>
                <?php if ($mediaImages): ?><div class="admin-field full"><label for="media_id">Ou choisir une image dans la médiathèque</label><select id="media_id" name="media_id"><option value="">Ne pas modifier l’image actuelle</option><?php foreach ($mediaImages as $media): ?><option value="<?= e((string) ($media['id'] ?? '')) ?>"><?= e((string) ($media['folder'] ?? 'Non classé')) ?> · <?= e((string) ($media['title'] ?? $media['filename'] ?? 'Image')) ?></option><?php endforeach; ?></select><small><a class="admin-inline-link" href="media.php">Ouvrir la médiathèque →</a></small></div><?php endif; ?>
                <div class="admin-field"><label for="category">Catégorie</label><input id="category" name="category" value="<?= e((string) ($entry['category'] ?? '')) ?>" placeholder="Ex. Football"></div>
                <div class="admin-field"><label for="title">Titre</label><input id="title" name="title" value="<?= e((string) ($entry['title'] ?? '')) ?>" required placeholder="Ex. Champions de France universitaires"></div>
                <div class="admin-field full"><label for="description">Description / légende</label><textarea id="description" name="description" rows="5" placeholder="Le résultat, la saison, le lieu, les personnes…"><?= e((string) ($entry['description'] ?? '')) ?></textarea></div>
                <div class="admin-field full"><label for="alt">Description de l’image (accessibilité)</label><input id="alt" name="alt" value="<?= e((string) ($entry['alt'] ?? '')) ?>" placeholder="Ex. Équipe de football AS amU sur le terrain"></div>
            </div>
        </section>
        <div class="admin-form-actions"><button class="admin-button primary" type="submit"><?= $editIndex === null ? 'Ajouter la photo' : 'Enregistrer la photo' ?></button><?php if ($editIndex !== null): ?><button class="admin-button danger" type="submit" name="action" value="delete" data-confirm="Retirer cette photo de la photothèque ?">Supprimer la photo</button><?php endif; ?></div>
    </form>

    <aside class="admin-gallery-list"><div class="admin-card-heading"><div><p class="admin-kicker">Photos publiées</p><h2><?= count($gallery) ?> photo<?= count($gallery) > 1 ? 's' : '' ?></h2></div></div>
        <?php foreach ($gallery as $index => $photo): ?><a class="admin-gallery-item<?= $editIndex === $index ? ' is-active' : '' ?>" href="gallery.php?edit=<?= $index ?>"><img src="../<?= e((string) $photo['image']) ?>" alt=""><span><small><?= e((string) ($photo['category'] ?? 'Photo')) ?></small><strong><?= e((string) ($photo['title'] ?? 'Sans titre')) ?></strong></span><b>→</b></a><?php endforeach; ?>
        <form method="post" class="admin-reset-form"><?= admin_form_token() ?><button class="admin-text-button" type="submit" name="action" value="reset" data-confirm="Réinitialiser toute la photothèque avec le contenu d’origine ?">Réinitialiser la photothèque</button></form>
    </aside>
</div>
<?php admin_page_end(); ?>
