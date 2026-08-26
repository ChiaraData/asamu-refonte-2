<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('sections');

$requestedSlug = admin_text($_GET['section'] ?? $_POST['section'] ?? '');
$section = find_section_by_slug($sections, $requestedSlug) ?? $sections[0];
$hasRequestedSection = $requestedSlug !== '' && find_section_by_slug($sections, $requestedSlug) !== null;
if (!$hasRequestedSection || !admin_can_edit_section((string) ($section['slug'] ?? ''))) {
    foreach ($sections as $availableSection) {
        if (admin_can_edit_section((string) ($availableSection['slug'] ?? ''))) {
            $section = $availableSection;
            break;
        }
    }
}
$slug = (string) $section['slug'];
if (!admin_can_edit_section($slug)) {
    admin_flash('error', 'Vous pouvez uniquement modifier les fiches qui vous sont attribuées.');
    admin_redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        if (($_POST['action'] ?? '') === 'reset') {
            unset($store['sections'][$slug]);
            $message = 'La fiche a retrouvé son contenu d’origine.';
        } else {
            $fields = ['name', 'component', 'city', 'campus', 'address', 'map_query', 'email', 'office_hours', 'adherents_count', 'licensees_count', 'notes'];
            $data = [];
            foreach ($fields as $field) {
                $data[$field] = admin_text($_POST[$field] ?? '');
            }
            $data['bureau'] = admin_post_rows(['role', 'name']);
            $data['activity_stats'] = admin_post_rows(['number', 'label']);
            $data['events'] = admin_post_rows(['event_title', 'event_date', 'event_description']);
            $data['events'] = array_map(static fn (array $row): array => [
                'title' => $row['event_title'], 'date' => $row['event_date'], 'description' => $row['event_description'],
            ], $data['events']);

            $blocks = admin_post_rows(['block_title', 'block_kicker', 'block_paragraphs']);
            $data['content_blocks'] = array_map(static fn (array $row): array => [
                'title' => $row['block_title'], 'kicker' => $row['block_kicker'], 'paragraphs' => array_map('rich_text_sanitize', admin_post_paragraphs($row['block_paragraphs'])),
            ], $blocks);
            $store['sections'][$slug] = $data;
            $message = 'Fiche « ' . $data['name'] . ' » enregistrée.';
        }

        if (content_store_write($store)) {
            admin_flash('success', $message);
        } else {
            admin_flash('error', admin_storage_error());
        }
    }
    admin_redirect('sections.php', ['section' => $slug]);
}

admin_page_start('Fiches sections', 'sections.php');
?>
<section class="admin-form-intro">
    <p class="admin-kicker">Sections universitaires</p>
    <h2>Une fiche complète pour chaque campus.</h2>
    <p>Les changements ci-dessous se répercutent immédiatement sur le site : coordonnées, permanences, référent·es, chiffres, textes et événements.</p>
</section>

<div class="admin-section-picker" aria-label="Choisir une section">
    <?php foreach ($sections as $item): ?>
        <?php if (!admin_can_edit_section((string) ($item['slug'] ?? ''))) continue; ?>
        <a href="sections.php?section=<?= e((string) $item['slug']) ?>"<?= $slug === $item['slug'] ? ' class="is-active" aria-current="page"' : '' ?>><?= e((string) $item['name']) ?></a>
    <?php endforeach; ?>
</div>

