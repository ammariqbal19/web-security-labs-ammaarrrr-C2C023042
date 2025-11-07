<?php
// create_user_vul_form.php
// DEMO ONLY: VULNERABLE user creation form — gunakan hanya di lab lokal/VM

$dsn = 'mysql:host=127.0.0.1;port=3307;dbname=praktek_sqli;charset=utf8mb4';
$dbUser = 'root';
$dbPass = ''; // sesuaikan jika perlu

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $fullname = $_POST['full_name'] ?? '';

    try {
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // VULNERABLE: menyimpan password plaintext and concatenation query
        $sql = "INSERT INTO users_vul (username, password, full_name) VALUES ('" 
                . $username . "', '" . $password . "', '" . $fullname . "')";
        $pdo->exec($sql);

        $message = "User rentan berhasil dibuat: " . htmlspecialchars($username);

    } catch (PDOException $e) {
        $message = "Terjadi kesalahan server (demo).";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Create User (VULNERABLE)</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    :root {
      --bg: #0f1724;
      --panel: #0b1220;
      --text: #e6eef8;
      --muted: #9aaac8;
      --accent: #3dd7c9;
      --danger: #ff6b6b;
      --radius: 10px;
      --shadow: 0 10px 30px rgba(2,6,23,0.6);
      font-family: "Inter", Arial, sans-serif;
    }
    html,body{height:100%;margin:0}
    body {
      background: linear-gradient(180deg,#061021 0%, #071431 60%);
      color: var(--text);
      display:flex;
      justify-content:center;
      align-items:flex-start;
      min-height:100vh;
      padding:40px 20px;
    }
    .container {
      background: var(--panel);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      width:100%;
      max-width: 480px;
      padding: 28px 26px;
      border: 1px solid rgba(255,255,255,0.05);
    }
    .back {
      display:inline-flex;
      align-items:center;
      gap:6px;
      color: var(--muted);
      text-decoration:none;
      margin-bottom:14px;
      font-weight:600;
      transition: color .15s;
    }
    .back:hover { color: var(--accent); }

    h2 {
      text-align:center;
      margin:0 0 18px 0;
      color: var(--accent);
      font-size:22px;
    }

    label {
      display:block;
      font-weight:600;
      margin-bottom:6px;
      color:var(--text);
    }
    input[type="text"], input[type="password"] {
      width:100%;
      padding:10px 12px;
      border-radius:8px;
      border:1px solid rgba(255,255,255,0.08);
      background: rgba(255,255,255,0.03);
      color:var(--text);
      margin-bottom:14px;
      font-size:14px;
    }
    input:focus {
      outline:none;
      border-color:var(--accent);
      background: rgba(255,255,255,0.06);
    }

    button {
      width:100%;
      padding:12px;
      background: var(--accent);
      border:none;
      border-radius:8px;
      color:#001;
      font-weight:700;
      cursor:pointer;
      transition: transform .12s ease;
    }
    button:hover { transform: translateY(-3px); box-shadow:0 6px 16px rgba(61,215,201,0.18); }

    .message {
      background: rgba(255,255,255,0.03);
      border-left:4px solid #7bd389;
      padding:10px 12px;
      border-radius:8px;
      color:#b7f0d0;
      margin-bottom:14px;
      font-weight:600;
    }

    .note {
      font-size:13px;
      color: var(--muted);
      margin-top:16px;
      text-align:center;
      line-height:1.4;
    }

    @media (max-width:520px){
      .container{padding:20px}
    }
  </style>
</head>
<body>
  <div class="container">
    <a class="back" href="index.php">← Kembali</a>

    <h2>CREATE USER — VERSI RENTAN (DEMO)</h2>

    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <label>Username</label>
      <input type="text" name="username" required>

      <label>Password</label>
      <input type="text" name="password" required>

      <label>Full name</label>
      <input type="text" name="full_name">

      <button type="submit">Buat User (vul)</button>
    </form>

    <p class="note">Catatan: contoh ini <strong style="color:var(--danger)">rentan</strong> — tidak gunakan di lingkungan publik.</p>
  </div>
</body>
</html>
