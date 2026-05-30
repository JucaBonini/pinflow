<?php
// delete_batch.php - Delete batch from MySQL and remove its image folder on server

header('Content-Type: application/json');
require_once 'db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$batchId = isset($_POST['id']) ? $_POST['id'] : '';

if (empty($batchId)) {
    echo json_encode(['success' => false, 'error' => 'Missing batch ID']);
    exit;
}

try {
    // Delete files first
    $batchDir = __DIR__ . '/imagens-pins/' . $batchId;
    if (is_dir($batchDir)) {
        // Delete all files in the directory
        $files = glob($batchDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($batchDir);
    }
    
    // Delete from DB (foreign key constraints cascade deletes the pins)
    $stmt = $pdo->prepare("DELETE FROM batches WHERE id = ?");
    $stmt->execute([$batchId]);
    
    echo json_encode(['success' => true]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
