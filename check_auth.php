<?php
// check_auth.php - Checks session status and returns logged-in user profile details

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    require_once 'db.php';
    try {
        $stmt = $pdo->prepare("
            SELECT u.role, u.plan_id, u.avatar_path, p.name as plan_name, p.max_pins_per_batch 
            FROM users u 
            LEFT JOIN plans p ON u.plan_id = p.id 
            WHERE u.id = ? 
            LIMIT 1
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $info = $stmt->fetch();

        if ($info) {
            $_SESSION['role'] = $info['role'];
            $_SESSION['plan_id'] = $info['plan_id'];
        }

        echo json_encode([
            'logged_in' => true,
            'user_id'   => $_SESSION['user_id'],
            'username'  => $_SESSION['username'],
            'name'      => $_SESSION['name'],
            'role'      => $info ? $info['role'] : 'user',
            'plan_id'   => $info ? $info['plan_id'] : null,
            'plan_name' => $info ? ($info['plan_name'] ? $info['plan_name'] : 'Nenhum') : 'Nenhum',
            'max_pins_per_batch' => $info ? (int)$info['max_pins_per_batch'] : 5,
            'avatar_path' => $info ? $info['avatar_path'] : null
        ]);
    } catch (\Exception $e) {
        echo json_encode([
            'logged_in' => true,
            'user_id'   => $_SESSION['user_id'],
            'username'  => $_SESSION['username'],
            'name'      => $_SESSION['name'],
            'role'      => isset($_SESSION['role']) ? $_SESSION['role'] : 'user',
            'plan_id'   => isset($_SESSION['plan_id']) ? $_SESSION['plan_id'] : null,
            'plan_name' => 'Nenhum',
            'max_pins_per_batch' => 5
        ]);
    }
} else {
    echo json_encode([
        'logged_in' => false
    ]);
}
exit;
