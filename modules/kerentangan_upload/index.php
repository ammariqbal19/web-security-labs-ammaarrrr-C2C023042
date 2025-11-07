<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login - Demo App</title>
    <style>
        :root{
            --bg1: #74ABE2;
            --bg2: #5563DE;
            --card: #ffffff;
            --accent: #5563DE;
            --accent-hover: #4450C9;
            --danger: #E74C3C;
            --muted: #666;
            --radius: 12px;
            --shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        *{box-sizing:border-box}
        body{
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, var(--bg1), var(--bg2));
            padding: 24px;
            color: #222;
        }

        .card {
            width: 380px;
            background: var(--card);
            border-radius: var(--radius);
            padding: 30px 26px;
            box-shadow: var(--shadow);
            animation: appear .45s ease;
        }

        @keyframes appear {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin: 0 0 8px 0;
            font-size: 22px;
            color: #222;
        }

        .lead {
            margin: 0 0 18px 0;
            color: var(--muted);
            font-size: 14px;
        }

        form { display:flex; flex-direction:column; gap:12px; }

        label {
            font-size: 13px;
            color: #333;
            font-weight:600;
            display:block;
            margin-bottom:6px;
        }

        input[type="text"],
        input[type="password"] {
            width:100%;
            padding:12px 14px;
            border-radius:10px;
            border:1px solid #d8dbe8;
            font-size:14px;
        }

        input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 6px 18px rgba(85,99,222,0.12);
        }

        .row { display:flex; align-items:center; justify-content:space-between; gap:10px; }

        .btn {
            background: var(--accent);
            color: #fff;
            padding:12px;
            border-radius:10px;
            border:none;
            cursor:pointer;
            font-weight:700;
            font-size:15px;
        }

        .btn:hover { background: var(--accent-hover); }

        .text-link {
            color: var(--accent);
            text-decoration:none;
            font-weight:600;
            font-size:14px;
        }

        .text-link:hover { text-decoration:underline; }

        .msg {
            padding:10px 12px;
            border-radius:10px;
            font-weight:600;
            font-size:14px;
        }

        .msg.error {
            background: #fdecea;
            color: var(--danger);
            border: 1px solid #f5b7b1;
        }

        .small {
            font-size:13px;
            color:var(--muted);
        }

        .footer {
            margin-top:14px;
            text-align:center;
            font-size:14px;
        }

        @media (max-width:420px){
            .card { width:100%; padding:22px; }
        }
    </style>
</head>
<body>
    <div class="card" role="main">
        <h2>Masuk ke Demo App</h2>
        <p class="lead">Masukkan username dan password untuk melanjutkan.</p>

        <?php if (isset($error)): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off" novalidate>
            <div>
                <label for="username">Username</label>
                <input id="username" name="username" type="text" required autofocus>
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="row">
                <button type="submit" class="btn">Login</button>
                <a class="text-link" href="register.php">Daftar</a>
            </div>
        </form>

        <div class="footer small">
            <p>Belum punya akun? <a href="register.php" class="text-link">Daftar sekarang</a></p>
        </div>
    </div>
</body>
</html>
