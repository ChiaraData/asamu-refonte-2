<?php
declare(strict_types=1);

require_once __DIR__ . '/content-store.php';

/**
 * Authentification et droits du back-office.
 *
 * Le site reste compatible avec l’ancien fichier à mot de passe unique ; il
 * est normalisé à la lecture vers une liste d’utilisateurs. Ainsi, aucun accès
 * existant n’est perdu lors du passage au CMS multi-utilisateur.
 */
function admin_account_path(): string
{
    return __DIR__ . '/../storage/admin-account.json';
}

/** @return array<string, array{label: string, capabilities: array<int, string>}> */
function admin_role_definitions(): array
{
    return [
        'owner' => ['label' => 'Propriétaire', 'capabilities' => ['*']],
        'admin' => ['label' => 'Administrateur·rice', 'capabilities' => ['dashboard', 'sections', 'calendar', 'coaches', 'gallery', 'media', 'palmares', 'google_sheets', 'settings', 'content', 'news']],
        'editor' => ['label' => 'Rédacteur·rice', 'capabilities' => ['dashboard', 'gallery', 'media', 'news']],
        'section_editor' => ['label' => 'Référent·e de section', 'capabilities' => ['dashboard', 'sections', 'gallery', 'media']],
        'viewer' => ['label' => 'Consultation', 'capabilities' => ['dashboard']],
    ];
}

function admin_default_account(): array
{
    return [
        'schema' => 2,
        'users' => [[
            'id' => 'owner',
            'username' => 'admin',
            'display_name' => 'Administration AS amU',
            // Mot de passe temporaire communiqué à la remise du site.
            'password_hash' => '$2y$12$2NQeSSmPgcmwyB7/UDJDauv1WVzdQHno1JglLwF4Dy5fjuV8uRMVi',
            'role' => 'owner',
            'section_slugs' => [],
            'active' => true,
            'created_at' => gmdate('c'),
        ]],
    ];
}

/** @return array<string, mixed> */
function admin_normalize_account(mixed $account): array
{
    $default = admin_default_account();
    if (!is_array($account)) {
        return $default;
    }

    // Format historique : un mot de passe unique à la racine du JSON.
    if (!empty($account['password_hash']) && is_string($account['password_hash'])) {
        $default['users'][0]['password_hash'] = $account['password_hash'];
        return $default;
    }

    $roles = admin_role_definitions();
    $users = [];
    foreach (($account['users'] ?? []) as $index => $user) {
        if (!is_array($user) || empty($user['password_hash']) || !is_string($user['password_hash'])) {
            continue;
        }
        $username = strtolower(trim((string) ($user['username'] ?? '')));
        if (!preg_match('/^[a-z0-9._-]{3,40}$/', $username)) {
            continue;
        }
        $role = (string) ($user['role'] ?? 'viewer');
        if (!isset($roles[$role])) {
            $role = 'viewer';
        }
        $sectionSlugs = array_values(array_filter(
            (array) ($user['section_slugs'] ?? []),
            static fn (mixed $slug): bool => is_string($slug) && preg_match('/^[a-z0-9-]+$/', $slug) === 1
        ));
        $users[] = [
            'id' => preg_replace('/[^a-z0-9_-]/i', '', (string) ($user['id'] ?? '')) ?: 'user-' . ($index + 1),
            'username' => $username,
            'display_name' => trim((string) ($user['display_name'] ?? $username)) ?: $username,
            'password_hash' => $user['password_hash'],
            'role' => $role,
            'section_slugs' => $sectionSlugs,
            'active' => !isset($user['active']) || (bool) $user['active'],
            'created_at' => (string) ($user['created_at'] ?? gmdate('c')),
            'updated_at' => (string) ($user['updated_at'] ?? ''),
        ];
    }

    if (!$users) {
        return $default;
    }

    return ['schema' => 2, 'users' => $users];
}

/** @return array<string, mixed> */
function admin_account_read(): array
{
    $path = admin_account_path();
    if (!is_file($path) || !is_readable($path)) {
        return admin_default_account();
    }

    return admin_normalize_account(json_decode((string) file_get_contents($path), true));
}

function admin_write_json_file(string $path, array $data): bool
{
    $directory = dirname($path);
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        return false;
    }
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    $temporaryPath = $path . '.tmp';
    if (file_put_contents($temporaryPath, $json . PHP_EOL, LOCK_EX) === false) {
        return false;
    }

    return rename($temporaryPath, $path);
}

function admin_account_write(array $account): bool
{
    return admin_write_json_file(admin_account_path(), admin_normalize_account($account));
}

/** @return array<int, array<string, mixed>> */
function admin_users(): array
{
    return (array) (admin_account_read()['users'] ?? []);
}

/** @return array<string, mixed>|null */
function admin_find_user(string $id): ?array
{
    foreach (admin_users() as $user) {
        if (hash_equals((string) ($user['id'] ?? ''), $id)) {
            return $user;
        }
    }
    return null;
}

function admin_request_is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
}

function admin_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    session_name('asamu_admin');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => admin_request_is_https(),
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

