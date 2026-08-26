<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('calendar');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $store = content_store_read();
        if (($_POST['action'] ?? '') === 'reset') {
            unset($store['collections']['competition_calendar']);
            $saved = content_store_write($store);
            admin_flash($saved ? 'success' : 'error', $saved ? 'Calendrier réinitialisé.' : admin_storage_error());
        } else {
            $fields = ['start_date', 'end_date', 'title', 'sport', 'level', 'status', 'place', 'section', 'description', 'registration_deadline', 'url', 'image_existing', 'image_alt'];
            $length = 0;
            foreach ($fields as $field) {
                $values = $_POST[$field] ?? [];
                $length = max($length, is_array($values) ? count($values) : 0);
            }
            $calendar = [];
            $errors = [];
            for ($index = 0; $index < $length; $index++) {
                $row = [];
                foreach ($fields as $field) {
                    $row[$field] = admin_text(is_array($_POST[$field] ?? null) ? ($_POST[$field][$index] ?? '') : '');
                }
                if ($row['title'] === '' && $row['start_date'] === '') {
                    continue;
                }
                if ($row['title'] === '' || $row['start_date'] === '') {
                    $errors[] = 'Chaque événement doit avoir au moins un titre et une date de début.';
                    continue;
                }
                $image = $row['image_existing'];
                $upload = [
                    'error' => $_FILES['calendar_image']['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                    'tmp_name' => $_FILES['calendar_image']['tmp_name'][$index] ?? '',
                    'size' => $_FILES['calendar_image']['size'][$index] ?? 0,
                ];
                $imageUpload = admin_store_image($upload, 'calendar', 'calendar');
                if ($imageUpload['error'] !== '') {
                    $errors[] = $imageUpload['error'];
                }
                if ($imageUpload['path'] !== '') {
                    $image = $imageUpload['path'];
                }
                $idBase = strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $row['title']) ?: $row['title']));
                $calendar[] = [
                    'id' => trim($idBase, '-') . '-' . $row['start_date'] . '-' . ($index + 1),
                    'start_date' => $row['start_date'], 'end_date' => $row['end_date'], 'title' => $row['title'],
                    'sport' => $row['sport'], 'level' => $row['level'], 'status' => $row['status'],
                    'place' => $row['place'], 'section' => $row['section'], 'description' => $row['description'],
                    'registration_deadline' => $row['registration_deadline'], 'url' => $row['url'],
                    'image' => $image, 'image_alt' => $row['image_alt'],
                ];
            }
            $store['collections']['competition_calendar'] = $calendar;
            $saved = content_store_write($store);
            admin_flash($saved ? 'success' : 'error', $saved ? (empty($errors) ? 'Calendrier enregistré.' : 'Calendrier enregistré, mais certaines affiches n’ont pas pu être ajoutées : ' . implode(' ', array_unique($errors))) : admin_storage_error());
        }
    }
    admin_redirect('calendar.php');
}

