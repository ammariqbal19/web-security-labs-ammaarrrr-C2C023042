<?php
require 'config.php';
require_login();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $file_path = null;
    if (!empty($_FILES['file']['name'])) {
        $upload_dir = 'uploads/';
        $file_name = $_FILES['file']['name'];
        $tmp_file = $_FILES['file']['tmp_name'];
        $target = $upload_dir . basename($file_name);

        // ❌ TIDAK ADA VALIDASI — RENTAN!
        if (move_uploaded_file($tmp_file, $target)) {
            $file_path = $target;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO articles (user_id, title, content, file_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $content, $file_path]);

    $message = "Artikel berhasil disimpan!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Artikel - Versi RENTAN</title>
    <style>
        /* Global layout */
        :root{
            --primary-1: #74ABE2;
            --primary-2: #5563DE;
            --danger: #E74C3C;
            --danger-dark: #C0392B;
            --card-bg: #ffffff;
            --muted: #666;
            --radius: 12px;
        }

        *{box-sizing:border-box}
        body{
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, var(--primary-1), var(--primary-2));
            padding: 28px;
        }

        .card{
            width: 560px;
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: 0 12px 30px rgba(17,24,39,0.12);
            animation: enter .5s ease;
        }

        @keyframes enter {
            from { opacity: 0; transform: translateY(12px) scale(.995); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        h2{
            margin:0 0 8px 0;
            color:#222;
            font-size:20px;
            text-align:left;
        }

        .subtitle{
            margin:0 0 18px 0;
            color:var(--muted);
            font-size:14px;
            text-align:left;
        }

        form { display:flex; flex-direction:column; gap:12px; }

        label{
            font-weight:600;
            font-size:14px;
            color:#333;
            margin-bottom:6px;
            display:block;
        }

        input[type="text"],
        textarea {
            width:100%;
            padding:12px 14px;
            border-radius:10px;
            border:1px solid #d8dbe8;
            font-size:14px;
            resize:vertical;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline:none;
            box-shadow:0 4px 12px rgba(85,99,222,0.12);
            border-color:var(--primary-2);
        }

        textarea { min-height:120px; }

        .file-row{
            display:flex;
            gap:12px;
            align-items:center;
        }

        .file-row input[type="file"]{
            flex:1;
            padding:8px;
            border-radius:10px;
            border:1px dashed #e2e6ff;
            background: linear-gradient(180deg, #fff, #fbfdff);
        }

        .btn{
            background:var(--primary-2);
            color:#fff;
            padding:12px;
            border-radius:10px;
            border: none;
            cursor:pointer;
            font-weight:600;
            font-size:15px;
        }

        .btn:hover{ background:#4450C9; }

        .message{
            padding:12px 14px;
            border-radius:10px;
            font-weight:600;
            font-size:14px;
        }

        .message.success{
            background:#e6f9ee;
            color:#2b8a3e;
            border:1px solid #a9e5b9;
        }

        /* Danger box for RENTAN version */
        .danger-box{
            margin-top:12px;
            padding:12px 14px;
            border-radius:10px;
            background: linear-gradient(90deg, rgba(231,76,60,0.09), rgba(255,255,255,0));
            border: 1px solid rgba(231,76,60,0.18);
            color: var(--danger-dark);
            display:flex;
            gap:10px;
            align-items:center;
            font-weight:700;
        }

        .danger-dot{
            width:12px;
            height:12px;
            border-radius:50%;
            background: var(--danger);
            box-shadow:0 6px 18px rgba(231,76,60,0.18);
            flex:0 0 12px;
        }

        .links{
            margin-top:14px;
            display:flex;
            gap:10px;
            align-items:center;
            justify-content:flex-start;
        }

        .links a{
            color:var(--primary-2);
            text-decoration:none;
            font-weight:600;
            background:transparent;
            padding:8px 12px;
            border-radius:8px;
        }

        .links a:hover{ text-decoration:underline; }

        /* small screens */
        @media (max-width:600px){
            .card{ width: 100%; padding:18px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Tulis Artikel (Versi RENTAN)</h2>
        <p class="subtitle">Form ini sengaja dibuat rentan — jangan gunakan di lingkungan produksi.</p>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div>
                <label for="title">Judul</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div>
                <label for="content">Isi</label>
                <textarea id="content" name="content" required></textarea>
            </div>

            <div>
                <label for="file">File (opsional)</label>
                <div class="file-row">
                    <input type="file" id="file" name="file">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:6px;">
                <button type="submit" class="btn">Simpan Artikel</button>
            </div>
        </form>

        <div class="danger-box" role="alert" aria-live="polite">
            <div class="danger-dot" aria-hidden="true"></div>
            <div>
                ⚠️ <strong>PERINGATAN:</strong> Versi ini <em>rentan</em> — file berbahaya (termasuk .php) dapat di-upload dan dieksekusi.
                Jangan gunakan pada server publik.
            </div>
        </div>

        <div class="links">
            <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
            <a href="artikel_safe.php" style="color:var(--danger-dark)">Lihat Versi Aman</a>
        </div>
    </div>
</body>
</html>
