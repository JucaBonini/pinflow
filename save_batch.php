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

    $newOriginalIndex = 0;
    
    // For each pin record
    foreach ($pinsData as $index => $pin) {
        $pinId = 'pin_' . $batchId . '_' . $index . '_' . rand(100, 999);
        $finalImagePath = '';
        
        $paddedIndex = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        $origFileName = '';
        $genFileName = 'pin_' . $paddedIndex . '.jpg';
        
        $targetGenFile = $batchDir . '/' . $genFileName;

        // 1. Move the generated image (it is ALWAYS uploaded by the client)
        if (isset($_FILES['generated_images']) && isset($_FILES['generated_images']['tmp_name'][$index])) {
            $genTmpName = $_FILES['generated_images']['tmp_name'][$index];
            if (!move_uploaded_file($genTmpName, $targetGenFile)) {
                throw new Exception("Failed to move generated file at index " . $index);
            }
        } else {
            throw new Exception("Generated file upload missing at index " . $index);
        }

        // 2. Save or copy the original image
        if ($pin['is_new']) {
            // It is a newly uploaded image
            if (isset($_FILES['original_images']) && isset($_FILES['original_images']['tmp_name'][$newOriginalIndex])) {
                $origTmpName = $_FILES['original_images']['tmp_name'][$newOriginalIndex];
                $origName = $_FILES['original_images']['name'][$newOriginalIndex];
                $origExt = pathinfo($origName, PATHINFO_EXTENSION);
                if (empty($origExt)) $origExt = 'jpg';
                
                $origFileName = 'original_' . $paddedIndex . '.' . $origExt;
                $targetOrigFile = $batchDir . '/' . $origFileName;

                if (move_uploaded_file($origTmpName, $targetOrigFile)) {
                    $finalImagePath = 'imagens-pins/' . $batchId . '/' . $origFileName;
                } else {
                    throw new Exception("Failed to move uploaded original file at index " . $index);
                }
                $newOriginalIndex++;
            } else {
                throw new Exception("Uploaded original file not found for new pin at index " . $index);
            }
        } else {
            // It is an existing image from a cloned batch
            $serverUrl = $pin['serverImageUrl'];
            if (!empty($serverUrl)) {
                // Parse relative path from URL
                $pathParts = parse_url($serverUrl, PHP_URL_PATH);
                $segments = explode('/imagens-pins/', $pathParts);
                
                if (count($segments) > 1) {
                    $relativeSourcePath = 'imagens-pins/' . $segments[1];
                    $sourceFile = __DIR__ . '/' . $relativeSourcePath;

                    if (file_exists($sourceFile)) {
                        $origExt = pathinfo($sourceFile, PATHINFO_EXTENSION);
                        if (empty($origExt)) $origExt = 'jpg';
                        
                        $origFileName = 'original_' . $paddedIndex . '.' . $origExt;
                        $targetOrigFile = $batchDir . '/' . $origFileName;

                        if (copy($sourceFile, $targetOrigFile)) {
                            $finalImagePath = 'imagens-pins/' . $batchId . '/' . $origFileName;
                        } else {
                            throw new Exception("Failed to copy existing original file " . $sourceFile);
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
