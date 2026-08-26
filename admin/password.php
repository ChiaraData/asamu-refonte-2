<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $current = (string) ($_POST['current_password'] ?? '');
        $new = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['confirm_password'] ?? '');
        if (mb_strlen($new) < 12) {
            admin_flash('error', 'Le nouveau mot de passe doit contenir au moins 12 caractères.');
        } elseif ($new !== $confirm) {
            admin_flash('error', 'Les deux nouveaux mots de passe ne correspondent pas.');
        } elseif (admin_update_current_user_password($current, $new)) {
            admin_flash('success', 'Mot de passe modifié.');
        } else {
            admin_flash('error', 'Le mot de passe actuel est incorrect ou l’enregistrement est impossible.');
        }
    }
    admin_redirect('password.php');
}

admin_page_start('Changer le mot de passe');
?>
<section class="admin-form-intro"><p class="admin-kicker">Sécurité</p><h2>Choisis un mot de passe personnel.</h2><p>Il protège l’ensemble des contenus modifiables du site.</p></section>
<form method="post" class="admin-form admin-password-form">
    <?= admin_form_token() ?>
    <div class="admin-field"><label for="current_password">Mot de passe actuel</label><input id="current_password" type="password" name="current_password" autocomplete="current-password" required></div>
    <div class="admin-field"><label for="new_password">Nouveau mot de passe</label><input id="new_password" type="password" name="new_password" autocomplete="new-password" minlength="12" required><small>Au moins 12 caractères.</small></div>
    <div class="admin-field"><label for="confirm_password">Confirmer le nouveau mot de passe</label><input id="confirm_password" type="password" name="confirm_password" autocomplete="new-password" minlength="12" required></div>
    <div class="admin-form-actions"><button class="admin-button primary" type="submit">Enregistrer le mot de passe</button></div>
</form>
<?php admin_page_end(); ?>