admin_page_start('Calendrier des compétitions', 'calendar.php');
?>
<section class="admin-form-intro"><p class="admin-kicker">Compétitions</p><h2>Publie les rendez-vous de la saison.</h2><p>Le calendrier du site affichera automatiquement les dates et alimentera ses filtres par sport, niveau et statut.</p></section>
<form method="post" enctype="multipart/form-data" class="admin-form">
    <?= admin_form_token() ?>
    <section class="admin-form-card" data-collection="calendar-events">
        <div class="admin-card-heading"><div><p class="admin-kicker">Événements publiés</p><h2><?= count($competitionCalendar) ?> compétition<?= count($competitionCalendar) > 1 ? 's' : '' ?></h2></div><button class="admin-button add" type="button" data-add-row="calendar-events">+ Ajouter une compétition</button></div>
        <div class="admin-repeat-list stacked" data-rows="calendar-events">
            <?php foreach ($competitionCalendar as $event): ?>
                <div class="admin-repeat-row admin-calendar-row"><div class="admin-fields three"><div class="admin-field"><label>Date de début</label><input type="date" name="start_date[]" value="<?= e((string) ($event['start_date'] ?? '')) ?>" required></div><div class="admin-field"><label>Date de fin (facultatif)</label><input type="date" name="end_date[]" value="<?= e((string) ($event['end_date'] ?? '')) ?>"></div><div class="admin-field"><label>Date limite d’inscription</label><input type="date" name="registration_deadline[]" value="<?= e((string) ($event['registration_deadline'] ?? '')) ?>"></div><div class="admin-field full"><label>Titre</label><input name="title[]" value="<?= e((string) ($event['title'] ?? '')) ?>" required></div><div class="admin-field"><label>Sport</label><input name="sport[]" value="<?= e((string) ($event['sport'] ?? '')) ?>"></div><div class="admin-field"><label>Niveau</label><input name="level[]" value="<?= e((string) ($event['level'] ?? '')) ?>" placeholder="National"></div><div class="admin-field"><label>Statut</label><input name="status[]" value="<?= e((string) ($event['status'] ?? '')) ?>" placeholder="À venir"></div><div class="admin-field"><label>Lieu</label><input name="place[]" value="<?= e((string) ($event['place'] ?? '')) ?>"></div><div class="admin-field"><label>Section / public</label><input name="section[]" value="<?= e((string) ($event['section'] ?? '')) ?>"></div><div class="admin-field"><label>Lien d’inscription / infos</label><input type="url" name="url[]" value="<?= e((string) ($event['url'] ?? '')) ?>" placeholder="https://"></div><div class="admin-field"><label>Affiche (JPG, PNG, WEBP)</label><input type="file" name="calendar_image[]" accept="image/jpeg,image/png,image/webp"><input type="hidden" name="image_existing[]" value="<?= e((string) ($event['image'] ?? '')) ?>"><?php if (!empty($event['image'])): ?><small>Affiche actuelle conservée si aucun fichier n’est choisi.</small><?php endif; ?></div><div class="admin-field"><label>Description de l’affiche</label><input name="image_alt[]" value="<?= e((string) ($event['image_alt'] ?? '')) ?>"></div><div class="admin-field full"><label>Description</label><textarea name="description[]" rows="3"><?= e((string) ($event['description'] ?? '')) ?></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cet événement">×</button></div>
            <?php endforeach; ?>
        </div>
        <template data-template="calendar-events"><div class="admin-repeat-row admin-calendar-row"><div class="admin-fields three"><div class="admin-field"><label>Date de début</label><input type="date" name="start_date[]" required></div><div class="admin-field"><label>Date de fin (facultatif)</label><input type="date" name="end_date[]"></div><div class="admin-field"><label>Date limite d’inscription</label><input type="date" name="registration_deadline[]"></div><div class="admin-field full"><label>Titre</label><input name="title[]" required></div><div class="admin-field"><label>Sport</label><input name="sport[]"></div><div class="admin-field"><label>Niveau</label><input name="level[]" placeholder="National"></div><div class="admin-field"><label>Statut</label><input name="status[]" placeholder="À venir"></div><div class="admin-field"><label>Lieu</label><input name="place[]"></div><div class="admin-field"><label>Section / public</label><input name="section[]"></div><div class="admin-field"><label>Lien d’inscription / infos</label><input type="url" name="url[]" placeholder="https://"></div><div class="admin-field"><label>Affiche (facultatif)</label><input type="file" name="calendar_image[]" accept="image/jpeg,image/png,image/webp"><input type="hidden" name="image_existing[]" value=""></div><div class="admin-field"><label>Description de l’affiche</label><input name="image_alt[]"></div><div class="admin-field full"><label>Description</label><textarea name="description[]" rows="3"></textarea></div></div><button class="admin-remove" type="button" data-remove-row aria-label="Supprimer cet événement">×</button></div></template>
    </section>
    <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer le calendrier</button><button class="admin-button danger" type="submit" name="action" value="reset" data-confirm="Réinitialiser le calendrier avec le contenu d’origine ?">Réinitialiser le calendrier</button></div>
</form>
<?php admin_page_end(); ?>
