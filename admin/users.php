<?php
declare(strict_types=1);

require_once __DIR__ . '/_layout.php';
admin_require_permission('users');

$roles = admin_role_definitions();
$account = admin_account_read();
$users = (array) ($account['users'] ?? []);
$editId = admin_text($_GET['edit'] ?? $_POST['user_id'] ?? '');
$editingUser = $editId !== '' ? admin_find_user($editId) : null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!admin_verify_csrf()) {
        admin_flash('error', 'La page a expiré. Recharge-la puis réessaie.');
    } else {
        $action = admin_text($_POST['action'] ?? 'save');
        $userId = admin_text($_POST['user_id'] ?? '');
        $currentUser = admin_current_user();
        $existingUser = $userId !== '' ? admin_find_user($userId) : null;

        if ($action === 'deactivate') {
            if (!$existingUser || ($currentUser['id'] ?? '') === ($existingUser['id'] ?? '')) {
                admin_flash('error', 'Tu ne peux pas désactiver ton propre compte.');
            } else {
                $activeOwners = array_filter($users, static fn (array $user): bool => !empty($user['active']) && ($user['role'] ?? '') === 'owner');
                if (($existingUser['role'] ?? '') === 'owner' && count($activeOwners) <= 1) {
                    admin_flash('error', 'Au moins un compte propriétaire doit rester actif.');
                } else {
                    foreach ($account['users'] as &$user) {
                        if (($user['id'] ?? '') === $existingUser['id']) {
                            $user['active'] = false;
                            $user['updated_at'] = gmdate('c');
                            break;
                        }
                    }
                    unset($user);
                    if (admin_account_write($account)) {
                        admin_record_audit('Accès désactivé', 'Compte : ' . (string) $existingUser['username']);
                        admin_flash('success', 'Accès désactivé. Les contenus du site ne sont pas affectés.');
                    } else {
                        admin_flash('error', admin_storage_error());
                    }
                }
            }
        } else {
            $username = strtolower(admin_text($_POST['username'] ?? ''));
            $displayName = admin_text($_POST['display_name'] ?? '');
            $role = admin_text($_POST['role'] ?? 'viewer');
            $password = (string) ($_POST['password'] ?? '');
            $active = !empty($_POST['active']);
            $sectionSlugs = array_values(array_intersect(
                array_map('admin_text', (array) ($_POST['section_slugs'] ?? [])),
                array_map(static fn (array $section): string => (string) ($section['slug'] ?? ''), $sections)
            ));
            $usernameExists = false;
            foreach ($users as $user) {
                if (($user['id'] ?? '') !== ($existingUser['id'] ?? '') && ($user['username'] ?? '') === $username) {
                    $usernameExists = true;
                    break;
                }
            }

            if (!preg_match('/^[a-z0-9._-]{3,40}$/', $username)) {
                admin_flash('error', 'L’identifiant doit contenir 3 à 40 caractères : lettres minuscules, chiffres, tirets, points ou _ .');
            } elseif ($displayName === '') {
                admin_flash('error', 'Indique le nom de la personne ou du service.');
            } elseif (!isset($roles[$role])) {
                admin_flash('error', 'Le rôle choisi est invalide.');
            } elseif ($usernameExists) {
                admin_flash('error', 'Cet identifiant est déjà utilisé.');
            } elseif (!$existingUser && mb_strlen($password) < 12) {
                admin_flash('error', 'Un nouveau compte doit avoir un mot de passe de 12 caractères minimum.');
            } elseif ($existingUser && $password !== '' && mb_strlen($password) < 12) {
                admin_flash('error', 'Le nouveau mot de passe doit contenir au moins 12 caractères.');
            } elseif (($existingUser['role'] ?? '') === 'owner' && ($role !== 'owner' || !$active) && count(array_filter($users, static fn (array $user): bool => !empty($user['active']) && ($user['role'] ?? '') === 'owner')) <= 1) {
                admin_flash('error', 'Au moins un compte propriétaire doit rester actif.');
            } else {
                $userData = [
                    'id' => $existingUser['id'] ?? ('user-' . bin2hex(random_bytes(6))),
                    'username' => $username,
                    'display_name' => $displayName,
                    'password_hash' => $existingUser['password_hash'] ?? admin_password_hash($password),
                    'role' => $role,
                    'section_slugs' => $role === 'section_editor' ? $sectionSlugs : [],
                    'active' => $active,
                    'created_at' => $existingUser['created_at'] ?? gmdate('c'),
                    'updated_at' => gmdate('c'),
                ];
                if ($password !== '') {
                    $userData['password_hash'] = admin_password_hash($password);
                }

                $updated = false;
                foreach ($account['users'] as $index => $user) {
                    if (($user['id'] ?? '') === $userData['id']) {
                        $account['users'][$index] = $userData;
                        $updated = true;
                        break;
                    }
                }
                if (!$updated) {
                    $account['users'][] = $userData;
                }

                if (admin_account_write($account)) {
                    admin_record_audit($updated ? 'Accès modifié' : 'Accès créé', 'Compte : ' . $username . ' · ' . $roles[$role]['label']);
                    admin_flash('success', $updated ? 'Accès mis à jour.' : 'Nouvel accès créé.');
                } else {
                    admin_flash('error', admin_storage_error());
                }
            }
        }
    }
    admin_redirect('users.php');
}

$formUser = $editingUser ?? [
    'id' => '', 'username' => '', 'display_name' => '', 'role' => 'editor', 'section_slugs' => [], 'active' => true,
];
admin_page_start('Utilisateurs et rôles', 'users.php');
?>
<section class="admin-form-intro">
    <p class="admin-kicker">Accès sécurisés</p>
    <h2>Les bonnes personnes, aux bons contenus.</h2>
    <p>Crée un compte individuel pour chaque membre de l’équipe. Les rédacteur·rices, référent·es de section et administrateur·rices ne voient que les rubriques qui leur sont utiles.</p>
