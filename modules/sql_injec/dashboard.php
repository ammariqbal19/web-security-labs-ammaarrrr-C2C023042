<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    // arahkan ke halaman login sesuai kebutuhan manual saat demo
    header('Location: login_safe.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <style>
      * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }

      body {
          font-family: 'Segoe UI', Arial, sans-serif;
          background-color: #0f1724;
          color: #0b1220;
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100vh;
      }

      .container {
          background-color: white;
          padding: 40px 50px;
          border-radius: 12px;
          box-shadow: 0 6px 16px rgba(0,0,0,0.1);
          width: 100%;
          max-width: 500px;
          text-align: center;
      }

      h2 {
          color: #3498db;
          font-size: 28px;
          margin-bottom: 20px;
      }

      p {
          font-size: 16px;
          color: #555;
          margin: 10px 0;
      }

      a {
          display: inline-block;
          margin-top: 20px;
          text-decoration: none;
          background-color: #3498db;
          color: white;
          padding: 10px 20px;
          border-radius: 8px;
          transition: background-color 0.3s, transform 0.2s;
          font-weight: bold;
      }

      a:hover {
          background-color: #2980b9;
          transform: translateY(-2px);
      }
  </style>
</head>
<body>
  <div class="container">
      <h2>DASHBOARD</h2>
      <p>Selamat datang, <?=htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'])?></p>
      <p>Mode demo: <?=htmlspecialchars($_SESSION['demo_mode'] ?? 'unknown')?></p>
      <p><a href="logout.php">Logout</a></p>
  </div>
</body>
</html>
