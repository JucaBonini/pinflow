<?php
// admin_plans.php - Plan management CRUD API for Super Admins

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Ensure user is logged in and is a super_admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acesso negado.']);
    exit;
}

require_once 'db.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
        $stmt = $pdo->query("SELECT * FROM plans ORDER BY id ASC");
        $plans = $stmt->fetchAll();
        echo json_encode(['success' => true, 'plans' => $plans]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($action === 'create') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.00;
            $maxPins = isset($_POST['max_pins_per_batch']) ? (int)$_POST['max_pins_per_batch'] : 10;

            if (empty($name)) {
                echo json_encode(['success' => false, 'error' => 'Nome do plano é obrigatório.']);
                exit;
            }

            // Check if name already exists
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM plans WHERE name = ?");
            $checkStmt->execute([$name]);
            if ($checkStmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'error' => 'Já existe um plano com este nome.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO plans (name, price, max_pins_per_batch) VALUES (?, ?, ?)");
            $stmt->execute([$name, $price, $maxPins]);

            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'update') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.00;
            $maxPins = isset($_POST['max_pins_per_batch']) ? (int)$_POST['max_pins_per_batch'] : 10;

            if (empty($id) || empty($name)) {
                echo json_encode(['success' => false, 'error' => 'Campos obrigatórios ausentes.']);
                exit;
            }

            // Check if name already exists for other plans
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM plans WHERE name = ? AND id != ?");
            $checkStmt->execute([$name, $id]);
            if ($checkStmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'error' => 'Já existe outro plano com este nome.']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE plans SET name = ?, price = ?, max_pins_per_batch = ? WHERE id = ?");
            $stmt->execute([$name, $price, $maxPins, $id]);

            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'delete') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'ID do plano não especificado.']);
                exit;
            }

            // Check if any users are assigned to this plan
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE plan_id = ?");
            $checkStmt->execute([$id]);
            if ($checkStmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'error' => 'Não é possível excluir este plano pois existem usuários vinculados a ele. Altere o plano dos usuários primeiro.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM plans WHERE id = ?");
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
