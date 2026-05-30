<?php
// login.php - Handles authentication validation and starts sessions

// Enable session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Enable error reporting for troubleshooting
ini_set('display_errors', 0);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Por favor, preencha todos os campos.']);
    exit;
}

require_once 'db.php';

try {
    // Check if the user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Authenticated! Store variables in $_SESSION
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'] ? $user['name'] : $user['username'];
        $_SESSION['role'] = $user['role'] ? $user['role'] : 'user';
        $_SESSION['plan_id'] = $user['plan_id'];

        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Usuário ou senha inválidos.']);
        exit;
    }
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erro interno de banco de dados.']);
    exit;
}
