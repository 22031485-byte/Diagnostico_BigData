<?php

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: '3306';
$db_name = getenv('DB_DATABASE') ?: 'employeesdb';
$db_user = getenv('DB_USERNAME') ?: 'bigdata';
$db_pass = getenv('DB_PASSWORD') ?: 'BigData#$';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    if (isset($_GET['action'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    } else {
        echo "<div style='font-family:sans-serif;padding:2rem;color:#b00'>
                <h3>Error de conexión a la base de datos</h3>
                <p>" . htmlspecialchars($e->getMessage()) . "</p>
              </div>";
    }
    exit;
}