<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username']);
    $p = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
    $stmt->execute([':u'=>$u]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // if ($user && password_verify($p, $user['password'])) {
    if ($user && $user['password']) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id'=>$user['id'],'username'=>$user['username'],'role'=>$user['role']];
        header('Location: index.php'); exit;
    } else $err = "Login gagal.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .input:focus {
            border-color: #4fd1c5;
            outline: none;
        }

        .btn {
            padding: 10px;
            background-color: #4fd1c5;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #38b2ac;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .back-button {
            margin-top: 15px;
            text-align: center;
        }

        .back-button a {
            text-decoration: none;
            color: #4fd1c5;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="post" class="login-form">
            <h3>Login</h3>
            <?=isset($err) ? "<p class='error'>$err</p>" : ""?>
            <input name="username" placeholder="username" class="input" required><br>
            <input name="password" type="password" placeholder="password" class="input" required><br>
            <button class="btn">Login</button>
        </form>
        <div class="back-button">
            <a href="../../dashboard.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
