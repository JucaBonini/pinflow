<?php
// change_password.php - Securely updates the user password in MySQL database

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Enable error reporting for troubleshooting
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Ensure user is authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autorizado. Por favor, faça login novamente.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
    exit;
}

$currentPassword = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
$newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(['success' => false, 'error' => 'Por favor, preencha a senha atual e a nova senha.']);
    exit;
}

if (strlen($newPassword) < 6) {
    echo json_encode(['success' => false, 'error' => 'A nova senha deve ter no mínimo 6 caracteres.']);
    exit;
}

require_once 'db.php';

try {
    $userId = $_SESSION['user_id'];
    
    // Retrieve stored password hash
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'Usuário não encontrado.']);
        exit;
    }
    
    // Verify current password
    if (!password_verify($currentPassword, $user['password'])) {
        echo json_encode(['success' => false, 'error' => 'Senha atual incorreta.']);
        exit;
    }
    
    // Update hash
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $updateStmt->execute([$newHash, $userId]);
    
    echo json_encode(['success' => true]);
    exit;
    
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erro interno de banco de dados ao atualizar senha.']);
    exit;
}
