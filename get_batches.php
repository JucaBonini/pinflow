<?php
// get_batches.php - Retrieve all batches from MySQL database

header('Content-Type: application/json');
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM batches ORDER BY date DESC");
    $batches = $stmt->fetchAll();
    echo json_encode(['success' => true, 'batches' => $batches]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
