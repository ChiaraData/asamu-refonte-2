<?php
declare(strict_types=1);

/**
 * Configuration locale de la synchronisation Google Sheets.
 * Le fichier est rangé dans storage/, protégé par .htaccess.
 */
function google_sheets_settings_path(): string
{
    return __DIR__ . '/../storage/google-sheets.json';
}

function google_sheets_default_settings(): array
{
    return [
        'enabled' => false,
        'sheet_url' => '',
        // Conservé pour ne pas casser une ancienne configuration sortante.
        'endpoint_url' => '',
        'shared_secret' => '',
        'last_sync_at' => '',
        'last_error' => '',
        'last_source' => '',
    ];
}

function google_sheets_read_settings(): array
{
    $path = google_sheets_settings_path();
    if (!is_file($path) || !is_readable($path)) {
        return google_sheets_default_settings();
    }

    $settings = json_decode((string) file_get_contents($path), true);
    return is_array($settings) ? array_replace(google_sheets_default_settings(), $settings) : google_sheets_default_settings();
}

function google_sheets_write_settings(array $settings): bool
{
    $directory = dirname(google_sheets_settings_path());
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        return false;
    }

    $json = json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    return $json !== false && file_put_contents(google_sheets_settings_path(), $json . PHP_EOL, LOCK_EX) !== false;
}

function google_sheets_endpoint_is_valid(string $url): bool
{
    $parts = parse_url($url);
    $host = strtolower((string) ($parts['host'] ?? ''));
    return ($parts['scheme'] ?? '') === 'https'
        && (str_ends_with($host, '.google.com') || str_ends_with($host, '.googleusercontent.com'));
}

/** URL à renseigner dans le script Apps Script lié au Google Sheet. */
function google_sheets_site_sync_url(): string
{
    global $site;
    $siteUrl = (string) ($site['site_url'] ?? '');
    if (function_exists('content_store_read')) {
        $content = content_store_read();
        if (!empty($content['site']['site_url']) && is_string($content['site']['site_url'])) {
            $siteUrl = $content['site']['site_url'];
        }
    }
    $siteUrl = rtrim($siteUrl, '/');
    return $siteUrl !== '' ? $siteUrl . '/google-sheets-sync.php' : '';
}

/**
 * Vérifie et réduit les données reçues depuis le Google Sheet avant publication.
 *
 * @param mixed $entries
 * @return array<int, array<string, mixed>>
 */
function google_sheets_sanitize_palmares_entries(mixed $entries): array
{
    if (!is_array($entries)) {
        return [];
    }

    $clean = [];
    foreach ($entries as $entry) {
        if (!is_array($entry)) {
            continue;
        }
        $place = (int) ($entry['place'] ?? 0);
        $sport = google_sheets_limit_text($entry['sport'] ?? '', 140);
        $result = google_sheets_limit_text($entry['result'] ?? '', 160);
        if ($sport === '' || $result === '' || !in_array($place, [1, 2, 3], true)) {
            continue;
        }

        $item = [
            'season' => google_sheets_limit_text($entry['season'] ?? '', 30),
            'sport' => $sport,
            'competition' => google_sheets_limit_text($entry['competition'] ?? '', 160),
            'result' => $result,
            'place' => $place,
        ];
        if (!empty($entry['team'])) {
            $item['team'] = true;
            $teamName = google_sheets_limit_text($entry['team_name'] ?? '', 160);
            if ($teamName !== '') {
                $item['team_name'] = $teamName;
            }
        } else {
            $item['last_name'] = google_sheets_limit_text($entry['last_name'] ?? '', 100);
            $item['first_name'] = google_sheets_limit_text($entry['first_name'] ?? '', 100);
            if ($item['last_name'] === '' && $item['first_name'] === '') {
                continue;
            }
        }
        $clean[] = $item;
    }
    return $clean;
}

function google_sheets_limit_text(mixed $value, int $length): string
{
    $value = trim(is_scalar($value) ? (string) $value : '');
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $length, 'UTF-8');
    }
    return substr($value, 0, $length);
}

/** @return array<int, array<int, string>> */
function google_sheets_palmares_rows(array $palmares): array
{
    $rows = [[
        'Saison', 'Sport', 'Compétition', 'Représentation', 'Nom', 'Prénom', 'Résultat', 'Place', 'Médaille', 'Mis à jour le',
    ]];

    foreach ($palmares as $entry) {
        $place = (int) ($entry['place'] ?? 0);
        if (!in_array($place, [1, 2, 3], true)) {
            continue;
        }
        $medal = [1 => 'Or', 2 => 'Argent', 3 => 'Bronze'][$place];
        $isTeam = !empty($entry['team']);
        $rows[] = array_map('google_sheets_safe_cell', [
            trim((string) ($entry['season'] ?? '')) ?: 'Non renseignée',
            (string) ($entry['sport'] ?? ''),
            (string) ($entry['competition'] ?? ''),
            $isTeam ? (trim((string) ($entry['team_name'] ?? '')) ?: 'Équipe AS amU') : 'Athlète',
            $isTeam ? '' : (string) ($entry['last_name'] ?? ''),
            $isTeam ? '' : (string) ($entry['first_name'] ?? ''),
            (string) ($entry['result'] ?? ''),
            (string) $place,
            $medal,
            date('d/m/Y H:i'),
        ]);
    }

    return $rows;
}

function google_sheets_safe_cell(string $value): string
{
    return preg_match('/^[=+\-@]/', $value) ? "'" . $value : $value;
}

/**
 * Envoie l’intégralité du palmarès vers le Web App Apps Script configuré.
 * Retourne null en cas de succès, ou le message d’erreur à afficher à l’admin.
 */
function google_sheets_sync_palmares(array $palmares): ?string
{
    $settings = google_sheets_read_settings();
    if (empty($settings['enabled'])) {
        return null;
    }

    if (!function_exists('curl_init')) {
        return 'L’extension PHP cURL est nécessaire pour synchroniser Google Sheets.';
    }

    $endpoint = trim((string) $settings['endpoint_url']);
    $secret = trim((string) $settings['shared_secret']);
    if (!google_sheets_endpoint_is_valid($endpoint) || $secret === '') {
        return 'La connexion Google Sheets n’est pas encore configurée.';
    }

    $payload = json_encode([
        'secret' => $secret,
        'sheet_name' => 'Palmarès',
        'rows' => google_sheets_palmares_rows($palmares),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($payload === false) {
        return 'Les données du palmarès ne peuvent pas être préparées pour Google Sheets.';
    }

    $curl = curl_init($endpoint);
    if ($curl === false) {
        return 'La connexion Google Sheets ne peut pas être initialisée sur ce serveur.';
    }
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 20,
    ]);
    $response = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($response === false || $status < 200 || $status >= 300) {
        $message = $curlError !== '' ? $curlError : 'Réponse HTTP ' . ($status ?: 'inconnue');
        $settings['last_error'] = $message;
        google_sheets_write_settings($settings);
        return 'Google Sheets n’a pas répondu : ' . $message;
    }

    $answer = json_decode((string) $response, true);
    if (!is_array($answer) || empty($answer['ok'])) {
        $message = is_array($answer) && !empty($answer['error']) ? (string) $answer['error'] : 'réponse inattendue du script Google';
        $settings['last_error'] = $message;
        google_sheets_write_settings($settings);
        return 'Google Sheets n’a pas accepté la synchronisation : ' . $message;
    }

    $settings['last_sync_at'] = date('c');
    $settings['last_error'] = '';
    google_sheets_write_settings($settings);
    return null;
}
