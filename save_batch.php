<?php
// save_batch.php - Save batch settings, copy/upload images, and store records in MySQL

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

// Fetch user plan limit
try {
    $stmtLimit = $pdo->prepare("
        SELECT p.max_pins_per_batch 
        FROM users u 
        LEFT JOIN plans p ON u.plan_id = p.id 
        WHERE u.id = ? 
        LIMIT 1
    ");
    $stmtLimit->execute([$_SESSION['user_id']]);
    $maxPins = $stmtLimit->fetchColumn();
    if ($maxPins === false || $maxPins === null) {
        $maxPins = 5; // Default fallback to Free limit
    }
} catch (Exception $e) {
    $maxPins = 5;
}

if (count($pinsData) > $maxPins) {
    echo json_encode(['success' => false, 'error' => 'Limite excedido. Seu plano atual permite gerar no máximo ' . $maxPins . ' pins por lote.']);
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

    // Extract the batch zip file containing all images
    if (isset($_FILES['batch_zip']) && isset($_FILES['batch_zip']['tmp_name'])) {
        $zipFile = $_FILES['batch_zip']['tmp_name'];
        $zip = new ZipArchive();
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($batchDir);
            $zip->close();
        } else {
            throw new Exception("Falha ao extrair o arquivo ZIP de lote enviado.");
        }
    } else {
        throw new Exception("Arquivo ZIP do lote não foi enviado pelo cliente.");
    }
    
    // For each pin record
    foreach ($pinsData as $index => $pin) {
        $pinId = 'pin_' . $batchId . '_' . $index . '_' . rand(100, 999);
        $finalImagePath = '';
        
        $paddedIndex = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        $origFileName = '';
        $genFileName = 'pin_' . $paddedIndex . '.jpg';
        
        $targetGenFile = $batchDir . '/' . $genFileName;

        // 1. Verify the generated image exists in extracted directory
        if (!file_exists($targetGenFile)) {
            throw new Exception("Generated file " . $genFileName . " missing in extracted ZIP");
        }

        // 2. Verify or copy the original image
        if ($pin['is_new']) {
            $origExt = isset($pin['originalExt']) ? preg_replace('/[^a-zA-Z0-9]/', '', $pin['originalExt']) : 'jpg';
            if (empty($origExt)) $origExt = 'jpg';
            
            $origFileName = 'original_' . $paddedIndex . '.' . $origExt;
            $targetOrigFile = $batchDir . '/' . $origFileName;

            if (file_exists($targetOrigFile)) {
                $finalImagePath = 'imagens-pins/' . $batchId . '/' . $origFileName;
            } else {
                throw new Exception("Original file " . $origFileName . " missing in extracted ZIP");
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
        $pinDestUrl = isset($pin['dest_url']) ? $pin['dest_url'] : (isset($pin['destUrl']) ? $pin['destUrl'] : null);
        $pinBoard = isset($pin['board']) ? $pin['board'] : null;

        $stmtPin = $pdo->prepare("INSERT INTO pins (id, batch_id, top_line_1, top_line_2, card_title, card_subtitle, description, image_path, dest_url, board) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtPin->execute([
            $pinId,
            $batchId,
            $pin['topLine1'],
            $pin['topLine2'],
            $pin['cardTitle'],
            $pin['cardSubtitle'],
            $pin['description'],
            $finalImagePath,
            $pinDestUrl,
            $pinBoard
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
