<?php
// admin_users.php - User management CRUD API for Super Admins

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Ensure user is logged in and is a super_admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acesso negado. Apenas administradores podem acessar esta API.']);
    exit;
}

require_once 'db.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
        // List users with plan names
        $stmt = $pdo->query("
            SELECT u.id, u.username, u.name, u.role, u.plan_id, p.name as plan_name 
            FROM users u 
            LEFT JOIN plans p ON u.plan_id = p.id 
            ORDER BY u.id ASC
        ");
        $users = $stmt->fetchAll();
        echo json_encode(['success' => true, 'users' => $users]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($action === 'create') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $role = isset($_POST['role']) ? trim($_POST['role']) : 'user';
            $planId = isset($_POST['plan_id']) && $_POST['plan_id'] !== '' ? (int)$_POST['plan_id'] : null;

            if (empty($username) || empty($password) || empty($name)) {
                echo json_encode(['success' => false, 'error' => 'Todos os campos são obrigatórios para a criação de usuários.']);
                exit;
            }

            // Check if username already exists
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $checkStmt->execute([$username]);
            if ($checkStmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'error' => 'Este nome de usuário já está em uso.']);
                exit;
            }

            // Create user
            $passHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, name, role, plan_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $passHash, $name, $role, $planId]);

            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'update') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $role = isset($_POST['role']) ? trim($_POST['role']) : 'user';
            $planId = isset($_POST['plan_id']) && $_POST['plan_id'] !== '' ? (int)$_POST['plan_id'] : null;
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($id) || empty($username) || empty($name)) {
                echo json_encode(['success' => false, 'error' => 'Campos obrigatórios ausentes.']);
                exit;
            }

            // Check if username is used by another user
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
            $checkStmt->execute([$username, $id]);
            if ($checkStmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'error' => 'Este nome de usuário já está em uso por outro usuário.']);
                exit;
            }

            // Prevent self-demotion
            if ($id === (int)$_SESSION['user_id'] && $role !== 'super_admin') {
                echo json_encode(['success' => false, 'error' => 'Você não pode remover seus próprios privilégios de administrador.']);
                exit;
            }

            if (!empty($password)) {
                $passHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, name = ?, role = ?, plan_id = ? WHERE id = ?");
                $stmt->execute([$username, $passHash, $name, $role, $planId, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, name = ?, role = ?, plan_id = ? WHERE id = ?");
                $stmt->execute([$username, $name, $role, $planId, $id]);
            }

            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'delete') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'ID do usuário não especificado.']);
                exit;
            }

            // Prevent self-deletion
            if ($id === (int)$_SESSION['user_id']) {
                echo json_encode(['success' => false, 'error' => 'Você não pode excluir sua própria conta enquanto estiver conectado.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true]);
            exit;
        }
    }

    echo json_encode(['success' => false, 'error' => 'Ação ou método inválido.']);
    exit;

} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados: ' . $e->getMessage()]);
    exit;
}
