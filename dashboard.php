<?php
// Tidak perlu session_start() karena sudah tidak menggunakan login
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVWA Ammar</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DASHBOARD DVWA KECIL</h1>
            <p>Selamat datang di Lab Keamanan Web</p>
        </div>

        <div class="modules-grid">
            <a href="modules/sql_injec/" class="module-card">
                <div class="module-title">SQL Injection Lab</div>
                <div class="module-desc">Praktik SQL Injection dan cara mengamankannya</div>
            </a>

            <a href="modules/xss_main/" class="module-card">
                <div class="module-title">XSS Lab</div>
                <div class="module-desc">Pembelajaran Cross-Site Scripting (XSS)</div>
            </a>

            <a href="modules/broken_acces/" class="module-card">
                <div class="module-title">Broken Access Lab</div>
                <div class="module-desc">Uji kerentanan kontrol akses</div>
            </a>

            <a href="modules/kerentangan_upload/" class="module-card">
                <div class="module-title">File Upload Lab</div>
                <div class="module-desc">Praktik keamanan upload file</div>
            </a>
        </div>
    </div>
</body>
</html>
