<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('coaches');

/** @return array<int, array{name: string, email: string, note?: string}> */
function admin_coach_contacts(string $value): array
{
    $contacts = [];
    foreach (preg_split('/\R/u', trim($value)) ?: [] as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (($parts[0] ?? '') === '') {
            continue;
        }
        $contact = ['name' => $parts[0], 'email' => $parts[1] ?? ''];
        if (($parts[2] ?? '') !== '') {
            $contact['note'] = $parts[2];
        }
        $contacts[] = $contact;
    }
    return $contacts;
}

function admin_coach_contact_text(array $contacts): string
{
    $lines = [];
    foreach ($contacts as $contact) {
        $lines[] = implode(' | ', array_filter([
            admin_text($contact['name'] ?? ''), admin_text($contact['email'] ?? ''), admin_text($contact['note'] ?? ''),
        ], static fn (string $item): bool => $item !== ''));
    }
    return implode("\n", $lines);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        if (($_POST['action'] ?? '') === 'reset') {
            unset($store['collections']['coaches'], $store['collections']['coach_info'], $store['collections']['coach_stats']);
            $message = 'Les contenus des coachs ont été réinitialisés.';
        } else {
            $rows = admin_post_rows(['coach_sport', 'coach_contacts']);
            $newCoaches = [];
            foreach ($rows as $row) {
                if ($row['coach_sport'] === '') {
                    continue;
                }
                $newCoaches[] = ['sport' => $row['coach_sport'], 'contacts' => admin_coach_contacts($row['coach_contacts'])];
            }
            $store['collections']['coaches'] = $newCoaches;
            $store['collections']['coach_info'] = [
                'intro' => admin_text($_POST['intro'] ?? ''),
                'warning' => admin_text($_POST['warning'] ?? ''),
                'sports_without_coach' => admin_post_lines((string) ($_POST['sports_without_coach'] ?? '')),
                'sports_with_coach' => admin_post_lines((string) ($_POST['sports_with_coach'] ?? '')),
            ];
            $coachStatsRows = admin_post_rows(['coach_stat_number', 'coach_stat_label', 'coach_stat_description']);
            $store['collections']['coach_stats'] = array_map(static fn (array $stat): array => [
                'number' => $stat['coach_stat_number'], 'label' => $stat['coach_stat_label'], 'description' => $stat['coach_stat_description'],
            ], $coachStatsRows);
            $message = 'Liste des coachs enregistrée.';
        }
        if (content_store_write($store)) {
            admin_flash('success', $message);
        } else {
            admin_flash('error', admin_storage_error());
        }
    }
    admin_redirect('coaches.php');
}

admin_page_start('Coachs sportifs', 'coaches.php');
?>
<section class="admin-form-intro"><p class="admin-kicker">Encadrement sportif</p><h2>Gère les disciplines et les contacts.</h2><p>Pour chaque sport, indique un contact par ligne au format : <b>Prénom NOM | e-mail | précision facultative</b>.</p></section>
<form method="post" class="admin-form">
    <?= admin_form_token() ?>
    <section class="admin-form-card"><div class="admin-card-heading"><div><p class="admin-kicker">Présentation</p><h2>Informations affichées sur la page</h2></div><a href="../coachs.php" target="_blank" rel="noopener">Voir la page ↗</a></div><div class="admin-fields"><div class="admin-field"><label for="intro">Texte d’introduction</label><textarea id="intro" name="intro" rows="4"><?= e((string) ($coachInfo['intro'] ?? '')) ?></textarea></div><div class="admin-field"><label for="warning">Information importante</label><textarea id="warning" name="warning" rows="3"><?= e((string) ($coachInfo['warning'] ?? '')) ?></textarea></div><div class="admin-fields two"><div class="admin-field"><label for="sports_with_coach">Sports avec coach (un par ligne)</label><textarea id="sports_with_coach" name="sports_with_coach" rows="8"><?php foreach (($coachInfo['sports_with_coach'] ?? []) as $sport): ?><?= e((string) $sport) ?>
<?php endforeach; ?></textarea></div><div class="admin-field"><label for="sports_without_coach">Sports sans coach identifié (un par ligne)</label><textarea id="sports_without_coach" name="sports_without_coach" rows="8"><?php foreach (($coachInfo['sports_without_coach'] ?? []) as $sport): ?><?= e((string) $sport) ?>
<?php endforeach; ?></textarea></div></div></div></section>
    <section class="admin-form-card" data-collection="coaches"><div class="admin-card-heading"><div><p class="admin-kicker">Annuaire</p><h2>Coach par discipline</h2></div><button class="admin-button add" type="button" data-add-row="coaches">+ Ajouter une discipline</button></div><div class="admin-repeat-list stacked" data-rows="coaches"><?php foreach ($coaches as $coach): ?><div class="admin-repeat-row admin-coach-row"><div class="admin-fields two"><div class="admin-field"><label>Sport</label><input name="coach_sport[]" value="<?= e((string) ($coach['sport'] ?? '')) ?>" required></div><div class="admin-field"><label>Contacts (un par ligne)</label><textarea name="coach_contacts[]" rows="4" placeholder="Prénom NOM | email@amu.fr | précision"><?= e(admin_coach_contact_text((array) ($coach['contacts'] ?? []))) ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette discipline">×</button></div><?php endforeach; ?></div><template data-template="coaches"><div class="admin-repeat-row admin-coach-row"><div class="admin-fields two"><div class="admin-field"><label>Sport</label><input name="coach_sport[]" required></div><div class="admin-field"><label>Contacts (un par ligne)</label><textarea name="coach_contacts[]" rows="4" placeholder="Prénom NOM | email@amu.fr | précision"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cette discipline">×</button></div></template></section>
    <section class="admin-form-card" data-collection="coach-stats"><div class="admin-card-heading"><div><p class="admin-kicker">Chiffres optionnels</p><h2>Chiffres de la page coachs</h2></div><button class="admin-button add" type="button" data-add-row="coach-stats">+ Ajouter un chiffre</button></div><div class="admin-repeat-list" data-rows="coach-stats"><?php foreach ($coachStats as $stat): ?><div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="coach_stat_number[]" value="<?= e((string) ($stat['number'] ?? '')) ?>"></div><div class="admin-field"><label>Libellé</label><input name="coach_stat_label[]" value="<?= e((string) ($stat['label'] ?? '')) ?>"></div><div class="admin-field"><label>Description</label><input name="coach_stat_description[]" value="<?= e((string) ($stat['description'] ?? '')) ?>"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div><?php endforeach; ?></div><template data-template="coach-stats"><div class="admin-repeat-row"><div class="admin-field"><label>Chiffre</label><input name="coach_stat_number[]"></div><div class="admin-field"><label>Libellé</label><input name="coach_stat_label[]"></div><div class="admin-field"><label>Description</label><input name="coach_stat_description[]"></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer ce chiffre">×</button></div></template></section>
    <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer les coachs</button><button class="admin-button danger" type="submit" name="action" value="reset" data-confirm="Réinitialiser toute la liste des coachs ?">Réinitialiser</button></div>
</form>
<?php admin_page_end(); ?>
