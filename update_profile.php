<?php
// update_profile.php - Handles user profile changes (name, nick, avatar photo upload)

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
    echo json_encode(['success' => false, 'error' => 'Não autorizado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
    exit;
}

$userId = $_SESSION['user_id'];
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

if (empty($name) || empty($username)) {
    echo json_encode(['success' => false, 'error' => 'Nome e Nick/Usuário são obrigatórios.']);
    exit;
}

// Validate nick/username format (no spaces, only letters, numbers, hyphens, underscores)
if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
    echo json_encode(['success' => false, 'error' => 'O Nick/Usuário deve conter apenas letras, números, hífen e sublinhado. Sem espaços.']);
    exit;
}

require_once 'db.php';

try {
    // Check if the username is already taken by another user
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
    $checkStmt->execute([$username, $userId]);
    if ($checkStmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'error' => 'Este Nick/Usuário já está sendo utilizado.']);
        exit;
    }

    $avatarPath = null;

    // Handle avatar file upload if provided
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Allowed extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            // Directory where avatars are saved
            $uploadFileDir = __DIR__ . '/imagens-pins/avatars/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            // Generate a unique filename using userId to overwrite old photo
            $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;
            
            // Remove previous avatar files if exist
            $existingAvatars = glob($uploadFileDir . 'avatar_' . $userId . '_*');
            foreach ($existingAvatars as $oldFile) {
                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $avatarPath = 'imagens-pins/avatars/' . $newFileName;
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao salvar a imagem do avatar no servidor.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Formato de arquivo inválido. Apenas JPG, JPEG, PNG e WEBP são permitidos.']);
            exit;
        }
    }

    // Prepare update query
    if ($avatarPath !== null) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, avatar_path = ? WHERE id = ?");
        $stmt->execute([$name, $username, $avatarPath, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ? WHERE id = ?");
        $stmt->execute([$name, $username, $userId]);
    }

    // Update active session details
    $_SESSION['username'] = $username;
    $_SESSION['name'] = $name;

    echo json_encode(['success' => true]);
    exit;

} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erro interno de banco de dados: ' . $e->getMessage()]);
    exit;
}
