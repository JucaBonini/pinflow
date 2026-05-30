<?php
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

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$ignored = [
    '.',
    '..',
    '.git',
    '.github',
    'configura_es_pinautomate',
    'dashboard_pinautomate',
    'hist_rico_de_lotes_pinautomate',
    'novo_lote_pinautomate',
    'vibrant_automation_utility'
];

$folders = [];
$dir = __DIR__;

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (is_dir($dir . '/' . $file) && !in_array($file, $ignored)) {
                $folders[] = $file;
            }
        }
        closedir($dh);
    }
}

// Sort alphabetically
sort($folders);

echo json_encode(['success' => true, 'folders' => $folders]);
