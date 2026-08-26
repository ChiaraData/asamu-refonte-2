<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/boussole-data.php';
admin_require_permission('content');

$contentTypes = [
    'sports' => 'Disciplines sportives',
    'adhesion' => 'Étapes d’adhésion',
    'documents' => 'Documents utiles',
    'commissions' => 'Commissions',
    'partners' => 'Partenaires',
    'compass' => 'Boussole du sport',
];
$type = admin_text($_GET['type'] ?? $_POST['type'] ?? 'sports');
if (!isset($contentTypes[$type])) {
    $type = 'sports';
}

/** @return array<int, array<string, string>> */
function admin_compass_options(string $value): array
{
    $options = [];
    foreach (preg_split('/\R/u', trim($value)) ?: [] as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (($parts[1] ?? '') === '') {
            continue;
        }
        $option = [
            'actor' => $parts[0] ?: 'AS_AMU', 'label' => $parts[1], 'title' => $parts[2] ?? '',
            'description' => $parts[3] ?? '', 'url' => $parts[5] ?? '',
        ];
        if (($parts[4] ?? '') !== '') {
            $option['info'] = $parts[4];
        }
        $options[] = $option;
    }
    return $options;
}

function admin_compass_option_text(array $options): string
{
    return implode("\n", array_map(static fn (array $option): string => implode(' | ', [
        admin_text($option['actor'] ?? ''), admin_text($option['label'] ?? ''), admin_text($option['title'] ?? ''),
        admin_text($option['description'] ?? ''), admin_text($option['info'] ?? ''), admin_text($option['url'] ?? ''),
    ]), $options));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        if (($_POST['action'] ?? '') === 'reset') {
            $resetKeys = [
                'sports' => ['sports'], 'adhesion' => ['adhesion_steps'], 'documents' => ['association_documents', 'competition_documents'],
                'commissions' => ['commissions'], 'partners' => ['partners'], 'compass' => ['compass_actors', 'compass_categories'],
            ];
            foreach ($resetKeys[$type] as $key) {
                unset($store['collections'][$key]);
            }
            $message = 'Contenu réinitialisé.';
        } elseif ($type === 'sports') {
            $groups = admin_post_rows(['group_name', 'group_items']);
            $sportsData = [];
            foreach ($groups as $group) {
                if ($group['group_name'] !== '') {
                    $sportsData[$group['group_name']] = admin_post_lines($group['group_items']);
                }
            }
            $store['collections']['sports'] = $sportsData;
            $message = 'Disciplines sportives enregistrées.';
        } elseif ($type === 'adhesion') {
            $steps = admin_post_rows(['step_title', 'step_text']);
            $store['collections']['adhesion_steps'] = array_map(static fn (array $step): array => ['title' => $step['step_title'], 'text' => $step['step_text']], $steps);
            $message = 'Étapes d’adhésion enregistrées.';
        } elseif ($type === 'documents') {
            $associationDocs = admin_post_rows(['association_label', 'association_type', 'association_description', 'association_url']);
            $competitionDocsRows = admin_post_rows(['competition_label', 'competition_url']);
            $store['collections']['association_documents'] = array_map(static fn (array $doc): array => ['label' => $doc['association_label'], 'type' => $doc['association_type'], 'description' => $doc['association_description'], 'url' => $doc['association_url']], $associationDocs);
            $store['collections']['competition_documents'] = array_map(static fn (array $doc): array => ['label' => $doc['competition_label'], 'url' => $doc['competition_url']], $competitionDocsRows);
            $message = 'Documents enregistrés.';
        } elseif ($type === 'commissions') {
            $rows = admin_post_rows(['commission_name', 'commission_mission', 'commission_members', 'commission_contact']);
            $store['collections']['commissions'] = array_map(static fn (array $row): array => ['name' => $row['commission_name'], 'mission' => $row['commission_mission'], 'members' => $row['commission_members'], 'contact' => $row['commission_contact']], $rows);
            $message = 'Commissions enregistrées.';
        } elseif ($type === 'partners') {
            $fields = ['partner_name', 'partner_url', 'partner_description', 'partner_logo_existing'];
            $length = max(...array_map(static fn (string $field): int => is_array($_POST[$field] ?? null) ? count($_POST[$field]) : 0, $fields));
            $partnersData = [];
            $errors = [];
            for ($index = 0; $index < $length; $index++) {
                $name = admin_text($_POST['partner_name'][$index] ?? '');
                if ($name === '') {
                    continue;
                }
                $logo = admin_text($_POST['partner_logo_existing'][$index] ?? '');
                $upload = ['error' => $_FILES['partner_logo']['error'][$index] ?? UPLOAD_ERR_NO_FILE, 'tmp_name' => $_FILES['partner_logo']['tmp_name'][$index] ?? '', 'size' => $_FILES['partner_logo']['size'][$index] ?? 0];
                $logoUpload = admin_store_image($upload, 'partners', 'partner');
                if ($logoUpload['error'] !== '') {
                    $errors[] = $logoUpload['error'];
                }
                if ($logoUpload['path'] !== '') {
                    $logo = $logoUpload['path'];
                }
                $partnersData[] = ['name' => $name, 'url' => admin_text($_POST['partner_url'][$index] ?? ''), 'description' => admin_text($_POST['partner_description'][$index] ?? ''), 'logo' => $logo];
            }
            $store['collections']['partners'] = $partnersData;
            $message = empty($errors) ? 'Partenaires enregistrés.' : 'Partenaires enregistrés, mais certains logos n’ont pas été ajoutés : ' . implode(' ', array_unique($errors));
        } else {
            $actors = admin_post_rows(['actor_id', 'actor_name', 'actor_description']);
            $actorsData = [];
            foreach ($actors as $actor) {
                if ($actor['actor_id'] !== '') {
                    $actorsData[$actor['actor_id']] = ['name' => $actor['actor_name'], 'description' => $actor['actor_description']];
                }
            }
            $categories = admin_post_rows(['compass_id', 'compass_emoji', 'compass_title', 'compass_subtitle', 'compass_theme', 'compass_options']);
            $categoriesData = [];
            foreach ($categories as $category) {
                if ($category['compass_title'] !== '') {
                    $categoriesData[] = ['id' => $category['compass_id'] ?: strtolower((string) preg_replace('/\s+/', '-', $category['compass_title'])), 'emoji' => $category['compass_emoji'], 'title' => $category['compass_title'], 'subtitle' => $category['compass_subtitle'], 'theme' => $category['compass_theme'] ?: 'blue', 'options' => admin_compass_options($category['compass_options'])];
                }
            }
            $store['collections']['compass_actors'] = $actorsData;
            $store['collections']['compass_categories'] = $categoriesData;
            $message = 'Boussole du sport enregistrée.';
        }
        if (content_store_write($store)) {
            admin_flash('success', $message);
        } else {
            admin_flash('error', admin_storage_error());
        }
    }
    admin_redirect('content.php', ['type' => $type]);
}

