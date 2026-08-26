<?php
declare(strict_types=1);

/**
 * Stockage des contenus saisis depuis l’administration.
 * Les contenus d’origine restent dans config.php : ce fichier ne garde que
 * les modifications effectuées depuis l’interface.
 */
function content_store_path(): string
{
    return __DIR__ . '/../storage/content.json';
}

/**
 * Nettoie le balisage produit par l’éditeur visuel. On autorise uniquement
 * les éléments utiles à un texte éditorial : aucun script, style intégré ou
 * attribut non maîtrisé ne peut être enregistré.
 */
function rich_text_sanitize(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    // Les contenus historiques sont en texte brut : ils restent entièrement
    // compatibles et sont mis en forme uniquement au moment de l’affichage.
    if (strip_tags($value) === $value) {
        return $value;
    }

    $value = (string) preg_replace('#<(script|style|iframe|object|embed)[^>]*>.*?</\1\s*>#isu', '', $value);
    $value = strip_tags($value, '<p><br><strong><em><ul><ol><li><a>');
    return (string) preg_replace_callback('/<([a-z0-9]+)([^>]*)>/iu', static function (array $matches): string {
        $tag = strtolower($matches[1]);
        if ($tag !== 'a') {
            return '<' . $tag . '>';
        }

        preg_match('/\bhref\s*=\s*(["\'])(.*?)\1/iu', $matches[2], $hrefMatch);
        $href = trim((string) ($hrefMatch[2] ?? ''));
        if ($href === '' || !preg_match('#^(https?://|mailto:|tel:)#iu', $href)) {
            return '<a>';
        }
        return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" rel="noopener noreferrer">';
    }, $value);
}

function rich_text_render(string $value): string
{
    $value = rich_text_sanitize($value);
    if ($value === '') {
        return '';
    }
    if (strip_tags($value) === $value) {
        return nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
    }
    return $value;
}

function content_store_default(): array
{
    return [
        'schema' => 1,
        'site' => [],
        'sections' => [],
        'gallery' => null,
        'palmares' => null,
        'collections' => [],
    ];
}

/**
 * Retourne une collection éditable. Les données d’origine restent utilisées
 * tant qu’aucune modification n’a été enregistrée depuis l’administration.
 */
function content_store_collection(string $name, array $default): array
{
    $content = content_store_read();
    $value = $content['collections'][$name] ?? null;
    return is_array($value) ? $value : $default;
}

function content_store_read(): array
{
    $path = content_store_path();
    if (!is_file($path) || !is_readable($path)) {
        return content_store_default();
    }

    $decoded = json_decode((string) file_get_contents($path), true);
    if (!is_array($decoded)) {
        return content_store_default();
    }

    return array_replace(content_store_default(), $decoded);
}

function content_store_write(array $content): bool
{
    $path = content_store_path();
    $directory = dirname($path);
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        return false;
    }

    $json = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }

    // Écriture atomique : le site public ne peut jamais lire un fichier JSON
    // partiellement sauvegardé, même en cas de coupure pendant l’enregistrement.
    $temporaryPath = $path . '.tmp';
    if (file_put_contents($temporaryPath, $json . PHP_EOL, LOCK_EX) === false || !rename($temporaryPath, $path)) {
        return false;
    }

    if (function_exists('admin_record_audit')) {
        $page = basename((string) ($_SERVER['SCRIPT_NAME'] ?? 'contenu'));
        admin_record_audit('Contenu enregistré', 'Module : ' . $page);
    }

    return true;
}

/** @param array<int, array<string, mixed>> $sections */
function content_store_apply(array &$site, array &$sections, array &$photoGallery, array &$palmares, array &$associationStats): void
{
    $content = content_store_read();

    $siteFields = [
        'name', 'full_name', 'tagline', 'site_url', 'social_image', 'helloasso_url', 'mysportu_url',
        'instagram_url', 'email', 'competition_email', 'treasury_email', 'communication_email',
        'phone', 'address', 'membership_price', 'license_price', 'season', 'president_name',
        'president_title', 'president_photo', 'president_word', 'legal_status', 'rna_number',
        'siren_number', 'siret_number', 'privacy_email', 'host_name', 'host_company',
        'host_address', 'host_phone',
    ];
    foreach (($content['site'] ?? []) as $key => $value) {
        if (in_array($key, $siteFields, true) && is_string($value)) {
            $site[$key] = $value;
        }
    }
    if (isset($content['site']['home_stats']) && is_array($content['site']['home_stats'])) {
        $site['home_stats'] = $content['site']['home_stats'];
    }

    $sectionFields = [
        'name', 'component', 'city', 'campus', 'address', 'map_query', 'email', 'office_hours',
        'adherents_count', 'licensees_count', 'notes',
    ];
    $sectionCollections = ['bureau', 'activity_stats', 'content_blocks', 'events'];
    foreach ($sections as &$section) {
        $slug = (string) ($section['slug'] ?? '');
        $override = $content['sections'][$slug] ?? null;
        if (!is_array($override)) {
            continue;
        }

        foreach ($sectionFields as $field) {
            if (isset($override[$field]) && is_string($override[$field])) {
                $section[$field] = $override[$field];
            }
        }
        foreach ($sectionCollections as $field) {
            if (array_key_exists($field, $override) && is_array($override[$field])) {
                $section[$field] = $override[$field];
            }
        }
    }
    unset($section);

    if (is_array($content['gallery'] ?? null)) {
        $photoGallery = $content['gallery'];
    }
    if (is_array($content['palmares'] ?? null)) {
        $palmares = $content['palmares'];
    }
    if (is_array($content['association_stats'] ?? null)) {
        $associationStats = $content['association_stats'];
    }
}
