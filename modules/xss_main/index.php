<?php
// register.php
require 'auth_simple.php';
$pdo = pdo_connect();
$msg = '';
$err = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');

    if($username && $password){
        try {
            // lab: simpan plaintext, di produksi wajib password_hash()
            $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name) VALUES (:u,:p,:n)");
            $stmt->execute([':u'=>$username, ':p'=>$password, ':n'=>$full_name]);
            $msg = "User berhasil didaftarkan. Silakan login.";
        } catch (Exception $e) {
            $err = "Registrasi gagal: kemungkinan username sudah dipakai.";
        }
    } else {
        $err = "Username & password wajib diisi.";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register — Lab</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #0d1b2a, #1b263b, #415a77);
        color: #e0efff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .card-register {
        background-color: #1e2a3a;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 420px;
        color: #dce9ff;
    }

    .brand {
        width: 72px;
        height: 72px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #3498db, #2980b9);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
        font-weight: 700;
        font-size: 20px;
        color: white;
        margin-bottom: 10px;
    }

    h4.card-title {
        color: #5dade2;
        font-weight: bold;
        margin-bottom: 6px;
    }

    small.text-muted {
        color: #a7c7ff !important;
    }

    .form-label {
        color: #9ecbff;
        font-weight: 600;
    }

    .form-control {
        background-color: #243447;
        border: 1px solid #34495e;
        border-radius: 8px;
        color: #e0efff;
        padding: 10px;
        font-size: 15px;
        transition: border-color 0.3s, background-color 0.3s;
    }

    .form-control:focus {
        border-color: #3498db;
        background-color: #2c3e50;
        outline: none;
        color: #ffffff;
    }

    /* warna placeholder */
    .form-control::placeholder {
        color: #87aee6;
        opacity: 1;
    }

    .btn-success {
        background-color: #3498db !important;
        border: none;
        color: white;
        font-weight: 600;
        padding: 12px;
        border-radius: 8px;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-success:hover {
        background-color: #2980b9 !important;
        transform: translateY(-2px);
    }

    .alert {
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 15px;
        text-align: center;
        font-size: 14px;
    }

    .alert-success {
        background-color: rgba(52, 152, 219, 0.15);
        color: #76c7ff;
        border: 1px solid rgba(52, 152, 219, 0.3);
    }

    .alert-danger {
        background-color: rgba(231, 76, 60, 0.15);
        color: #ff9b92;
        border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .card-footer {
        background-color: transparent;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 10px;
        font-size: 13px;
        color: #9ecbff;
        text-align: center;
    }

    a {
        color: #5dade2;
        text-decoration: none;
        transition: color 0.2s;
    }

    a:hover {
        color: #82caff;
    }

    .text-center {
        text-align: center;
    }

    .small {
        font-size: 13px;
        color: #b7d3ff;
    }

    .back-btn {
        display: inline-block;
        text-align: center;
        text-decoration: none;
        width: 100%;
        padding: 10px 0;
        background: #0d6efd;
        color: #fff;
        border-radius: 8px;
        font-weight: 700;
        margin-top: 18px;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .back-btn:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <div class="card card-register">
    <div class="card-body p-4">
      <div class="text-center mb-3">
        <div class="brand mx-auto mb-2">XSS</div>
        <h4 class="card-title mb-0">Buat Akun Baru</h4>
        <small class="text-muted">Isi form berikut untuk registrasi</small>
      </div>

      <?php if($msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if($err): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input id="username" name="username" class="form-control" placeholder="Pilih username unik" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" name="password" type="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="mb-3">
          <label for="full_name" class="form-label">Nama Lengkap</label>
          <input id="full_name" name="full_name" class="form-control" placeholder="Nama Anda">
        </div>
        <div class="d-grid">
          <button class="btn btn-success" type="submit">Daftar</button>
        </div>
      </form>

      <div class="text-center mt-3">
        <span class="small">Sudah punya akun? <a href="login.php">Login</a></span>
      </div>

      <a href="../../dashboard.php" class="back-btn">⬅ Kembali</a>
    </div>

    <div class="card-footer text-center small">
      ⚠️ Lab demo: password disimpan plaintext. Produksi harus gunakan <code>password_hash()</code>.
    </div>
  </div>
</body>
</html>