admin_page_start('Autres contenus', 'content.php');
?>
<section class="admin-form-intro"><p class="admin-kicker">Contenus complémentaires</p><h2>Les autres pages du site, sans code.</h2><p>Choisis une rubrique pour modifier les informations qui y sont publiées.</p></section>
<nav class="admin-tabs" aria-label="Choisir un contenu"><?php foreach ($contentTypes as $key => $label): ?><a href="content.php?type=<?= e($key) ?>"<?= $type === $key ? ' class="is-active" aria-current="page"' : '' ?>><?= e($label) ?></a><?php endforeach; ?></nav>
<form method="post" enctype="multipart/form-data" class="admin-form"><input type="hidden" name="type" value="<?= e($type) ?>"><?= admin_form_token() ?>
<?php if ($type === 'sports'): ?>
    <section class="admin-form-card" data-collection="sport-groups"><div class="admin-card-heading"><div><p class="admin-kicker">Page Sections</p><h2>Familles de sports</h2><p>Un sport par ligne dans chaque famille.</p></div><button class="admin-button add" type="button" data-add-row="sport-groups">+ Ajouter une famille</button></div><div class="admin-repeat-list stacked" data-rows="sport-groups"><?php foreach ($sports as $group => $items): ?><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Nom de la famille</label><input name="group_name[]" value="<?= e((string) $group) ?>"></div><div class="admin-field"><label>Sports (un par ligne)</label><textarea name="group_items[]" rows="5"><?php foreach ($items as $item): ?><?= e((string) $item) ?>
