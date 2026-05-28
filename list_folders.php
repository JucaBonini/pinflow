<?php
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
