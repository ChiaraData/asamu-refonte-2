<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('news');

/** @param array<int, array<string, mixed>> $posts */
function admin_news_slug(string $title, array $posts, string $currentId = ''): string
{
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title) ?: $title;
    $base = trim(strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $ascii)), '-');
    $base = $base ?: 'actualite';
    $slug = $base;
    $number = 2;
    $used = array_filter($posts, static fn (array $post): bool => ($post['id'] ?? '') !== $currentId);
    while (in_array($slug, array_column($used, 'slug'), true)) {
        $slug = $base . '-' . $number++;
    }
    return $slug;
}

$posts = array_values($newsPosts);
$editId = admin_text($_GET['edit'] ?? $_POST['news_id'] ?? '');
$editIndex = null;
foreach ($posts as $index => $post) {
    if (($post['id'] ?? '') === $editId) {
        $editIndex = $index;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        $action = admin_text($_POST['action'] ?? 'save');
        if ($action === 'reset') {
            unset($store['collections']['news']);
            $saved = content_store_write($store);
            admin_flash($saved ? 'success' : 'error', $saved ? 'Les actualités ont été réinitialisées.' : admin_storage_error());
        } elseif ($action === 'delete' && $editIndex !== null) {
            array_splice($posts, $editIndex, 1);
            $store['collections']['news'] = array_values($posts);
            $saved = content_store_write($store);
            admin_flash($saved ? 'success' : 'error', $saved ? 'Actualité supprimée.' : admin_storage_error());
        } else {
            $title = admin_text($_POST['title'] ?? '');
            $body = rich_text_sanitize((string) ($_POST['body'] ?? ''));
            $media = media_library_find(admin_text($_POST['cover_media_id'] ?? ''));
            if ($title === '' || $body === '') {
                admin_flash('error', 'Ajoute au minimum un titre et un texte à l’actualité.');
            } else {
                $existing = $editIndex === null ? null : $posts[$editIndex];
                $entry = [
                    'id' => $existing['id'] ?? ('news-' . bin2hex(random_bytes(7))),
                    'slug' => admin_news_slug($title, $posts, (string) ($existing['id'] ?? '')),
                    'title' => $title,
                    'category' => admin_text($_POST['category'] ?? '') ?: 'Vie de l’association',
                    'published_at' => admin_text($_POST['published_at'] ?? '') ?: date('Y-m-d'),
                    'excerpt' => admin_text($_POST['excerpt'] ?? ''),
                    'body' => $body,
                    'image' => $media && str_starts_with((string) ($media['mime'] ?? ''), 'image/') ? (string) ($media['path'] ?? '') : (string) ($existing['image'] ?? ''),
                    'image_alt' => $media && str_starts_with((string) ($media['mime'] ?? ''), 'image/') ? (string) ($media['alt'] ?? '') : (string) ($existing['image_alt'] ?? ''),
                    'is_published' => !empty($_POST['is_published']),
                    'updated_at' => gmdate('c'),
                ];
                if ($editIndex === null) {
                    $posts[] = $entry;
                    $message = 'Actualité créée.';
                } else {
                    $posts[$editIndex] = $entry;
                    $message = 'Actualité mise à jour.';
                }
                $store['collections']['news'] = array_values($posts);
                $saved = content_store_write($store);
                admin_flash($saved ? 'success' : 'error', $saved ? $message : admin_storage_error());
            }
        }
    }
    admin_redirect('news.php');
}

usort($posts, static fn (array $left, array $right): int => strcmp((string) ($right['published_at'] ?? ''), (string) ($left['published_at'] ?? '')));
$editingPost = $editIndex === null ? null : ($newsPosts[$editIndex] ?? null);
$post = $editingPost ?? [
    'id' => '', 'title' => '', 'category' => 'Vie de l’association', 'published_at' => date('Y-m-d'), 'excerpt' => '', 'body' => '', 'image' => '', 'image_alt' => '', 'is_published' => true,
];
$mediaImages = media_library_images();
admin_page_start('Actualités', 'news.php');
?>
<section class="admin-form-intro"><p class="admin-kicker">Informations à partager</p><h2>Publie les nouvelles de l’AS amU.</h2><p>Résultats, vie de campus, appels à participation ou annonces : écris, prévisualise et publie sans code.</p></section>

