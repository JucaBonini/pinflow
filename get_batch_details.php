<?php
// get_batch_details.php - Retrieve details of a specific batch and its associated pins

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

$batchId = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($batchId)) {
    echo json_encode(['success' => false, 'error' => 'Missing batch ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM batches WHERE id = ?");
    $stmt->execute([$batchId]);
    $batch = $stmt->fetch();
    
    if (!$batch) {
        echo json_encode(['success' => false, 'error' => 'Batch not found']);
        exit;
    }
    
    $stmtPins = $pdo->prepare("SELECT * FROM pins WHERE batch_id = ?");
    $stmtPins->execute([$batchId]);
    $pins = $stmtPins->fetchAll();
    
    echo json_encode([
        'success' => true,
        'batch' => $batch,
        'pins' => $pins
    ]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