</section>

<section class="admin-users-grid">
    <article class="admin-user-directory admin-form-card">
        <div class="admin-card-heading"><div><p class="admin-kicker">Équipe</p><h2><?= count($users) ?> compte<?= count($users) > 1 ? 's' : '' ?></h2></div><a class="admin-button add" href="users.php">+ Nouvel accès</a></div>
        <div class="admin-user-list">
            <?php foreach ($users as $user): ?>
                <?php $role = $roles[(string) ($user['role'] ?? 'viewer')] ?? $roles['viewer']; ?>
                <a class="admin-user-list-item<?= ($formUser['id'] ?? '') === ($user['id'] ?? '') ? ' is-active' : '' ?>" href="users.php?edit=<?= e((string) ($user['id'] ?? '')) ?>">
                    <span class="admin-user-avatar"><?= e(strtoupper(mb_substr((string) ($user['display_name'] ?? '?'), 0, 1))) ?></span>
                    <span><strong><?= e((string) ($user['display_name'] ?? 'Compte')) ?></strong><small>@<?= e((string) ($user['username'] ?? '')) ?> · <?= e($role['label']) ?></small></span>
                    <b class="<?= !empty($user['active']) ? 'is-active' : '' ?>"><?= !empty($user['active']) ? 'Actif' : 'Désactivé' ?></b>
                </a>
            <?php endforeach; ?>
        </div>
    </article>

    <form method="post" class="admin-form admin-user-form">
        <?= admin_form_token() ?>
        <input type="hidden" name="user_id" value="<?= e((string) ($formUser['id'] ?? '')) ?>">
        <section class="admin-form-card">
            <div class="admin-card-heading"><div><p class="admin-kicker"><?= $editingUser ? 'Modifier un accès' : 'Nouvel accès' ?></p><h2><?= $editingUser ? e((string) ($formUser['display_name'] ?? 'Compte')) : 'Inviter un membre' ?></h2><p>Chaque personne possède son propre identifiant et son propre mot de passe.</p></div></div>
            <div class="admin-fields two">
                <div class="admin-field"><label for="display_name">Nom affiché</label><input id="display_name" name="display_name" value="<?= e((string) ($formUser['display_name'] ?? '')) ?>" required placeholder="Prénom NOM ou Communication"></div>
                <div class="admin-field"><label for="username">Identifiant</label><input id="username" name="username" value="<?= e((string) ($formUser['username'] ?? '')) ?>" required pattern="[a-z0-9._-]{3,40}" autocomplete="username" placeholder="prenom.nom"><small>Minuscules, chiffres, tirets, points et _ uniquement.</small></div>
                <div class="admin-field"><label for="role">Rôle</label><select id="role" name="role" data-role-select><?php foreach ($roles as $key => $role): ?><option value="<?= e($key) ?>"<?= ($formUser['role'] ?? '') === $key ? ' selected' : '' ?>><?= e($role['label']) ?></option><?php endforeach; ?></select></div>
                <div class="admin-field"><label for="password">Mot de passe<?= $editingUser ? ' (laisser vide pour le conserver)' : '' ?></label><input id="password" name="password" type="password"<?= $editingUser ? '' : ' required' ?> minlength="12" autocomplete="new-password"><small>12 caractères minimum.</small></div>
                <fieldset class="admin-section-rights full" data-section-rights<?= ($formUser['role'] ?? '') === 'section_editor' ? '' : ' hidden' ?>><legend>Sections attribuées</legend><p>Ce rôle ne pourra ouvrir et modifier que ces fiches.</p><div><?php foreach ($sections as $section): ?><label><input type="checkbox" name="section_slugs[]" value="<?= e((string) $section['slug']) ?>"<?= in_array((string) $section['slug'], (array) ($formUser['section_slugs'] ?? []), true) ? ' checked' : '' ?>> <?= e((string) $section['name']) ?></label><?php endforeach; ?></div></fieldset>
                <div class="admin-field full"><label class="admin-switch"><input type="checkbox" name="active" value="1"<?= !empty($formUser['active']) ? ' checked' : '' ?>><span aria-hidden="true"></span>Accès actif</label><small>Un accès désactivé reste dans l’historique mais ne peut plus se connecter.</small></div>
            </div>
        </section>
        <div class="admin-form-actions"><button class="admin-button primary" type="submit"><?= $editingUser ? 'Enregistrer les droits' : 'Créer l’accès' ?></button><?php if ($editingUser && !empty($formUser['active']) && ($formUser['id'] ?? '') !== (admin_current_user()['id'] ?? '')): ?><button class="admin-button danger" type="submit" name="action" value="deactivate" data-confirm="Désactiver cet accès ? La personne ne pourra plus se connecter.">Désactiver l’accès</button><?php endif; ?></div>
    </form>
</section>

<section class="admin-role-guide">
    <?php foreach ($roles as $key => $role): ?>
        <article><strong><?= e($role['label']) ?></strong><p><?= $key === 'owner' ? 'Tous les droits, y compris la gestion des accès.' : ($key === 'admin' ? 'Gestion complète du contenu et des synchronisations.' : ($key === 'editor' ? 'Photothèque et contenus éditoriaux.' : ($key === 'section_editor' ? 'Uniquement les fiches de section attribuées.' : 'Lecture seule du tableau de bord.'))) ?></p></article>
    <?php endforeach; ?>
</section>
<?php admin_page_end(); ?>
