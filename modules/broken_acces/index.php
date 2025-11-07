<?php
require 'config.php';
// Reset session lama yang mungkin masih menyimpan string
if (isset($_SESSION['user']) && is_string($_SESSION['user'])) {
    unset($_SESSION['user']);
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: white;
            flex: 1 1 300px; /* Flex item with a minimum width */
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .vulnerable {
            border: 1px solid #c00;
        }

        .safe {
            border: 1px solid #0a0;
        }

        .card h3 {
            margin-top: 0;
        }

        .card p {
            color: #666;
        }

        .card a {
            text-decoration: none;
            color: #4fd1c5;
            font-weight: bold;
        }

        .card a:hover {
            text-decoration: underline;
        }

        .logout {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Dashboard — <?= htmlspecialchars($user['username']) ?></h1>

    <div class="container">
        <div class="card vulnerable">
            <h3>VULNERABLE AREA</h3>
            <p>Contoh Broken Access Control (IDOR) — tanpa validasi ownership.</p>
            <a href="vuln/list.php">Masuk ke area VULN</a>
        </div>

        <div class="card safe">
            <h3>SAFE AREA</h3>
            <p>Versi aman dengan UUID + Token + Ownership Check.</p>
            <a href="safe/list.php">Masuk ke area SAFE</a>
        </div>
    </div>

    <p class="logout"><a href="logout.php">Logout</a></p>
</body>
</html>
