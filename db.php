<?php
/* ============================================
   Practical 8: PDO Database Connection
   Secure connection with error handling
   ============================================ */

$host = 'localhost';
$dbname = 'student_hub';
$username = 'root';
$password = '';

try {
    // PDO Connection (Intermediate requirement)
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
