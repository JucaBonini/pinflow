<?php
// delete_batch.php - Delete batch from MySQL and remove its image folder on server

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
require_once 'db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$batchId = isset($_POST['id']) ? $_POST['id'] : '';

if (empty($batchId) || !preg_match('/^[a-zA-Z0-9_-]+$/', $batchId)) {
    echo json_encode(['success' => false, 'error' => 'Invalid or missing batch ID']);
    exit;
}

try {
    // Delete files first in a secure and complete manner
    $batchDir = __DIR__ . '/imagens-pins/' . $batchId;
    
    // Ensure the folder path is actually inside the expected parent directory
    $realBatchDir = realpath($batchDir);
    $realParentDir = realpath(__DIR__ . '/imagens-pins');
    
    if ($realBatchDir !== false && $realParentDir !== false && strpos($realBatchDir, $realParentDir) === 0) {
        if (is_dir($realBatchDir)) {
            // Delete all files in the directory
            $files = glob($realBatchDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            @rmdir($realBatchDir);
        }
    }
    
    // Delete from DB (foreign key constraints cascade deletes the pins)
    $stmt = $pdo->prepare("DELETE FROM batches WHERE id = ?");
    $stmt->execute([$batchId]);
    
    echo json_encode(['success' => true]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
