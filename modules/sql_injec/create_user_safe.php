<?php
session_start();

$dsn = 'mysql:host=127.0.0.1;port=3307;dbname=praktek_sqli;charset=utf8mb4';
$dbUser = 'root';
$dbPass = ''; // sesuaikan jika perlu

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        $errors[] = 'Token CSRF tidak valid.';
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['full_name'] ?? '');

    if ($username === '' || $password === '') {
        $errors[] = 'Username dan password wajib diisi.';
    } else {
        if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
            $errors[] = 'Username hanya boleh huruf, angka, underscore; 3–30 karakter.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter.';
        }
    }

    if (empty($errors)) {
        try {
            $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $stmt = $pdo->prepare("SELECT id FROM users_safe WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $errors[] = 'Username sudah terdaftar. Pilih username lain.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users_safe (username, password_hash, full_name) VALUES (?, ?, ?)");
                $stmt->execute([$username, $passwordHash, $fullname]);
                $message = "✅ User aman berhasil dibuat: " . htmlspecialchars($username);
                $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
            }
        } catch (PDOException $e) {
            $errors[] = 'Terjadi kesalahan server. Coba lagi nanti.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Create User (SAFE)</title>
  <style>
    :root {
      --bg: #0f1724;
      --panel: #0b1220;
      --text: #e6eef8;
      --muted: #9aaac8;
      --accent: #3dd7c9;
      --success: #4CAF50;
      --error: #ff6b6b;
      --radius: 10px;
      --shadow: 0 10px 30px rgba(2,6,23,0.6);
      font-family: "Inter", Arial, sans-serif;
    }
    body {
      background: linear-gradient(180deg,#061021 0%, #071431 60%);
      color: var(--text);
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 40px 20px;
    }
    .container {
      background: var(--panel);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      width: 100%;
      max-width: 480px;
      padding: 28px 26px;
      border: 1px solid rgba(255,255,255,0.05);
    }
    h2 {
      text-align: center;
      margin-bottom: 24px;
      color: var(--accent);
      font-size: 22px;
    }
    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      color: var(--text);
    }
    input {
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid rgba(255,255,255,0.1);
      background: rgba(255,255,255,0.04);
      color: var(--text);
      margin-bottom: 16px;
      font-size: 14px;
    }
    input:focus {
      outline: none;
      border-color: var(--accent);
      background: rgba(255,255,255,0.06);
    }
    button {
      width: 100%;
      background: var(--accent);
      border: none;
      padding: 12px;
      border-radius: 8px;
      color: #001;
      font-weight: 700;
      cursor: pointer;
      transition: transform 0.1s ease;
    }
    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(61,215,201,0.3);
    }
    .msg {
      background: rgba(255,255,255,0.03);
      border-left: 4px solid var(--success);
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 16px;
      color: var(--success);
      font-weight: 600;
    }
    .errbox {
      background: rgba(255,255,255,0.03);
      border-left: 4px solid var(--error);
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 16px;
      color: var(--error);
    }
    .note {
      font-size: 13px;
      color: var(--muted);
      margin-top: 16px;
      text-align: center;
      line-height: 1.5;
    }
    .back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--muted);
      text-decoration: none;
      margin-bottom: 16px;
      font-weight: 600;
      transition: color 0.2s;
    }
    .back:hover {
      color: var(--accent);
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="index.php" class="back">← Kembali</a>

    <h2>🛡️ Create User (Versi Aman)</h2>

    <?php if ($message): ?>
      <div class="msg"><?= $message ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="errbox">
        <ul style="margin-left:18px;">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      
      <label>Username (3–30 karakter, huruf/angka/_)</label>
      <input type="text" name="username" required value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">

      <label>Password (minimal 8 karakter)</label>
      <input type="password" name="password" required>

      <label>Nama Lengkap (opsional)</label>
      <input type="text" name="full_name" value="<?= isset($fullname) ? htmlspecialchars($fullname) : '' ?>">

      <button type="submit">Buat User (Aman)</button>
    </form>

    <p class="note">
      Form ini telah dilengkapi validasi input, token CSRF, dan penyimpanan password dengan <strong>password_hash()</strong>.<br>
      <em>Gunakan untuk praktikum keamanan basis data (SQL Injection Safe).</em>
    </p>
  </div>
</body>
</html>