<?php endforeach; ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette famille">×</button></div><?php endforeach; ?></div><template data-template="sport-groups"><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Nom de la famille</label><input name="group_name[]"></div><div class="admin-field"><label>Sports (un par ligne)</label><textarea name="group_items[]" rows="5"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette famille">×</button></div></template></section>
<?php elseif ($type === 'adhesion'): ?>
    <section class="admin-form-card" data-collection="adhesion-steps"><div class="admin-card-heading"><div><p class="admin-kicker">Page Adhésion</p><h2>Étapes à suivre</h2></div><button class="admin-button add" type="button" data-add-row="adhesion-steps">+ Ajouter une étape</button></div><div class="admin-repeat-list stacked" data-rows="adhesion-steps"><?php foreach ($adhesionSteps as $step): ?><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Titre</label><input name="step_title[]" value="<?= e((string) ($step['title'] ?? '')) ?>"></div><div class="admin-field"><label>Texte</label><textarea name="step_text[]" rows="3"><?= e((string) ($step['text'] ?? '')) ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette étape">×</button></div><?php endforeach; ?></div><template data-template="adhesion-steps"><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Titre</label><input name="step_title[]"></div><div class="admin-field"><label>Texte</label><textarea name="step_text[]" rows="3"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette étape">×</button></div></template></section>
<?php elseif ($type === 'documents'): ?>
    <section class="admin-form-card" data-collection="association-documents"><div class="admin-card-heading"><div><p class="admin-kicker">Organisation</p><h2>Documents de l’association</h2></div><button class="admin-button add" type="button" data-add-row="association-documents">+ Ajouter un document</button></div><div class="admin-repeat-list stacked" data-rows="association-documents"><?php foreach ($associationDocuments as $doc): ?><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Nom</label><input name="association_label[]" value="<?= e((string) ($doc['label'] ?? '')) ?>"></div><div class="admin-field"><label>Type</label><input name="association_type[]" value="<?= e((string) ($doc['type'] ?? '')) ?>"></div><div class="admin-field"><label>Lien</label><input type="url" name="association_url[]" value="<?= e((string) ($doc['url'] ?? '')) ?>"></div><div class="admin-field"><label>Description</label><textarea name="association_description[]" rows="3"><?= e((string) ($doc['description'] ?? '')) ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce document">×</button></div><?php endforeach; ?></div><template data-template="association-documents"><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Nom</label><input name="association_label[]"></div><div class="admin-field"><label>Type</label><input name="association_type[]"></div><div class="admin-field"><label>Lien</label><input type="url" name="association_url[]"></div><div class="admin-field"><label>Description</label><textarea name="association_description[]" rows="3"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce document">×</button></div></template></section>
    <section class="admin-form-card" data-collection="competition-documents"><div class="admin-card-heading"><div><p class="admin-kicker">Compétitions</p><h2>Formulaires et fichiers</h2></div><button class="admin-button add" type="button" data-add-row="competition-documents">+ Ajouter un document</button></div><div class="admin-repeat-list" data-rows="competition-documents"><?php foreach ($competitionDocs as $doc): ?><div class="admin-repeat-row"><div class="admin-field"><label>Nom</label><input name="competition_label[]" value="<?= e((string) ($doc['label'] ?? '')) ?>"></div><div class="admin-field"><label>Lien</label><input type="url" name="competition_url[]" value="<?= e((string) ($doc['url'] ?? '')) ?>"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce document">×</button></div><?php endforeach; ?></div><template data-template="competition-documents"><div class="admin-repeat-row"><div class="admin-field"><label>Nom</label><input name="competition_label[]"></div><div class="admin-field"><label>Lien</label><input type="url" name="competition_url[]"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce document">×</button></div></template></section>
