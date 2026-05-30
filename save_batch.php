<?php
// save_batch.php - Save batch settings, copy/upload images, and store records in MySQL

header('Content-Type: application/json');
require_once 'db.php';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$batchId = isset($_POST['batch_id']) ? $_POST['batch_id'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$destUrl = isset($_POST['destUrl']) ? $_POST['destUrl'] : '';
$board = isset($_POST['board']) ? $_POST['board'] : '';
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
$baseUrl = isset($_POST['baseUrl']) ? $_POST['baseUrl'] : '';
$pinsDataJson = isset($_POST['pins_data']) ? $_POST['pins_data'] : '';

if (empty($batchId) || empty($name) || empty($destUrl) || empty($pinsDataJson)) {
    echo json_encode(['success' => false, 'error' => 'Missing required batch settings']);
    exit;
}

$pinsData = json_decode($pinsDataJson, true);
if (!is_array($pinsData)) {
    echo json_encode(['success' => false, 'error' => 'Invalid pins metadata format']);
    exit;
}

try {
    // Create base images-pins folder if not exists
    $baseDir = __DIR__ . '/imagens-pins';
    if (!is_dir($baseDir)) {
        mkdir($baseDir, 0755, true);
    }

    // Create batch folder
    $batchDir = $baseDir . '/' . $batchId;
    if (!is_dir($batchDir)) {
        mkdir($batchDir, 0755, true);
    }

    $pdo->beginTransaction();

    // Insert batch info
    $stmtBatch = $pdo->prepare("INSERT INTO batches (id, name, date, count, dest_url, board, keyword, base_url, status) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, 'Completed')");
    $stmtBatch->execute([
        $batchId,
        $name,
        count($pinsData),
        $destUrl,
        $board,
        $keyword,
        $baseUrl
    ]);

    $newFileIndex = 0;
    
    // For each pin record
    foreach ($pinsData as $index => $pin) {
        $pinId = 'pin_' . $batchId . '_' . $index . '_' . rand(100, 999);
        $finalImagePath = '';

        if ($pin['is_new']) {
            // It is a newly uploaded image
            if (isset($_FILES['images']) && isset($_FILES['images']['tmp_name'][$newFileIndex])) {
                $tmpName = $_FILES['images']['tmp_name'][$newFileIndex];
                $originalName = $_FILES['images']['name'][$newFileIndex];
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                if (empty($ext)) $ext = 'jpg';
                
                $fileName = $pinId . '.' . $ext;
                $targetFile = $batchDir . '/' . $fileName;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    // Set relative image path for database
                    $finalImagePath = 'imagens-pins/' . $batchId . '/' . $fileName;
                } else {
                    throw new Exception("Failed to move uploaded file " . $originalName);
                }
                $newFileIndex++;
            } else {
                throw new Exception("Uploaded file not found at index " . $newFileIndex);
            }
        } else {
            // It is an existing image from a cloned batch
            $serverUrl = $pin['serverImageUrl'];
            if (!empty($serverUrl)) {
                // Parse relative path from URL (e.g. .../imagens-pins/batch_xxx/pin_yyy.jpg)
                $pathParts = parse_url($serverUrl, PHP_URL_PATH);
                $segments = explode('/imagens-pins/', $pathParts);
                
                if (count($segments) > 1) {
                    $relativeSourcePath = 'imagens-pins/' . $segments[1];
                    $sourceFile = __DIR__ . '/' . $relativeSourcePath;

                    if (file_exists($sourceFile)) {
                        $ext = pathinfo($sourceFile, PATHINFO_EXTENSION);
                        if (empty($ext)) $ext = 'jpg';
                        $fileName = $pinId . '.' . $ext;
                        $targetFile = $batchDir . '/' . $fileName;

                        if (copy($sourceFile, $targetFile)) {
                            $finalImagePath = 'imagens-pins/' . $batchId . '/' . $fileName;
                        } else {
                            throw new Exception("Failed to copy existing file " . $sourceFile);
                        }
                    } else {
                        throw new Exception("Source file for reuse does not exist: " . $sourceFile);
                    }
                } else {
                    throw new Exception("Invalid server image URL segment: " . $serverUrl);
                }
            } else {
                throw new Exception("Missing serverImageUrl for reused pin");
            }
        }

        // Insert pin record
        $stmtPin = $pdo->prepare("INSERT INTO pins (id, batch_id, top_line_1, top_line_2, card_title, card_subtitle, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtPin->execute([
            $pinId,
            $batchId,
            $pin['topLine1'],
            $pin['topLine2'],
            $pin['cardTitle'],
            $pin['cardSubtitle'],
            $pin['description'],
            $finalImagePath
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Clean up folder if created
    if (isset($batchDir) && is_dir($batchDir)) {
        $files = glob($batchDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
        rmdir($batchDir);
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
