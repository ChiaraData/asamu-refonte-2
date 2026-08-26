<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/../includes/media-library.php';
require_once __DIR__ . '/../includes/google-sheets.php';

admin_start_session();

function admin_redirect(string $path, array $query = []): never
{
    if ($query) {
        $path .= (str_contains($path, '?') ? '&' : '?') . http_build_query($query);
    }

    header('Location: ' . $path);
    exit;
}

function admin_text(mixed $value): string
{
    return trim(is_scalar($value) ? (string) $value : '');
}

/** @return array<int, array<string, string>> */
function admin_post_rows(array $fields): array
{
    $length = 0;
    foreach ($fields as $field) {
        $values = $_POST[$field] ?? [];
        $length = max($length, is_array($values) ? count($values) : 0);
    }

    $rows = [];
    for ($index = 0; $index < $length; $index++) {
        $row = [];
        $hasValue = false;
        foreach ($fields as $field) {
            $values = $_POST[$field] ?? [];
            $value = is_array($values) ? admin_text($values[$index] ?? '') : '';
            $row[$field] = $value;
            $hasValue = $hasValue || $value !== '';
        }
        if ($hasValue) {
            $rows[] = $row;
        }
    }

    return $rows;
}

/** @return array<int, string> */
function admin_post_paragraphs(string $value): array
{
    $paragraphs = preg_split('/\R\s*\R/u', trim($value)) ?: [];
    return array_values(array_filter(array_map('trim', $paragraphs), static fn (string $text): bool => $text !== ''));
}

/** @return array<int, string> */
function admin_post_lines(string $value): array
{
    $lines = preg_split('/\R/u', trim($value)) ?: [];
    return array_values(array_filter(array_map('trim', $lines), static fn (string $text): bool => $text !== ''));
}

function admin_storage_error(): string
{
    return 'Impossible d’enregistrer. Vérifie que le dossier « storage » est accessible en écriture sur l’hébergement.';
}

/** @return array{path: string, error: string} */
function admin_store_image(array $upload, string $folder, string $prefix): array
{
    $errorCode = (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($errorCode === UPLOAD_ERR_NO_FILE) {
        return ['path' => '', 'error' => ''];
    }
    if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file((string) ($upload['tmp_name'] ?? ''))) {
        return ['path' => '', 'error' => 'Le fichier image n’a pas pu être envoyé.'];
    }
    if ((int) ($upload['size'] ?? 0) > 5 * 1024 * 1024) {
        return ['path' => '', 'error' => 'L’image dépasse la limite de 5 Mo.'];
    }

    $imageInfo = @getimagesize((string) $upload['tmp_name']);
    $mime = is_array($imageInfo) ? ($imageInfo['mime'] ?? '') : '';
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime])) {
        return ['path' => '', 'error' => 'Utilise une image JPG, PNG ou WEBP.'];
    }

    $targetDirectory = __DIR__ . '/../assets/img/' . trim($folder, '/') . '/';
    if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
        return ['path' => '', 'error' => 'Le dossier de destination des images est indisponible.'];
    }
    $filename = $prefix . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extensions[$mime];
    if (!move_uploaded_file((string) $upload['tmp_name'], $targetDirectory . $filename)) {
        return ['path' => '', 'error' => 'Impossible de placer l’image sur le serveur.'];
    }

    return ['path' => 'assets/img/' . trim($folder, '/') . '/' . $filename, 'error' => ''];
}

function admin_media_folder_slug(string $folder): string
{
    $folder = trim($folder);
    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $folder) ?: $folder;
    $slug = strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '-', $transliterated));
    return trim($slug, '-') ?: 'non-classe';
}

/**
 * Dépose une image ou un PDF dans la médiathèque. Les images compatibles sont
 * redimensionnées en WebP lorsque l’extension GD est disponible ; l’original
 * n’est alors pas dupliqué inutilement sur l’hébergement.
 *
 * @return array{path: string, error: string, mime: string, filename: string, size: int, width: int, height: int, optimized: bool}
 */
function admin_store_media(array $upload, string $folder): array
{
    $empty = ['path' => '', 'error' => '', 'mime' => '', 'filename' => '', 'size' => 0, 'width' => 0, 'height' => 0, 'optimized' => false];
    $errorCode = (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($errorCode === UPLOAD_ERR_NO_FILE) {
        return $empty;
    }
    if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file((string) ($upload['tmp_name'] ?? ''))) {
        return [...$empty, 'error' => 'Le fichier n’a pas pu être envoyé.'];
    }
    if ((int) ($upload['size'] ?? 0) > 12 * 1024 * 1024) {
        return [...$empty, 'error' => 'Le fichier dépasse la limite de 12 Mo.'];
    }

    $temporaryPath = (string) $upload['tmp_name'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($temporaryPath);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf'];
    if (!isset($extensions[$mime])) {
        return [...$empty, 'error' => 'Utilise un JPG, PNG, WEBP ou PDF.'];
    }

    $width = 0;
    $height = 0;
    if (str_starts_with($mime, 'image/')) {
        $imageInfo = @getimagesize($temporaryPath);
        if (!is_array($imageInfo)) {
            return [...$empty, 'error' => 'Cette image est invalide.'];
        }
        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);
        if ($width < 32 || $height < 32 || $width * $height > 30_000_000) {
            return [...$empty, 'error' => 'L’image est trop petite ou trop lourde à traiter.'];
        }
    }

    $folderSlug = admin_media_folder_slug($folder);
    $targetDirectory = __DIR__ . '/../assets/media/' . $folderSlug . '/';
    if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
        return [...$empty, 'error' => 'Le dossier de la médiathèque est indisponible.'];
    }

    $baseName = 'media-' . date('Ymd-His') . '-' . bin2hex(random_bytes(5));
    $filename = $baseName . '.' . $extensions[$mime];
    $targetPath = $targetDirectory . $filename;
    $optimized = false;

    // Compression raisonnable des images : elle reste facultative pour ne pas
    // rendre l’administration dépendante d’une extension non disponible.
    if (str_starts_with($mime, 'image/') && function_exists('imagewebp')) {
        $createFunction = match ($mime) {
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/png' => 'imagecreatefrompng',
            'image/webp' => 'imagecreatefromwebp',
        };
        $source = function_exists($createFunction) ? @$createFunction($temporaryPath) : false;
        if ($source !== false) {
            $maximumDimension = 2560;
            $ratio = min(1, $maximumDimension / max($width, $height));
            $targetWidth = max(1, (int) round($width * $ratio));
            $targetHeight = max(1, (int) round($height * $ratio));
            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefill($canvas, 0, 0, $transparent);
            imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
            $filename = $baseName . '.webp';
            $targetPath = $targetDirectory . $filename;
            $optimized = imagewebp($canvas, $targetPath, 82);
            imagedestroy($canvas);
            imagedestroy($source);
            if ($optimized) {
                $mime = 'image/webp';
                $width = $targetWidth;
                $height = $targetHeight;
            }
        }
    }

    if (!$optimized && !move_uploaded_file($temporaryPath, $targetPath)) {
        return [...$empty, 'error' => 'Impossible de placer le fichier sur le serveur.'];
    }

    return [
        'path' => 'assets/media/' . $folderSlug . '/' . $filename,
        'error' => '',
        'mime' => $mime,
        'filename' => $filename,
        'size' => (int) filesize($targetPath),
        'width' => $width,
        'height' => $height,
        'optimized' => $optimized,
    ];
}

function admin_form_token(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(admin_csrf_token()) . '">';
}