<?php elseif ($type === 'commissions'): ?>
    <section class="admin-form-card" data-collection="commissions"><div class="admin-card-heading"><div><p class="admin-kicker">Organisation</p><h2>Les commissions</h2></div><button class="admin-button add" type="button" data-add-row="commissions">+ Ajouter une commission</button></div><div class="admin-repeat-list stacked" data-rows="commissions"><?php foreach ($commissions as $commission): ?><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Nom</label><input name="commission_name[]" value="<?= e((string) ($commission['name'] ?? '')) ?>"></div><div class="admin-field"><label>Membres</label><input name="commission_members[]" value="<?= e((string) ($commission['members'] ?? '')) ?>"></div><div class="admin-field"><label>E-mail</label><input type="email" name="commission_contact[]" value="<?= e((string) ($commission['contact'] ?? '')) ?>"></div><div class="admin-field"><label>Mission</label><textarea name="commission_mission[]" rows="3"><?= e((string) ($commission['mission'] ?? '')) ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette commission">×</button></div><?php endforeach; ?></div><template data-template="commissions"><div class="admin-repeat-row"><div class="admin-fields two"><div class="admin-field"><label>Nom</label><input name="commission_name[]"></div><div class="admin-field"><label>Membres</label><input name="commission_members[]"></div><div class="admin-field"><label>E-mail</label><input type="email" name="commission_contact[]"></div><div class="admin-field"><label>Mission</label><textarea name="commission_mission[]" rows="3"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette commission">×</button></div></template></section>
<?php elseif ($type === 'partners'): ?>
    <section class="admin-form-card" data-collection="partners"><div class="admin-card-heading"><div><p class="admin-kicker">Pied de page</p><h2>Partenaires</h2><p>Ajoute un logo depuis l’ordinateur ; laisse le champ vide pour conserver le logo actuel.</p></div><button class="admin-button add" type="button" data-add-row="partners">+ Ajouter un partenaire</button></div><div class="admin-repeat-list stacked" data-rows="partners"><?php foreach ($partners as $partner): ?><div class="admin-repeat-row"><div class="admin-fields three"><div class="admin-field"><label>Nom</label><input name="partner_name[]" value="<?= e((string) ($partner['name'] ?? '')) ?>"></div><div class="admin-field"><label>Site web</label><input type="url" name="partner_url[]" value="<?= e((string) ($partner['url'] ?? '')) ?>"></div><div class="admin-field"><label>Logo</label><input type="file" name="partner_logo[]" accept="image/jpeg,image/png,image/webp"><input type="hidden" name="partner_logo_existing[]" value="<?= e((string) ($partner['logo'] ?? '')) ?>"></div><div class="admin-field full"><label>Description</label><input name="partner_description[]" value="<?= e((string) ($partner['description'] ?? '')) ?>"></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce partenaire">×</button></div><?php endforeach; ?></div><template data-template="partners"><div class="admin-repeat-row"><div class="admin-fields three"><div class="admin-field"><label>Nom</label><input name="partner_name[]"></div><div class="admin-field"><label>Site web</label><input type="url" name="partner_url[]"></div><div class="admin-field"><label>Logo</label><input type="file" name="partner_logo[]" accept="image/jpeg,image/png,image/webp"><input type="hidden" name="partner_logo_existing[]" value=""></div><div class="admin-field full"><label>Description</label><input name="partner_description[]"></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce partenaire">×</button></div></template></section>
