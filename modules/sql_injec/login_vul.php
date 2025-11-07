<?php
// login_vul.php  (VERSI RENTAN — DEMO)
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

        // --- pola rentan: concatenation langsung dengan input user ---
        $sql = "SELECT id, username, password, full_name FROM users_vul
                WHERE username = '" . $username . "' AND password = '" . $password . "'";
        $stmt = $pdo->query($sql);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['demo_mode'] = 'vul';
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
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login — VERSI RENTAN (Demo)</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    * { box-sizing: border-box; margin:0; padding:0; }
    :root{
      --bg: #0f1724;
      --card: #0b1220;
      --accent: #3dd7c9;
      --text: #e6eef8;
      --muted: #98a8c6;
      --danger: #ff6b6b;
      --radius: 12px;
      --shadow: 0 10px 30px rgba(2,6,23,0.6);
      font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
    }
    html,body{height:100%}
    body{
      background: linear-gradient(180deg,#061021 0%, #071431 60%);
      color:var(--text);
      display:flex;
      align-items:center;
      justify-content:center;
      padding:28px;
      -webkit-font-smoothing:antialiased;
    }

    .container{
      width:100%;
      max-width:420px;
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(0,0,0,0.05));
      border-radius:var(--radius);
      padding:34px 28px;
      border:1px solid rgba(255,255,255,0.06);
      box-shadow:var(--shadow);
    }

    h2{
      color:var(--accent);
      text-align:center;
      margin-bottom:18px;
      font-size:1.6rem;
    }

    form{display:flex;flex-direction:column;gap:10px}

    label{color:var(--text);font-weight:600;font-size:0.95rem}

    input[type="text"], input[type="password"]{
      padding:10px 12px;
      border-radius:8px;
      border:1px solid rgba(255,255,255,0.08);
      background: rgba(255,255,255,0.04);
      color:var(--text);
      font-size:15px;
      transition: all .18s ease;
    }
    input:focus{
      outline:none;
      border-color:var(--accent);
      background: rgba(255,255,255,0.07);
    }

    .btn {
      margin-top:14px;
      padding:12px;
      border-radius:8px;
      border:none;
      background:var(--accent);
      color:#001;
      font-weight:700;
      cursor:pointer;
      transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn:hover{transform:translateY(-3px); box-shadow:0 8px 20px rgba(14,165,181,0.08)}

    .message {
      background: rgba(255,255,255,0.04);
      color:var(--danger);
      padding:10px 12px;
      border-radius:8px;
      border:1px solid rgba(255,255,255,0.04);
      margin-bottom:10px;
      text-align:center;
    }

    .back-btn{
      display:inline-block;
      margin-top:12px;
      width:100%;
      text-align:center;
      padding:10px 12px;
      border-radius:8px;
      text-decoration:none;
      color:var(--text);
      background:transparent;
      border:1px solid rgba(255,255,255,0.06);
      font-weight:700;
      transition:all .12s ease;
    }
    .back-btn:hover{color:var(--accent); transform:translateY(-2px)}

    .note{
      margin-top:12px;
      color:var(--muted);
      font-size:13px;
      text-align:center;
    }

    @media (max-width:480px){
      .container{padding:22px}
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🔓 Login — VERSI RENTAN (Demo)</h2>

    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <label>Username</label>
      <input name="username" type="text" required>

      <label>Password</label>
      <input name="password" type="password" required>

      <button class="btn" type="submit">Login</button>
    </form>

    <a class="back-btn" href="index.php">← Kembali</a>

    <div class="note">Catatan: contoh ini <strong style="color:var(--danger)">sengaja rentan</strong> (concatenation, password plaintext). Jalankan hanya di lingkungan lokal yang terisolasi.</div>
  </div>
</body>
</html>
