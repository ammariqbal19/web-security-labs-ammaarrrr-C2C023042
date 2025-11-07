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
<link rel="stylesheet" href="assets/css/login.css">
<div class="login-container">
<form method="post" class="login-form">
  <h3>Login</h3>
  <?=isset($err) ? "<p class='error'>$err</p>" : ""?>
  <input name="username" placeholder="username" class="input"><br>
  <input name="password" type="password" placeholder="password" class="input"><br>
  <button class="btn">Login</button>
</form>
</div>
