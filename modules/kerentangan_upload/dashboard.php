<?php
require 'config.php';
require_login();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Demo App</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #74ABE2, #5563DE);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-container {
            background-color: white;
            width: 480px;
            padding: 35px 45px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        h2 {
            color: #444;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        nav {
            margin-top: 20px;
        }

        nav a {
            background-color: #5563DE;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s;
            margin: 0 5px;
            display: inline-block;
        }

        nav a:hover {
            background-color: #4450C9;
        }

        .logout {
            background-color: #E74C3C;
        }

        .logout:hover {
            background-color: #C0392B;
        }

        p {
            color: #555;
            font-size: 15px;
            margin-top: 15px;
        }

        .username {
            color: #5563DE;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Selamat datang, <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>!</h1>
        
        <nav>
            <a href="artikel_vul.php">📝 Artikel (Versi RENTAN)</a>
            <a href="artikel_safe.php">✅ Artikel (Versi AMAN)</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>

        <h2>Menu Utama</h2>
        <p>Ini adalah halaman dashboard setelah login.  
        Silakan pilih menu di atas untuk melanjutkan.</p>
    </div>
</body>
</html>