<section class="admin-news-layout">
    <form method="post" class="admin-form admin-news-form">
        <?= admin_form_token() ?>
        <input type="hidden" name="news_id" value="<?= e((string) ($post['id'] ?? '')) ?>">
        <section class="admin-form-card">
            <div class="admin-card-heading"><div><p class="admin-kicker"><?= $editingPost ? 'Modifier une actualité' : 'Nouvelle actualité' ?></p><h2><?= $editingPost ? e((string) ($post['title'] ?? 'Actualité')) : 'Créer une actualité' ?></h2></div><?php if ($editingPost): ?><a href="news.php">+ Nouvelle actualité</a><?php endif; ?></div>
            <div class="admin-fields two">
                <div class="admin-field full"><label for="title">Titre</label><input id="title" name="title" value="<?= e((string) ($post['title'] ?? '')) ?>" required placeholder="Ex. Les équipes AS amU brillent aux championnats de France"></div>
                <div class="admin-field"><label for="category">Catégorie</label><input id="category" name="category" value="<?= e((string) ($post['category'] ?? '')) ?>" placeholder="Compétitions, vie de l’association…"></div>
                <div class="admin-field"><label for="published_at">Date de publication</label><input id="published_at" type="date" name="published_at" value="<?= e((string) ($post['published_at'] ?? '')) ?>" required></div>
                <div class="admin-field full"><label for="excerpt">Résumé</label><textarea id="excerpt" name="excerpt" rows="3" placeholder="Deux phrases pour présenter l’information."><?= e((string) ($post['excerpt'] ?? '')) ?></textarea></div>
                <div class="admin-field full"><label for="cover_media_id">Image de couverture (facultatif)</label><select id="cover_media_id" name="cover_media_id"><option value="">Conserver l’image actuelle ou ne pas en ajouter</option><?php foreach ($mediaImages as $media): ?><option value="<?= e((string) ($media['id'] ?? '')) ?>"><?= e((string) ($media['folder'] ?? 'Non classé')) ?> · <?= e((string) ($media['title'] ?? $media['filename'] ?? 'Image')) ?></option><?php endforeach; ?></select><small><a class="admin-inline-link" href="media.php">Ajouter une image dans la médiathèque →</a></small></div>
                <div class="admin-field full"><label for="body">Texte de l’actualité</label><textarea id="body" name="body" rows="9" data-rich-editor><?= e((string) ($post['body'] ?? '')) ?></textarea></div>
                <div class="admin-field full"><label class="admin-switch"><input type="checkbox" name="is_published" value="1"<?= !empty($post['is_published']) ? ' checked' : '' ?>><span aria-hidden="true"></span>Publier cette actualité sur le site</label></div>
            </div>
        </section>
        <div class="admin-form-actions"><button class="admin-button primary" type="submit"><?= $editingPost ? 'Enregistrer l’actualité' : 'Créer l’actualité' ?></button><?php if ($editingPost): ?><button class="admin-button danger" type="submit" name="action" value="delete" data-confirm="Supprimer définitivement cette actualité ?">Supprimer</button><?php endif; ?></div>
    </form>
    <aside class="admin-news-list admin-form-card"><div class="admin-card-heading"><div><p class="admin-kicker">Publications</p><h2><?= count($posts) ?> actualité<?= count($posts) > 1 ? 's' : '' ?></h2></div></div><?php foreach ($posts as $item): ?><a href="news.php?edit=<?= e((string) ($item['id'] ?? '')) ?>" class="admin-news-list-item<?= ($post['id'] ?? '') === ($item['id'] ?? '') ? ' is-active' : '' ?>"><span><?= !empty($item['is_published']) ? 'Publié' : 'Brouillon' ?></span><strong><?= e((string) ($item['title'] ?? 'Sans titre')) ?></strong><small><?= e((string) ($item['published_at'] ?? '')) ?> · <?= e((string) ($item['category'] ?? '')) ?></small></a><?php endforeach; ?><form method="post" class="admin-reset-form"><?= admin_form_token() ?><button class="admin-text-button" type="submit" name="action" value="reset" data-confirm="Réinitialiser la liste des actualités ?">Réinitialiser les actualités</button></form></aside>
</section>
<?php admin_page_end(); ?>
