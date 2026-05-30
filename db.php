<?php
// db.php - Database connection and schema initialization

$host = 'localhost';
$db   = 'pinflow';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // Initialize database schema if not exists
     $pdo->exec("CREATE TABLE IF NOT EXISTS batches (
         id VARCHAR(50) PRIMARY KEY,
         name VARCHAR(255) NOT NULL,
         date DATETIME NOT NULL,
         count INT NOT NULL,
         dest_url VARCHAR(500) NOT NULL,
         board VARCHAR(255) NOT NULL,
         keyword VARCHAR(255),
         base_url VARCHAR(500),
         status VARCHAR(50) DEFAULT 'Completed'
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

     $pdo->exec("CREATE TABLE IF NOT EXISTS pins (
         id VARCHAR(50) PRIMARY KEY,
         batch_id VARCHAR(50) NOT NULL,
         top_line_1 VARCHAR(100),
         top_line_2 VARCHAR(100),
         card_title VARCHAR(100),
         card_subtitle VARCHAR(100),
         description TEXT,
         image_path VARCHAR(500),
         FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

     // Create plans table
     $pdo->exec("CREATE TABLE IF NOT EXISTS plans (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(100) NOT NULL UNIQUE,
         price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
         max_pins_per_batch INT NOT NULL DEFAULT 10
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

     // Seed plans if empty
     $stmtPlansCheck = $pdo->query("SELECT COUNT(*) FROM plans");
     if ($stmtPlansCheck->fetchColumn() == 0) {
         $pdo->exec("INSERT INTO plans (name, price, max_pins_per_batch) VALUES 
             ('Gratuito', 0.00, 5),
             ('Profissional', 29.90, 30),
             ('Ilimitado', 99.90, 9999)
         ");
     }

     // Create users table
     $pdo->exec("CREATE TABLE IF NOT EXISTS users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         username VARCHAR(100) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL,
         name VARCHAR(100)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

     // Alter users table to add role if not exists
     try {
         $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'user'");
     } catch (\Exception $e) {
         // Column already exists, ignore
     }

     // Alter users table to add plan_id if not exists
     try {
         $pdo->exec("ALTER TABLE users ADD COLUMN plan_id INT DEFAULT NULL");
         $pdo->exec("ALTER TABLE users ADD FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL");
     } catch (\Exception $e) {
         // Column already exists, ignore
     }

     // Alter users table to add avatar_path if not exists
     try {
         $pdo->exec("ALTER TABLE users ADD COLUMN avatar_path VARCHAR(500) DEFAULT NULL");
     } catch (\Exception $e) {
         // Column already exists, ignore
     }

     // Seed admin user
     $stmtUserCheck = $pdo->query("SELECT COUNT(*) FROM users");
     if ($stmtUserCheck->fetchColumn() == 0) {
         $unlimitedPlanId = $pdo->query("SELECT id FROM plans WHERE name = 'Ilimitado'")->fetchColumn();
         $defaultPassHash = password_hash('admin123', PASSWORD_DEFAULT);
         $pdo->prepare("INSERT INTO users (username, password, name, role, plan_id) VALUES (?, ?, ?, ?, ?)")
             ->execute(['admin', $defaultPassHash, 'Administrador', 'super_admin', $unlimitedPlanId]);
     } else {
         // Ensure admin is super_admin and has Unlimited plan
         $unlimitedPlanId = $pdo->query("SELECT id FROM plans WHERE name = 'Ilimitado'")->fetchColumn();
         $pdo->prepare("UPDATE users SET role = 'super_admin', plan_id = ? WHERE username = 'admin'")
             ->execute([$unlimitedPlanId]);
     }

} catch (\PDOException $e) {
     header('Content-Type: application/json');
     echo json_encode(['success' => false, 'error' => 'Database connection or schema init failed: ' . $e->getMessage()]);
     exit;
}
