<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('palmares');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        if (($_POST['action'] ?? '') === 'reset') {
            $store['palmares'] = null;
            $message = 'Le palmarès a retrouvé son contenu d’origine.';
        } else {
            $rows = admin_post_rows(['season', 'sport', 'competition', 'team_status', 'team_name', 'last_name', 'first_name', 'result', 'place']);
            $podiums = [];
            foreach ($rows as $row) {
                if ($row['sport'] === '' || $row['result'] === '') {
                    continue;
                }
                $place = (int) $row['place'];
                if (!in_array($place, [1, 2, 3], true)) {
                    continue;
                }
                $entry = [
                    'season' => $row['season'],
                    'sport' => $row['sport'],
                    'competition' => $row['competition'],
                    'result' => $row['result'],
                    'place' => $place,
                ];
                if ($row['team_status'] === 'team') {
                    $entry['team'] = true;
                    if ($row['team_name'] !== '') {
                        $entry['team_name'] = $row['team_name'];
                    }
                } else {
                    $entry['last_name'] = $row['last_name'];
                    $entry['first_name'] = $row['first_name'];
                }
                $podiums[] = $entry;
            }
            $store['palmares'] = $podiums;
            $message = 'Palmarès enregistré.';
        }
        if (content_store_write($store)) {
            admin_flash('success', $message);
            if (!empty(google_sheets_read_settings()['enabled'])) {
                admin_flash('info', 'Le Google Sheet est le document de référence : sa prochaine synchronisation remplacera cette saisie manuelle.');
            }
        } else {
            admin_flash('error', admin_storage_error());
        }
    }
    admin_redirect('palmares.php');
}

admin_page_start('Palmarès', 'palmares.php');
?>
<section class="admin-form-intro"><p class="admin-kicker">Podiums nationaux</p><h2>Valorise chaque médaille, par saison et par sport.</h2><p>Le Google Sheet est le document de référence : ses podiums sont récupérés automatiquement depuis la feuille <b>NATIONAL</b>. Cette saisie manuelle reste disponible uniquement comme solution de secours.</p><p><a class="admin-inline-link" href="google-sheets.php">Configurer la synchronisation Google Sheets →</a></p></section>

<form method="post" class="admin-form">
    <?= admin_form_token() ?>
    <section class="admin-form-card" data-collection="podiums">
        <div class="admin-card-heading"><div><p class="admin-kicker">Résultats</p><h2>Les podiums publiés</h2><p><?= count($palmares) ?> résultat<?= count($palmares) > 1 ? 's' : '' ?> dans la liste actuelle.</p></div><button class="admin-button add" type="button" data-add-row="podiums">+ Ajouter un podium</button></div>
        <div class="admin-podium-head admin-podium-row"><span>Saison</span><span>Sport</span><span>Compétition</span><span>Représentation</span><span>Nom de l’équipe</span><span>Nom</span><span>Prénom</span><span>Résultat</span><span>Place</span><i></i></div>
        <div class="admin-repeat-list admin-podium-list" data-rows="podiums">
            <?php foreach ($palmares as $entry): ?>
                <div class="admin-podium-row"><div class="admin-field"><label>Saison</label><input name="season[]" value="<?= e((string) ($entry['season'] ?? '2025/2026')) ?>" placeholder="2025/2026"></div><div class="admin-field"><label>Sport</label><input name="sport[]" value="<?= e((string) ($entry['sport'] ?? '')) ?>" required></div><div class="admin-field"><label>Compétition</label><input name="competition[]" value="<?= e((string) ($entry['competition'] ?? '')) ?>" placeholder="CFDU"></div><div class="admin-field"><label>Représentation</label><select name="team_status[]"><option value="athlete"<?= empty($entry['team']) ? ' selected' : '' ?>>Athlète</option><option value="team"<?= !empty($entry['team']) ? ' selected' : '' ?>>Équipe AS amU</option></select></div><div class="admin-field"><label>Nom de l’équipe</label><input name="team_name[]" value="<?= e((string) ($entry['team_name'] ?? '')) ?>" placeholder="Eagles"></div><div class="admin-field"><label>Nom</label><input name="last_name[]" value="<?= e((string) ($entry['last_name'] ?? '')) ?>"></div><div class="admin-field"><label>Prénom</label><input name="first_name[]" value="<?= e((string) ($entry['first_name'] ?? '')) ?>"></div><div class="admin-field"><label>Résultat</label><input name="result[]" value="<?= e((string) ($entry['result'] ?? '')) ?>" required placeholder="Champion de France"></div><div class="admin-field"><label>Place</label><select name="place[]"><option value="1"<?= (int) ($entry['place'] ?? 0) === 1 ? ' selected' : '' ?>>Or · 1</option><option value="2"<?= (int) ($entry['place'] ?? 0) === 2 ? ' selected' : '' ?>>Argent · 2</option><option value="3"<?= (int) ($entry['place'] ?? 0) === 3 ? ' selected' : '' ?>>Bronze · 3</option></select></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce podium">×</button></div>
            <?php endforeach; ?>
        </div>
        <template data-template="podiums"><div class="admin-podium-row"><div class="admin-field"><label>Saison</label><input name="season[]" value="<?= e($site['season']) ?>" placeholder="2025/2026"></div><div class="admin-field"><label>Sport</label><input name="sport[]" required></div><div class="admin-field"><label>Compétition</label><input name="competition[]" placeholder="CFDU"></div><div class="admin-field"><label>Représentation</label><select name="team_status[]"><option value="athlete">Athlète</option><option value="team">Équipe AS amU</option></select></div><div class="admin-field"><label>Nom de l’équipe</label><input name="team_name[]" placeholder="Eagles"></div><div class="admin-field"><label>Nom</label><input name="last_name[]"></div><div class="admin-field"><label>Prénom</label><input name="first_name[]"></div><div class="admin-field"><label>Résultat</label><input name="result[]" required placeholder="Champion de France"></div><div class="admin-field"><label>Place</label><select name="place[]"><option value="1">Or · 1</option><option value="2">Argent · 2</option><option value="3">Bronze · 3</option></select></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce podium">×</button></div></template>
    </section>
    <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer le palmarès</button><button class="admin-button danger" type="submit" name="action" value="reset" data-confirm="Réinitialiser tous les résultats avec le contenu d’origine ?">Réinitialiser le palmarès</button></div>
</form>
<?php admin_page_end(); ?>
