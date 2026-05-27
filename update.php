<?php
header('Content-Type: application/json');

// Enable error logging for troubleshooting
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Basic security check: only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$owner = isset($_POST['owner']) ? preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['owner']) : '';
$repo = isset($_POST['repo']) ? preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['repo']) : '';
$branch = isset($_POST['branch']) ? preg_replace('/[^a-zA-Z0-9-_.]/', '', $_POST['branch']) : 'main';

if (empty($owner) || empty($repo)) {
    echo json_encode(['success' => false, 'error' => 'Missing owner or repository name']);
    exit;
}

$zipUrl = "https://github.com/{$owner}/{$repo}/archive/refs/heads/{$branch}.zip";
$tempZip = __DIR__ . '/temp_update.zip';
$extractPath = __DIR__ . '/temp_extracted';

// Download zip file via cURL
$ch = curl_init($zipUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
// Add a user agent since GitHub API/Downloads require it
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
$zipContent = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$zipContent) {
    echo json_encode(['success' => false, 'error' => 'Failed to download zip from GitHub. HTTP code: ' . $httpCode]);
    exit;
}

file_put_contents($tempZip, $zipContent);

// Ensure ZipArchive is available
if (!class_exists('ZipArchive')) {
    unlink($tempZip);
    echo json_encode(['success' => false, 'error' => 'ZipArchive class not available in PHP on server']);
    exit;
}

// Extract ZIP
$zip = new ZipArchive();
if ($zip->open($tempZip) === TRUE) {
    if (!is_dir($extractPath)) {
        mkdir($extractPath, 0755, true);
    }
    $zip->extractTo($extractPath);
    $zip->close();
} else {
    unlink($tempZip);
    echo json_encode(['success' => false, 'error' => 'Failed to open ZIP archive']);
    exit;
}

// GitHub zips extract into a folder named "repo-branch" (e.g. "pinflow-main")
$extractedDirs = glob($extractPath . '/*', GLOB_ONLYDIR);
if (empty($extractedDirs)) {
    unlink($tempZip);
    rmdir($extractPath);
    echo json_encode(['success' => false, 'error' => 'Extracted folder not found']);
    exit;
}

$sourceDir = $extractedDirs[0]; // e.g., /temp_extracted/pinflow-main

// Helper to recursively copy files and folders
function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }
    while (($file = readdir($dir)) !== false) {
        if (($file !== '.') && ($file !== '..') && ($file !== '.git')) {
            if (is_dir($src . '/' . $file)) {
                recursiveCopy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Copy extracted assets to root directory
recursiveCopy($sourceDir, __DIR__);

// Helper to recursively delete temp directories
function recursiveDelete($dirPath) {
    if (!is_dir($dirPath)) return;
    $files = array_diff(scandir($dirPath), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dirPath/$file")) ? recursiveDelete("$dirPath/$file") : unlink("$dirPath/$file");
    }
    rmdir($dirPath);
}

// Clean up
unlink($tempZip);
recursiveDelete($extractPath);

echo json_encode(['success' => true]);
