<?php
// download_batch_zip.php - Pack and download batch generated images as ZIP on demand

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("Acesso negado. Por favor, faça login.");
}

$batchId = isset($_GET['batch_id']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['batch_id']) : '';

if (empty($batchId)) {
    http_response_code(400);
    die("ID de lote inválido.");
}

$batchDir = __DIR__ . '/imagens-pins/' . $batchId;

if (!is_dir($batchDir)) {
    http_response_code(404);
    die("A pasta de imagens deste lote não foi encontrada no servidor (pode ter sido excluída).");
}

// Find all generated images matching pin_*.jpg
$files = glob($batchDir . '/pin_*.jpg');

if (empty($files)) {
    http_response_code(404);
    die("Nenhuma imagem gerada foi encontrada para este lote.");
}

if (!class_exists('ZipArchive')) {
    http_response_code(500);
    die("A extensão ZipArchive do PHP não está ativada neste servidor.");
}

$zip = new ZipArchive();
// Create temporary zip in the batch folder to avoid temp folder permissions issues
$tempZipPath = $batchDir . '/download_' . time() . '.zip';

if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    foreach ($files as $file) {
        $filename = basename($file);
        $zip->addFile($file, $filename);
    }
    $zip->close();
    
    // Clear output buffer to prevent corrupted zip file
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Stream ZIP file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="lote_' . $batchId . '_imagens.zip"');
    header('Content-Length: ' . filesize($tempZipPath));
    header('Pragma: no-cache');
    header('Expires: 0');
    
    readfile($tempZipPath);
    unlink($tempZipPath); // Clean up temp file
    exit;
} else {
    http_response_code(500);
    die("Falha ao criar o arquivo ZIP no servidor.");
}
