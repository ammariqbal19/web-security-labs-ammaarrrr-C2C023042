<?php
// login.php
require 'auth_simple.php';
$err = '';

// simple CSRF token (lab/demo)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $err = 'Invalid request (CSRF).';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $err = 'Username dan password wajib diisi.';
        } else {
            $pdo = pdo_connect();
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :u LIMIT 1");
            $stmt->execute([':u' => $username]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($u) {
                $ok = false;
                if (password_verify($password, $u['password'])) {
                    $ok = true;
                } elseif ($password === $u['password']) {
                    $ok = true;
                }

                if ($ok) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $u['id'];
                    unset($_SESSION['csrf_token']);
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $err = 'Login gagal: username atau password salah.';
                }
            } else {
                $err = 'Login gagal: username atau password salah.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login — Lab</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* === Tema utama biru elegan === */
    body {
      background: linear-gradient(135deg, #e9f0fb 0%, #d7e3ff 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      color: #0a2342;
    }

    .card-login {
      max-width: 420px;
      margin: 70px auto;
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(0, 84, 204, 0.15);
      border: none;
      background: #ffffff;
    }

    .brand {
      width: 72px;
      height: 72px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #ffffff;
      box-shadow: 0 4px 16px rgba(13,110,253,0.15);
      font-weight: 700;
      font-size: 22px;
      color: #0d6efd;
    }

    .card-title {
      color: #0d6efd;
      font-weight: 600;
    }

    .form-label {
      font-weight: 500;
      color: #0d3b66;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #c7d7f4;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.25);
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
      transform: translateY(-1px);
    }

    .form-footer {
      font-size: 0.9rem;
      color: #0d3b66;
    }

    a {
      color: #0d6efd;
      text-decoration: none;
      font-weight: 500;
    }
    a:hover {
      text-decoration: underline;
    }

    .card-footer {
      background: #f8faff;
      border-top: 1px solid #dbe4ff;
      color: #6c757d;
      border-radius: 0 0 14px 14px;
    }

    .alert-danger {
      background: #f8d7da;
      color: #842029;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="card card-login">
    <div class="card-body p-4">
      <div class="text-center mb-3">
        <div class="brand mx-auto mb-2">XSS</div>
        <h4 class="card-title mb-0">Selamat Datang</h4>
        <small class="text-muted">Masuk untuk melanjutkan</small>
      </div>

      <?php if($err): ?>
        <div class="alert alert-danger" role="alert">
          <?= htmlspecialchars($err); ?>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input id="username" name="username" class="form-control" placeholder="Masukkan username" required
                 value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label d-flex justify-content-between">
            <span>Password</span>
            <a href="#" class="small">Lupa password?</a>
          </label>
          <input id="password" name="password" type="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="d-grid">
          <button class="btn btn-primary" type="submit">Masuk</button>
        </div>
      </form>

      <div class="text-center mt-3 form-footer">
        <span>Belum punya akun? <a href="index.php">Daftar</a></span>
      </div>
    </div>
    <div class="card-footer text-center small">
      ⚠️ Untuk keperluan lab: password bisa berupa plaintext. Produksi harus gunakan <code>password_hash()</code>.
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('username')?.focus();
  </script>
</body>
</html>
