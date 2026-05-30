<?php
// db.php - Database connection and schema initialization

$host = '127.0.0.1';
$db   = 'desc_pinflow';
$user = 'desc_pinflow';
$pass = 'fNIH%xM#M1cHv3L*';
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

} catch (\PDOException $e) {
     header('Content-Type: application/json');
     echo json_encode(['success' => false, 'error' => 'Database connection or schema init failed: ' . $e->getMessage()]);
     exit;
}
