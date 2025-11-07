<?php
session_start();

$host = '127.0.0.1';  // gunakan IP langsung, bukan 'localhost'
$port = '3307';       // port baru MySQL kamu
$db   = 'demo_app';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit();
    }
}
?>
