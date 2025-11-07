<?php
// login_safe.php (VERSI AMAN)
session_start();

$dsn = 'mysql:host=127.0.0.1;port=3307;dbname=praktek_sqli;charset=utf8mb4';
$dbUser = 'root';
$dbPass = '';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // --- prepared statement (AMAN) ---
        $stmt = $pdo->prepare("SELECT id, username, password_hash, full_name FROM users_safe WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['demo_mode'] = 'safe';
            header('Location: dashboard.php');
            exit;
        } else {
            $message = 'Username atau password salah.';
        }
    } catch (PDOException $e) {
        $message = 'Terjadi kesalahan server.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login (Aman)</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --bg: #0f1724;
      --card: #0b1220;
      --accent: #3dd7c9;
      --text: #e6eef8;
      --muted: #98a8c6;
      --danger: #ff6b6b;
      --radius: 12px;
      --shadow: 0 10px 30px rgba(2,6,23,0.6);
    }

    body {
      font-family: "Inter", Arial, sans-serif;
      background: linear-gradient(180deg, #061021 0%, #071431 60%);
      color: var(--text);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 30px;
    }

    .container {
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(0,0,0,0.05));
      padding: 36px 40px;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid rgba(255,255,255,0.06);
      width: 100%;
      max-width: 420px;
    }

    h2 {
      text-align: center;
      color: var(--accent);
      font-size: 1.8em;
      margin-bottom: 24px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: 600;
      margin-top: 10px;
      color: var(--text);
    }

    input {
      padding: 10px;
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 8px;
      margin-top: 6px;
      background: rgba(255,255,255,0.05);
      color: var(--text);
      font-size: 15px;
      transition: all 0.3s ease;
    }

    input:focus {
      outline: none;
      border-color: var(--accent);
      background: rgba(255,255,255,0.08);
    }

    button {
      margin-top: 22px;
      padding: 12px;
      background: var(--accent);
      color: #001;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.25s ease;
    }

    button:hover {
      background: #2cbfb2;
      transform: translateY(-2px);
    }

    .message {
      color: var(--danger);
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.05);
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 10px;
    }

    .back-btn {
      display: inline-block;
      text-align: center;
      text-decoration: none;
      width: 100%;
      padding: 10px 0;
      background: #4CAF50;
      color: #fff;
      border-radius: 8px;
      font-weight: 700;
      margin-top: 18px;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .back-btn:hover {
      background: #45a049;
      transform: translateY(-2px);
    }

    .note {
      text-align: center;
      font-size: 13px;
      color: var(--muted);
      margin-top: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🔒 Login — Versi Aman</h2>

    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <label>Username</label>
      <input type="text" name="username" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit">Login</button>
    </form>

    <a href="index.php" class="back-btn">⬅ Kembali</a>

    <p class="note">Form ini menggunakan <b>PDO + Prepared Statement</b> untuk mencegah SQL Injection.</p>
  </div>
</body>
</html>
