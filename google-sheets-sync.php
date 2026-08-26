<?php
declare(strict_types=1);

/**
 * Point de réception protégé : il reçoit les podiums calculés dans Google
 * Sheets par Apps Script et actualise le contenu public du site.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/content-store.php';
require_once __DIR__ . '/includes/google-sheets.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function google_sheets_sync_response(int $status, array $body): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    google_sheets_sync_response(405, ['ok' => false, 'error' => 'Méthode non autorisée.']);
}

$payload = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($payload)) {
    google_sheets_sync_response(400, ['ok' => false, 'error' => 'Le contenu JSON est invalide.']);
}

$settings = google_sheets_read_settings();
$secret = (string) ($settings['shared_secret'] ?? '');
if (empty($settings['enabled']) || $secret === '') {
    google_sheets_sync_response(503, ['ok' => false, 'error' => 'La synchronisation Google Sheets n’est pas activée sur le site.']);
}
if (!isset($payload['secret']) || !is_string($payload['secret']) || !hash_equals($secret, $payload['secret'])) {
    google_sheets_sync_response(403, ['ok' => false, 'error' => 'Code secret incorrect.']);
}
if (!array_key_exists('entries', $payload) || !is_array($payload['entries'])) {
    google_sheets_sync_response(422, ['ok' => false, 'error' => 'Aucun résultat reçu.']);
}

$entries = google_sheets_sanitize_palmares_entries($payload['entries']);
$store = content_store_read();
$store['palmares'] = $entries;
if (!content_store_write($store)) {
    $settings['last_error'] = 'Le fichier de contenu du site est inaccessible en écriture.';
    google_sheets_write_settings($settings);
    google_sheets_sync_response(500, ['ok' => false, 'error' => $settings['last_error']]);
}

$settings['last_sync_at'] = date('c');
$settings['last_error'] = '';
$settings['last_source'] = trim((string) ($payload['source'] ?? 'Google Sheet')) ?: 'Google Sheet';
google_sheets_write_settings($settings);

google_sheets_sync_response(200, [
    'ok' => true,
    'entries' => count($entries),
    'message' => 'Le palmarès du site a été mis à jour.',
]);