<form method="post" class="admin-form admin-section-form">
    <?= admin_form_token() ?>
    <input type="hidden" name="section" value="<?= e($slug) ?>">
    <section class="admin-form-card">
        <div class="admin-card-heading"><div><p class="admin-kicker">Informations pratiques</p><h2><?= e((string) $section['name']) ?></h2></div><a href="../section.php?slug=<?= e($slug) ?>" target="_blank" rel="noopener">Voir la fiche ↗</a></div>
        <div class="admin-fields two">
            <div class="admin-field"><label for="name">Nom de la section</label><input id="name" name="name" value="<?= e((string) $section['name']) ?>" required></div>
            <div class="admin-field"><label for="component">Composante / établissement</label><input id="component" name="component" value="<?= e((string) $section['component']) ?>" required></div>
            <div class="admin-field"><label for="city">Ville</label><input id="city" name="city" value="<?= e((string) $section['city']) ?>" required></div>
            <div class="admin-field"><label for="campus">Campus</label><input id="campus" name="campus" value="<?= e((string) $section['campus']) ?>" required></div>
            <div class="admin-field full"><label for="address">Adresse</label><textarea id="address" name="address" rows="3" required><?= e((string) $section['address']) ?></textarea></div>
            <div class="admin-field full"><label for="map_query">Recherche GPS</label><input id="map_query" name="map_query" value="<?= e((string) ($section['map_query'] ?? '')) ?>" placeholder="Nom officiel du lieu + adresse"><small>Optionnel : à renseigner seulement si Google Maps ne trouve pas précisément l’adresse.</small></div>
            <div class="admin-field"><label for="email">E-mail de la section</label><input id="email" type="email" name="email" value="<?= e((string) $section['email']) ?>" required></div>
            <div class="admin-field"><label for="office_hours">Permanences / horaires</label><input id="office_hours" name="office_hours" value="<?= e((string) $section['office_hours']) ?>" required></div>
            <div class="admin-field"><label for="adherents_count">Nombre d’adhérent·es</label><input id="adherents_count" name="adherents_count" inputmode="numeric" value="<?= e((string) ($section['adherents_count'] ?? '')) ?>"></div>
            <div class="admin-field"><label for="licensees_count">Nombre de licencié·es</label><input id="licensees_count" name="licensees_count" inputmode="numeric" value="<?= e((string) ($section['licensees_count'] ?? '')) ?>"></div>
            <div class="admin-field full"><label for="notes">Information complémentaire</label><textarea id="notes" name="notes" rows="3"><?= e((string) ($section['notes'] ?? '')) ?></textarea></div>
        </div>
    </section>

    <section class="admin-form-card" data-collection="bureau">
        <div class="admin-card-heading"><div><p class="admin-kicker">Référent·es</p><h2>Le bureau de la section</h2></div><button class="admin-button add" type="button" data-add-row="bureau">+ Ajouter une personne</button></div>
        <div class="admin-repeat-list" data-rows="bureau">
            <?php foreach (($section['bureau'] ?? []) as $person): ?>
                <div class="admin-repeat-row"><div class="admin-field"><label>Rôle</label><input name="role[]" value="<?= e((string) ($person['role'] ?? '')) ?>" placeholder="Ex. Présidence"></div><div class="admin-field"><label>Nom / noms</label><input name="name[]" value="<?= e((string) ($person['name'] ?? '')) ?>" placeholder="Prénom NOM"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette personne">×</button></div>
            <?php endforeach; ?>
        </div>
        <template data-template="bureau"><div class="admin-repeat-row"><div class="admin-field"><label>Rôle</label><input name="role[]" placeholder="Ex. Présidence"></div><div class="admin-field"><label>Nom / noms</label><input name="name[]" placeholder="Prénom NOM"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette personne">×</button></div></template>
    </section>

    <section class="admin-form-card" data-collection="statistics">
        <div class="admin-card-heading"><div><p class="admin-kicker">Chiffres supplémentaires</p><h2>Mettre en avant l’activité</h2><p>Exemples : « 15 équipes engagées », « 410 étudiant·es touché·es ».</p></div><button class="admin-button add" type="button" data-add-row="statistics">+ Ajouter un chiffre</button></div>
        <div class="admin-repeat-list" data-rows="statistics">
            <?php foreach (($section['activity_stats'] ?? []) as $stat): ?>
                <div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="number[]" value="<?= e((string) ($stat['number'] ?? '')) ?>"></div><div class="admin-field"><label>Libellé</label><input name="label[]" value="<?= e((string) ($stat['label'] ?? '')) ?>"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div>
            <?php endforeach; ?>
        </div>
        <template data-template="statistics"><div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="number[]"></div><div class="admin-field"><label>Libellé</label><input name="label[]" placeholder="Ex. équipes engagées"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div></template>
    </section>

    <section class="admin-form-card" data-collection="editorials">
        <div class="admin-card-heading"><div><p class="admin-kicker">Textes de présentation</p><h2>Compétitions, vie de section…</h2><p>Pour créer plusieurs paragraphes, laisse une ligne vide entre chaque paragraphe.</p></div><button class="admin-button add" type="button" data-add-row="editorials">+ Ajouter un bloc</button></div>
        <div class="admin-repeat-list stacked" data-rows="editorials">
            <?php foreach (($section['content_blocks'] ?? []) as $block): ?>
                <div class="admin-repeat-row admin-editorial-row"><div class="admin-fields two"><div class="admin-field"><label>Titre</label><input name="block_title[]" value="<?= e((string) ($block['title'] ?? '')) ?>"></div><div class="admin-field"><label>Petit titre</label><input name="block_kicker[]" value="<?= e((string) ($block['kicker'] ?? '')) ?>"></div><div class="admin-field full"><label>Texte</label><textarea name="block_paragraphs[]" rows="6" data-rich-editor><?php foreach (($block['paragraphs'] ?? []) as $index => $paragraph): ?><?= $index ? "\n\n" : '' ?><?= e((string) $paragraph) ?><?php endforeach; ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce bloc">×</button></div>
            <?php endforeach; ?>
        </div>
        <template data-template="editorials"><div class="admin-repeat-row admin-editorial-row"><div class="admin-fields two"><div class="admin-field"><label>Titre</label><input name="block_title[]"></div><div class="admin-field"><label>Petit titre</label><input name="block_kicker[]"></div><div class="admin-field full"><label>Texte</label><textarea name="block_paragraphs[]" rows="6" data-rich-editor></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce bloc">×</button></div></template>
    </section>

    <section class="admin-form-card" data-collection="events">
        <div class="admin-card-heading"><div><p class="admin-kicker">Rendez-vous</p><h2>Événements de la section</h2></div><button class="admin-button add" type="button" data-add-row="events">+ Ajouter un événement</button></div>
        <div class="admin-repeat-list stacked" data-rows="events">
            <?php foreach (($section['events'] ?? []) as $event): ?>
                <div class="admin-repeat-row admin-event-row"><div class="admin-fields three"><div class="admin-field"><label>Nom</label><input name="event_title[]" value="<?= e((string) ($event['title'] ?? '')) ?>"></div><div class="admin-field"><label>Date / période</label><input name="event_date[]" value="<?= e((string) ($event['date'] ?? '')) ?>"></div><div class="admin-field"><label>Description</label><input name="event_description[]" value="<?= e((string) ($event['description'] ?? '')) ?>"></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cet événement">×</button></div>
            <?php endforeach; ?>
        </div>
        <template data-template="events"><div class="admin-repeat-row admin-event-row"><div class="admin-fields three"><div class="admin-field"><label>Nom</label><input name="event_title[]"></div><div class="admin-field"><label>Date / période</label><input name="event_date[]"></div><div class="admin-field"><label>Description</label><input name="event_description[]"></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cet événement">×</button></div></template>
    </section>

    <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer la fiche</button><button class="admin-button danger" type="submit" name="action" value="reset" data-confirm="Réinitialiser toute cette fiche avec les contenus d’origine ?">Réinitialiser cette fiche</button></div>
</form>
<?php admin_page_end(); ?>
