<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('settings');

/** @return array<int, array{name: string, role: string, initials: string}> */
function admin_board_members(string $value): array
{
    $members = [];
    foreach (preg_split('/\R/u', trim($value)) ?: [] as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (($parts[0] ?? '') === '') {
            continue;
        }
        $initials = $parts[2] ?? '';
        if ($initials === '') {
            $words = preg_split('/\s+/u', $parts[0]) ?: [];
            $initials = strtoupper(implode('', array_map(static fn (string $word): string => mb_substr($word, 0, 1), $words)));
        }
        $members[] = ['name' => $parts[0], 'role' => $parts[1] ?? '', 'initials' => $initials];
    }
    return $members;
}

function admin_board_member_text(array $members): string
{
    return implode("\n", array_map(static fn (array $member): string => implode(' | ', [
        admin_text($member['name'] ?? ''), admin_text($member['role'] ?? ''), admin_text($member['initials'] ?? ''),
    ]), $members));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        if (($_POST['action'] ?? '') === 'reset') {
            $store['site'] = [];
            $store['association_stats'] = null;
            unset($store['collections']['association_board']);
            $message = 'Les informations générales ont été réinitialisées.';
        } else {
            $fields = ['name', 'full_name', 'tagline', 'season', 'address', 'phone', 'email', 'competition_email', 'treasury_email', 'communication_email', 'helloasso_url', 'mysportu_url', 'instagram_url', 'membership_price', 'license_price', 'site_url', 'social_image', 'president_name', 'president_title', 'president_photo', 'president_word', 'legal_status', 'rna_number', 'siren_number', 'siret_number', 'privacy_email', 'host_name', 'host_company', 'host_address', 'host_phone'];
            $store['site'] = [];
            foreach ($fields as $field) {
                $store['site'][$field] = admin_text($_POST[$field] ?? '');
            }
            $store['site']['president_word'] = rich_text_sanitize($store['site']['president_word']);
            $store['association_stats'] = admin_post_rows(['stat_number', 'stat_label']);
            $store['association_stats'] = array_map(static fn (array $row): array => ['number' => $row['stat_number'], 'label' => $row['stat_label']], $store['association_stats']);
            $store['site']['home_stats'] = admin_post_rows(['home_stat_number', 'home_stat_label', 'home_stat_note']);
            $store['site']['home_stats'] = array_map(static fn (array $row): array => ['number' => $row['home_stat_number'], 'label' => $row['home_stat_label'], 'note' => $row['home_stat_note']], $store['site']['home_stats']);

            $poleRows = admin_post_rows(['pole_name', 'pole_accent', 'pole_members']);
            $poles = [];
            foreach ($poleRows as $pole) {
                if ($pole['pole_name'] === '') {
                    continue;
                }
                $poles[] = ['name' => $pole['pole_name'], 'accent' => in_array($pole['pole_accent'], ['yellow', 'blue', 'green'], true) ? $pole['pole_accent'] : 'blue', 'members' => admin_board_members($pole['pole_members'])];
            }
            $presidentName = admin_text($_POST['board_president_name'] ?? '');
            $presidentInitials = admin_text($_POST['board_president_initials'] ?? '');
            if ($presidentInitials === '') {
                $presidentInitials = strtoupper(implode('', array_map(static fn (string $word): string => mb_substr($word, 0, 1), preg_split('/\s+/u', $presidentName) ?: [])));
            }
            $store['collections']['association_board'] = [
                'year' => admin_text($_POST['board_year'] ?? ''),
                'contact' => admin_text($_POST['board_contact'] ?? ''),
                'president' => ['name' => $presidentName, 'role' => admin_text($_POST['board_president_role'] ?? ''), 'initials' => $presidentInitials],
                'poles' => $poles,
            ];
            $message = 'Informations générales enregistrées.';
        }

        if (content_store_write($store)) {
            admin_flash('success', $message);
        } else {
            admin_flash('error', admin_storage_error());
        }
    }
    admin_redirect('settings.php');
}

