<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('google_sheets');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $action = (string) ($_POST['action'] ?? 'save');
        $settings = google_sheets_read_settings();

        if ($action === 'generate-secret') {
            $settings['shared_secret'] = bin2hex(random_bytes(24));
            if (google_sheets_write_settings($settings)) {
                admin_flash('success', 'Nouveau code secret généré. Copie-le dans les propriétés du script Google.');
            } else {
                admin_flash('error', admin_storage_error());
            }
        } else {
            $settings['enabled'] = isset($_POST['enabled']);
            $settings['sheet_url'] = admin_text($_POST['sheet_url'] ?? '');
            $settings['shared_secret'] = admin_text($_POST['shared_secret'] ?? '');
            if ($settings['enabled'] && $settings['shared_secret'] === '') {
                admin_flash('error', 'Pour activer Google Sheets, génère ou renseigne un code secret.');
            } elseif ($settings['sheet_url'] !== '' && !filter_var($settings['sheet_url'], FILTER_VALIDATE_URL)) {
                admin_flash('error', 'Le lien du Google Sheet doit être une URL complète.');
            } elseif (google_sheets_write_settings($settings)) {
                admin_flash('success', $settings['enabled'] ? 'Réception des résultats Google Sheets activée.' : 'Réception Google Sheets désactivée.');
            } else {
                admin_flash('error', admin_storage_error());
            }
        }
    }
    admin_redirect('google-sheets.php');
}

$settings = google_sheets_read_settings();
$lastSync = $settings['last_sync_at'] !== '' ? date('d/m/Y à H:i', strtotime((string) $settings['last_sync_at'])) : 'Jamais';
$syncUrl = google_sheets_site_sync_url();
admin_page_start('Google Sheets', 'google-sheets.php');
?>
<section class="admin-form-intro">
    <p class="admin-kicker">Document de référence</p>
    <h2>Le Google Sheet met directement le palmarès du site à jour.</h2>
    <p>Le script ne lit que la feuille <b>NATIONAL</b> et les colonnes SPORT, CHAMPIONNATS, NOMS, PRENOMS, CATEGORIE et RÉSULTATS / NIVEAU. Les informations de budget, de déplacement et de contact ne quittent pas le tableur.</p>
</section>

<section class="admin-google-status<?= !empty($settings['enabled']) ? ' is-active' : '' ?>">
    <span><?= !empty($settings['enabled']) ? '●' : '○' ?></span>
    <div><strong><?= !empty($settings['enabled']) ? 'Réception Google Sheets activée' : 'Réception Google Sheets non configurée' ?></strong><p>Dernière mise à jour du site : <?= e($lastSync) ?><?= $settings['last_source'] !== '' ? ' · ' . e((string) $settings['last_source']) : '' ?></p></div>
    <?php if ($settings['last_error'] !== ''): ?><small>Dernier message : <?= e((string) $settings['last_error']) ?></small><?php endif; ?>
</section>

<div class="admin-google-grid">
    <form method="post" class="admin-form">
        <?= admin_form_token() ?>
        <section class="admin-form-card">
            <div class="admin-card-heading"><div><p class="admin-kicker">Connexion</p><h2>Relier le tableur de résultats</h2></div></div>
            <div class="admin-fields">
                <label class="admin-switch"><input type="checkbox" name="enabled"<?= !empty($settings['enabled']) ? ' checked' : '' ?>><span></span> Autoriser le Google Sheet à actualiser le site</label>
                <div class="admin-field"><label for="sheet_url">Lien du Google Sheet</label><input id="sheet_url" type="url" name="sheet_url" value="<?= e((string) $settings['sheet_url']) ?>" placeholder="https://docs.google.com/spreadsheets/d/.../edit"><small>Facultatif pour la synchronisation, mais pratique pour ouvrir rapidement le document de référence.</small></div>
                <div class="admin-field"><label for="shared_secret">Code secret</label><input id="shared_secret" name="shared_secret" value="<?= e((string) $settings['shared_secret']) ?>" autocomplete="off"><small>Il doit être identique à la propriété <b>SHARED_SECRET</b> du script Google.</small></div>
                <div class="admin-field"><label for="site_sync_url">Adresse de synchronisation du site</label><input id="site_sync_url" value="<?= e($syncUrl) ?>" readonly onclick="this.select()"><small>Copie cette adresse dans la propriété <b>SITE_SYNC_URL</b> du script Google.</small></div>
            </div>
        </section>
        <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer la connexion</button><button class="admin-button secondary" type="submit" name="action" value="generate-secret">Générer un code secret</button><?php if ($settings['sheet_url'] !== ''): ?><a class="admin-button secondary" href="<?= e((string) $settings['sheet_url']) ?>" target="_blank" rel="noopener">Ouvrir le tableur ↗</a><?php endif; ?></div>
    </form>

    <aside class="admin-google-steps">
        <p class="admin-kicker">Configuration à faire une fois</p>
        <h2>Relier le budget national au site</h2>
        <ol>
            <li>Active la réception ici et génère le code secret.</li>
            <li>Dans le Google Sheet, ouvre <b>Extensions → Apps Script</b>.</li>
            <li>Copie le script AS amU, puis ajoute <b>SITE_SYNC_URL</b>, <b>SHARED_SECRET</b> et <b>DEFAULT_SEASON</b> dans les propriétés du script.</li>
            <li>Exécute <b>installAutomaticSync</b> une seule fois, accepte les autorisations Google, puis lance <b>syncNow</b> pour le premier essai.</li>
        </ol>
        <a class="admin-button secondary" href="../google-sheets/Code.gs" target="_blank" rel="noopener">Ouvrir le script à copier</a>
        <p class="admin-google-note">Aucun déploiement Apps Script n’est nécessaire. Après l’installation, chaque modification de l’onglet NATIONAL actualise les podiums du site.</p>
    </aside>
</div>

<section class="admin-tip admin-google-local-note"><strong>Essai local</strong><p>Google ne peut pas joindre <b>localhost</b>. Sur XAMPP, la réception automatique attend une URL publique HTTPS ; elle fonctionnera dès que le site sera hébergé (ou avec un tunnel public temporaire).</p></section>
<?php admin_page_end(); ?>