/** @return array<string, mixed>|null */
function admin_current_user(): ?array
{
    $id = (string) ($_SESSION['asamu_admin_user_id'] ?? '');
    if ($id === '') {
        return null;
    }
    $user = admin_find_user($id);
    return $user && !empty($user['active']) ? $user : null;
}

function admin_is_logged_in(): bool
{
    return admin_current_user() !== null;
}

/** @return array<int, string> */
function admin_user_capabilities(?array $user = null): array
{
    $user ??= admin_current_user();
    if (!$user) {
        return [];
    }
    $roles = admin_role_definitions();
    return $roles[(string) ($user['role'] ?? 'viewer')]['capabilities'] ?? [];
}

function admin_can(string $capability): bool
{
    $capabilities = admin_user_capabilities();
    return in_array('*', $capabilities, true) || in_array($capability, $capabilities, true);
}

function admin_can_edit_section(string $slug): bool
{
    if (!admin_can('sections')) {
        return false;
    }
    $user = admin_current_user();
    if (!$user || in_array((string) ($user['role'] ?? ''), ['owner', 'admin'], true)) {
        return true;
    }
    return in_array($slug, (array) ($user['section_slugs'] ?? []), true);
}

function admin_require_login(): void
{
    if (admin_is_logged_in()) {
        return;
    }

    header('Location: login.php');
    exit;
}

function admin_require_permission(string $capability): void
{
    admin_require_login();
    if (admin_can($capability)) {
        return;
    }

    admin_flash('error', 'Cet accès n’est pas autorisé pour votre compte.');
    header('Location: index.php');
    exit;
}

function admin_login(string $username, string $password): bool
{
    $username = strtolower(trim($username));
    $users = array_values(array_filter(admin_users(), static fn (array $user): bool => !empty($user['active'])));

    // Compatibilité avec le compte historique : son identifiant est « admin ».
    if ($username === '' && count($users) === 1) {
        $username = (string) ($users[0]['username'] ?? '');
    }

    foreach ($users as $user) {
        if (!hash_equals((string) ($user['username'] ?? ''), $username)) {
            continue;
        }
        if (!password_verify($password, (string) ($user['password_hash'] ?? ''))) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['asamu_admin_user_id'] = $user['id'];
        $_SESSION['asamu_admin_authenticated_at'] = time();
        admin_record_audit('Connexion à l’administration', 'Connexion réussie');
        return true;
    }

    return false;
}

function admin_password_hash(string $password): string
{
    $algorithm = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
    return password_hash($password, $algorithm);
}

function admin_update_current_user_password(string $currentPassword, string $newPassword): bool
{
    $currentUser = admin_current_user();
    if (!$currentUser || !password_verify($currentPassword, (string) $currentUser['password_hash'])) {
        return false;
    }

    $account = admin_account_read();
    foreach ($account['users'] as &$user) {
        if (($user['id'] ?? '') === $currentUser['id']) {
            $user['password_hash'] = admin_password_hash($newPassword);
            $user['updated_at'] = gmdate('c');
            break;
        }
    }
    unset($user);

    $saved = admin_account_write($account);
    if ($saved) {
        admin_record_audit('Mot de passe modifié', 'Compte : ' . (string) $currentUser['username']);
    }
    return $saved;
}

function admin_logout(): void
{
    if (admin_is_logged_in()) {
        admin_record_audit('Déconnexion de l’administration', '');
    }
    $_SESSION = [];
    if (session_id() !== '') {
        session_destroy();
    }
}

function admin_csrf_token(): string
{
    if (empty($_SESSION['asamu_admin_csrf'])) {
        $_SESSION['asamu_admin_csrf'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['asamu_admin_csrf'];
}

function admin_verify_csrf(): bool
{
    $token = (string) ($_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    return !empty($_SESSION['asamu_admin_csrf']) && hash_equals((string) $_SESSION['asamu_admin_csrf'], $token);
}

function admin_flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['asamu_admin_flash'][$key] = $value;
        return null;
    }

    $message = $_SESSION['asamu_admin_flash'][$key] ?? null;
    unset($_SESSION['asamu_admin_flash'][$key]);
    return is_string($message) ? $message : null;
}

function admin_audit_path(): string
{
    return __DIR__ . '/../storage/admin-audit.json';
}

/** @return array<int, array<string, mixed>> */
function admin_audit_read(): array
{
    $path = admin_audit_path();
    if (!is_file($path) || !is_readable($path)) {
        return [];
    }
    $records = json_decode((string) file_get_contents($path), true);
    return is_array($records) ? array_values(array_filter($records, 'is_array')) : [];
}

function admin_record_audit(string $action, string $details = ''): void
{
    $user = admin_current_user();
    $records = admin_audit_read();
    array_unshift($records, [
        'id' => bin2hex(random_bytes(8)),
        'created_at' => gmdate('c'),
        'action' => $action,
        'details' => $details,
        'user' => $user ? (string) ($user['display_name'] ?? $user['username'] ?? '') : 'Système',
    ]);
    // Le journal est volontairement borné pour rester léger sur un hébergement mutualisé.
    admin_write_json_file(admin_audit_path(), array_slice($records, 0, 120));
}