admin_page_start('Informations du site', 'settings.php');
?>
<section class="admin-form-intro"><p class="admin-kicker">Informations générales</p><h2>Les coordonnées et chiffres de l’AS amU.</h2><p>Ces informations alimentent les boutons, les contacts, le pied de page et la page Organisation.</p></section>
<form method="post" class="admin-form">
    <?= admin_form_token() ?>
    <section class="admin-form-card"><div class="admin-card-heading"><div><p class="admin-kicker">Association</p><h2>Coordonnées et liens</h2></div></div><div class="admin-fields two"><div class="admin-field"><label>Nom court</label><input name="name" value="<?= e($site['name']) ?>"></div><div class="admin-field"><label>Nom complet</label><input name="full_name" value="<?= e($site['full_name']) ?>"></div><div class="admin-field"><label>Slogan</label><input name="tagline" value="<?= e($site['tagline']) ?>"></div><div class="admin-field"><label>Saison</label><input name="season" value="<?= e($site['season']) ?>" placeholder="2026/2027"></div><div class="admin-field full"><label>Adresse postale</label><textarea name="address" rows="2"><?= e($site['address']) ?></textarea></div><div class="admin-field"><label>Téléphone</label><input name="phone" value="<?= e($site['phone']) ?>"></div><div class="admin-field"><label>E-mail général</label><input type="email" name="email" value="<?= e($site['email']) ?>"></div><div class="admin-field"><label>E-mail compétitions</label><input type="email" name="competition_email" value="<?= e($site['competition_email']) ?>"></div><div class="admin-field"><label>E-mail trésorerie</label><input type="email" name="treasury_email" value="<?= e($site['treasury_email']) ?>"></div><div class="admin-field"><label>E-mail communication</label><input type="email" name="communication_email" value="<?= e($site['communication_email']) ?>"></div><div class="admin-field"><label>Adhésion HelloAsso</label><input type="url" name="helloasso_url" value="<?= e($site['helloasso_url']) ?>"></div><div class="admin-field"><label>MySportU</label><input type="url" name="mysportu_url" value="<?= e($site['mysportu_url']) ?>"></div><div class="admin-field"><label>Instagram</label><input type="url" name="instagram_url" value="<?= e($site['instagram_url']) ?>"></div><div class="admin-field"><label>Tarif adhésion</label><input name="membership_price" value="<?= e($site['membership_price']) ?>"></div><div class="admin-field"><label>Tarif licence</label><input name="license_price" value="<?= e($site['license_price']) ?>"></div><div class="admin-field"><label>Adresse du site</label><input type="url" name="site_url" value="<?= e($site['site_url']) ?>"></div><div class="admin-field full"><label>Image de partage (réseaux sociaux)</label><input name="social_image" value="<?= e((string) ($site['social_image'] ?? '')) ?>" placeholder="https://… ou assets/img/…"></div></div></section>
    <section class="admin-form-card"><div class="admin-card-heading"><div><p class="admin-kicker">Informations légales</p><h2>Association, confidentialité et hébergement</h2><p>Ces champs alimentent automatiquement les pages légales du site.</p></div></div><div class="admin-fields two"><div class="admin-field full"><label>Forme juridique</label><input name="legal_status" value="<?= e((string) ($site['legal_status'] ?? '')) ?>"></div><div class="admin-field"><label>Numéro RNA</label><input name="rna_number" value="<?= e((string) ($site['rna_number'] ?? '')) ?>"></div><div class="admin-field"><label>Numéro SIREN</label><input name="siren_number" value="<?= e((string) ($site['siren_number'] ?? '')) ?>"></div><div class="admin-field"><label>Numéro SIRET</label><input name="siret_number" value="<?= e((string) ($site['siret_number'] ?? '')) ?>"></div><div class="admin-field"><label>E-mail pour les droits RGPD</label><input type="email" name="privacy_email" value="<?= e((string) ($site['privacy_email'] ?? '')) ?>"></div><div class="admin-field"><label>Nom commercial de l’hébergeur</label><input name="host_name" value="<?= e((string) ($site['host_name'] ?? '')) ?>" placeholder="À compléter avant publication"></div><div class="admin-field"><label>Raison sociale de l’hébergeur</label><input name="host_company" value="<?= e((string) ($site['host_company'] ?? '')) ?>"></div><div class="admin-field full"><label>Adresse de l’hébergeur</label><textarea name="host_address" rows="2"><?= e((string) ($site['host_address'] ?? '')) ?></textarea></div><div class="admin-field"><label>Téléphone de l’hébergeur</label><input name="host_phone" value="<?= e((string) ($site['host_phone'] ?? '')) ?>"></div></div></section>
    <section class="admin-form-card"><div class="admin-card-heading"><div><p class="admin-kicker">Mot du président</p><h2>Signature de l’accueil</h2></div></div><div class="admin-fields two"><div class="admin-field"><label>Nom</label><input name="president_name" value="<?= e((string) $site['president_name']) ?>"></div><div class="admin-field"><label>Fonction</label><input name="president_title" value="<?= e((string) $site['president_title']) ?>"></div><div class="admin-field full"><label>Chemin ou URL de la photo</label><input name="president_photo" value="<?= e((string) $site['president_photo']) ?>"><small>Exemple : assets/img/president.jpg</small></div><div class="admin-field full"><label>Texte</label><textarea name="president_word" rows="8" data-rich-editor><?= e((string) $site['president_word']) ?></textarea><small>Mise en forme visuelle disponible : gras, italique, liste et lien. L’aperçu est mis à jour immédiatement.</small></div></div></section>
    <section class="admin-form-card" data-collection="association-stats"><div class="admin-card-heading"><div><p class="admin-kicker">Chiffres clés</p><h2>Page Organisation</h2></div><button class="admin-button add" type="button" data-add-row="association-stats">+ Ajouter un chiffre</button></div><div class="admin-repeat-list" data-rows="association-stats"><?php foreach ($associationStats as $stat): ?><div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="stat_number[]" value="<?= e((string) ($stat['number'] ?? '')) ?>"></div><div class="admin-field"><label>Libellé</label><input name="stat_label[]" value="<?= e((string) ($stat['label'] ?? '')) ?>"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div><?php endforeach; ?></div><template data-template="association-stats"><div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="stat_number[]"></div><div class="admin-field"><label>Libellé</label><input name="stat_label[]"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div></template></section>
    <section class="admin-form-card" data-collection="home-stats"><div class="admin-card-heading"><div><p class="admin-kicker">Accueil</p><h2>Chiffres affichés sur l’accueil</h2></div><button class="admin-button add" type="button" data-add-row="home-stats">+ Ajouter un chiffre</button></div><div class="admin-repeat-list" data-rows="home-stats"><?php foreach (($site['home_stats'] ?? []) as $stat): ?><div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="home_stat_number[]" value="<?= e((string) ($stat['number'] ?? '')) ?>"></div><div class="admin-field"><label>Libellé</label><input name="home_stat_label[]" value="<?= e((string) ($stat['label'] ?? '')) ?>"></div><div class="admin-field"><label>Précision</label><input name="home_stat_note[]" value="<?= e((string) ($stat['note'] ?? '')) ?>" placeholder="Ex. Chiffres 2025-2026"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div><?php endforeach; ?></div><template data-template="home-stats"><div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="home_stat_number[]"></div><div class="admin-field"><label>Libellé</label><input name="home_stat_label[]"></div><div class="admin-field"><label>Précision</label><input name="home_stat_note[]" placeholder="Ex. Chiffres 2025-2026"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div></template></section>
    <section class="admin-form-card" data-collection="board-poles"><div class="admin-card-heading"><div><p class="admin-kicker">Bureau AS amU</p><h2>Le collectif</h2><p>Sur le site, chaque personne est présentée au même niveau. Les couleurs apportent simplement du rythme. Pour chaque groupe, ajoute un membre par ligne : <b>Prénom NOM | fonction | initiales</b>.</p></div><button class="admin-button add" type="button" data-add-row="board-poles">+ Ajouter un groupe</button></div><div class="admin-fields three"><div class="admin-field"><label>Année</label><input name="board_year" value="<?= e((string) ($associationBoard['year'] ?? '')) ?>"></div><div class="admin-field"><label>E-mail du bureau</label><input type="email" name="board_contact" value="<?= e((string) ($associationBoard['contact'] ?? '')) ?>"></div><div class="admin-field"><label>Membre du collectif</label><input name="board_president_name" value="<?= e((string) ($associationBoard['president']['name'] ?? '')) ?>"></div><div class="admin-field"><label>Fonction</label><input name="board_president_role" value="<?= e((string) ($associationBoard['president']['role'] ?? '')) ?>"></div><div class="admin-field"><label>Initiales</label><input name="board_president_initials" value="<?= e((string) ($associationBoard['president']['initials'] ?? '')) ?>"></div></div><div class="admin-repeat-list stacked" data-rows="board-poles"><?php foreach (($associationBoard['poles'] ?? []) as $pole): ?><div class="admin-repeat-row admin-pole-row"><div class="admin-fields three"><div class="admin-field"><label>Nom du groupe</label><input name="pole_name[]" value="<?= e((string) ($pole['name'] ?? '')) ?>"></div><div class="admin-field"><label>Couleur</label><select name="pole_accent[]"><option value="yellow"<?= ($pole['accent'] ?? '') === 'yellow' ? ' selected' : '' ?>>Jaune</option><option value="blue"<?= ($pole['accent'] ?? '') === 'blue' ? ' selected' : '' ?>>Bleu</option><option value="green"<?= ($pole['accent'] ?? '') === 'green' ? ' selected' : '' ?>>Vert</option></select></div><div class="admin-field"><label>Membres (un par ligne)</label><textarea name="pole_members[]" rows="5"><?= e(admin_board_member_text((array) ($pole['members'] ?? []))) ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce groupe">×</button></div><?php endforeach; ?></div><template data-template="board-poles"><div class="admin-repeat-row admin-pole-row"><div class="admin-fields three"><div class="admin-field"><label>Nom du groupe</label><input name="pole_name[]"></div><div class="admin-field"><label>Couleur</label><select name="pole_accent[]"><option value="yellow">Jaune</option><option value="blue">Bleu</option><option value="green">Vert</option></select></div><div class="admin-field"><label>Membres (un par ligne)</label><textarea name="pole_members[]" rows="5" placeholder="Prénom NOM | fonction | initiales"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce groupe">×</button></div></template></section>
    <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer les informations</button><button class="admin-button danger" type="submit" name="action" value="reset" data-confirm="Réinitialiser toutes les informations de cette page ?">Réinitialiser</button></div>
</form>
<?php admin_page_end(); ?>
