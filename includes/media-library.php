<?php
declare(strict_types=1);

/**
 * Métadonnées de la médiathèque. Les fichiers restent publics dans
 * assets/media afin de pouvoir être utilisés dans les pages, tandis que leurs
 * descriptions, dossiers et cadrages sont conservés hors de l’URL publique.
 */
function media_library_path(): string
{
    return __DIR__ . '/../storage/media-library.json';
}

/** @return array{schema: int, items: array<int, array<string, mixed>>} */
function media_library_default(): array
{
    return ['schema' => 1, 'items' => []];
}

/** @return array{schema: int, items: array<int, array<string, mixed>>} */
function media_library_read(): array
{
    $path = media_library_path();
    if (!is_file($path) || !is_readable($path)) {
        return media_library_default();
    }
    $content = json_decode((string) file_get_contents($path), true);
    if (!is_array($content)) {
        return media_library_default();
    }

    $items = array_values(array_filter((array) ($content['items'] ?? []), static fn (mixed $item): bool => is_array($item)));
    return ['schema' => 1, 'items' => $items];
}

function media_library_write(array $library): bool
{
    $path = media_library_path();
    $directory = dirname($path);
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        return false;
    }
    $json = json_encode(['schema' => 1, 'items' => array_values((array) ($library['items'] ?? []))], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    $temporaryPath = $path . '.tmp';
    return file_put_contents($temporaryPath, $json . PHP_EOL, LOCK_EX) !== false && rename($temporaryPath, $path);
}

/** @return array<int, array<string, mixed>> */
function media_library_items(bool $includeArchived = false): array
{
    $items = media_library_read()['items'];
    if (!$includeArchived) {
        $items = array_values(array_filter($items, static fn (array $item): bool => empty($item['archived_at'])));
    }
    usort($items, static fn (array $left, array $right): int => strcmp((string) ($right['created_at'] ?? ''), (string) ($left['created_at'] ?? '')));
    return $items;
}

/** @return array<string, mixed>|null */
function media_library_find(string $id): ?array
{
    foreach (media_library_items(true) as $item) {
        if (hash_equals((string) ($item['id'] ?? ''), $id)) {
            return $item;
        }
    }
    return null;
}

/** @return array<int, string> */
function media_library_folders(): array
{
    $folders = array_map(static fn (array $item): string => trim((string) ($item['folder'] ?? '')), media_library_items(true));
    $folders = array_values(array_unique(array_filter($folders)));
    natcasesort($folders);
    return array_values($folders);
}

/** @return array<int, array<string, mixed>> */
function media_library_images(): array
{
    return array_values(array_filter(media_library_items(), static fn (array $item): bool => str_starts_with((string) ($item['mime'] ?? ''), 'image/')));
}

