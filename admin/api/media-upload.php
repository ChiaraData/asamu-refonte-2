<?php
declare(strict_types=1);

require_once __DIR__ . '/../_bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Méthode non autorisée.']);
    exit;
}
if (!admin_is_logged_in() || !admin_can('media')) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Connexion requise.']);
    exit;
}
if (!admin_verify_csrf()) {
    http_response_code(419);
    echo json_encode(['ok' => false, 'error' => 'Jeton de sécurité expiré. Recharge la page.']);
    exit;
}

$folder = admin_text($_POST['folder'] ?? '') ?: 'Non classé';
$uploaded = admin_store_media((array) ($_FILES['file'] ?? []), $folder);
if ($uploaded['error'] !== '' || $uploaded['path'] === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => $uploaded['error'] ?: 'Fichier absent.']);
    exit;
}

$originalName = admin_text($_FILES['file']['name'] ?? '');
$item = [
    'id' => 'media-' . bin2hex(random_bytes(8)),
    'path' => $uploaded['path'],
    'filename' => $uploaded['filename'],
    'original_name' => $originalName,
    'mime' => $uploaded['mime'],
    'size' => $uploaded['size'],
    'width' => $uploaded['width'],
    'height' => $uploaded['height'],
    'optimized' => $uploaded['optimized'],
    'folder' => $folder,
    'title' => admin_text($_POST['title'] ?? '') ?: pathinfo($originalName, PATHINFO_FILENAME),
    'alt' => admin_text($_POST['alt'] ?? ''),
    'description' => admin_text($_POST['description'] ?? ''),
    'focal_x' => 50,
    'focal_y' => 50,
    'created_at' => gmdate('c'),
    'updated_at' => gmdate('c'),
];
$library = media_library_read();
$library['items'][] = $item;
if (!media_library_write($library)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => admin_storage_error()]);
    exit;
}

admin_record_audit('Média ajouté via API', $originalName);
echo json_encode(['ok' => true, 'media' => $item], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