<?php else: ?>
    <section class="admin-form-card" data-collection="compass-actors"><div class="admin-card-heading"><div><p class="admin-kicker">Boussole</p><h2>Les interlocuteurs</h2><p>L’identifiant est un mot court en majuscules, par exemple AS_AMU ou SUAPS.</p></div><button class="admin-button add" type="button" data-add-row="compass-actors">+ Ajouter un interlocuteur</button></div><div class="admin-repeat-list" data-rows="compass-actors"><?php foreach ($sportCompassActors as $id => $actor): ?><div class="admin-repeat-row"><div class="admin-field"><label>Identifiant</label><input name="actor_id[]" value="<?= e((string) $id) ?>"></div><div class="admin-field"><label>Nom affiché</label><input name="actor_name[]" value="<?= e((string) ($actor['name'] ?? '')) ?>"></div><div class="admin-field"><label>Description</label><input name="actor_description[]" value="<?= e((string) ($actor['description'] ?? '')) ?>"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cet interlocuteur">×</button></div><?php endforeach; ?></div><template data-template="compass-actors"><div class="admin-repeat-row"><div class="admin-field"><label>Identifiant</label><input name="actor_id[]" placeholder="AS_AMU"></div><div class="admin-field"><label>Nom affiché</label><input name="actor_name[]"></div><div class="admin-field"><label>Description</label><input name="actor_description[]"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cet interlocuteur">×</button></div></template></section>
    <section class="admin-form-card" data-collection="compass-categories"><div class="admin-card-heading"><div><p class="admin-kicker">Boussole</p><h2>Rubriques et propositions</h2><p>Une proposition par ligne, au format : <b>ACTEUR | question | titre | description | précision facultative | lien</b>.</p></div><button class="admin-button add" type="button" data-add-row="compass-categories">+ Ajouter une rubrique</button></div><div class="admin-repeat-list stacked" data-rows="compass-categories"><?php foreach ($sportCompass as $category): ?><div class="admin-repeat-row"><div class="admin-fields three"><div class="admin-field"><label>Identifiant</label><input name="compass_id[]" value="<?= e((string) ($category['id'] ?? '')) ?>"></div><div class="admin-field"><label>Emoji</label><input name="compass_emoji[]" value="<?= e((string) ($category['emoji'] ?? '')) ?>"></div><div class="admin-field"><label>Couleur</label><select name="compass_theme[]"><option value="green"<?= ($category['theme'] ?? '') === 'green' ? ' selected' : '' ?>>Vert</option><option value="blue"<?= ($category['theme'] ?? '') === 'blue' ? ' selected' : '' ?>>Bleu</option><option value="yellow"<?= ($category['theme'] ?? '') === 'yellow' ? ' selected' : '' ?>>Jaune</option><option value="purple"<?= ($category['theme'] ?? '') === 'purple' ? ' selected' : '' ?>>Violet</option></select></div><div class="admin-field"><label>Titre</label><input name="compass_title[]" value="<?= e((string) ($category['title'] ?? '')) ?>"></div><div class="admin-field"><label>Sous-titre</label><input name="compass_subtitle[]" value="<?= e((string) ($category['subtitle'] ?? '')) ?>"></div><div class="admin-field full"><label>Propositions (une par ligne)</label><textarea name="compass_options[]" rows="8"><?= e(admin_compass_option_text((array) ($category['options'] ?? []))) ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette rubrique">×</button></div><?php endforeach; ?></div><template data-template="compass-categories"><div class="admin-repeat-row"><div class="admin-fields three"><div class="admin-field"><label>Identifiant</label><input name="compass_id[]"></div><div class="admin-field"><label>Emoji</label><input name="compass_emoji[]"></div><div class="admin-field"><label>Couleur</label><select name="compass_theme[]"><option value="green">Vert</option><option value="blue">Bleu</option><option value="yellow">Jaune</option><option value="purple">Violet</option></select></div><div class="admin-field"><label>Titre</label><input name="compass_title[]"></div><div class="admin-field"><label>Sous-titre</label><input name="compass_subtitle[]"></div><div class="admin-field full"><label>Propositions (une par ligne)</label><textarea name="compass_options[]" rows="8" placeholder="AS_AMU | Ma question | Le titre | La description | Précision | https://"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette rubrique">×</button></div></template></section>
<?php endif; ?>
    <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer</button><button class="admin-button danger" type="submit" name="action" value="reset" data-confirm="Réinitialiser ce contenu avec la version d’origine ?">Réinitialiser cette rubrique</button></div>
</form>
<?php admin_page_end(); ?>
