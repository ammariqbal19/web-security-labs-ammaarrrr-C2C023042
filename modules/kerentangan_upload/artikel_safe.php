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
        $file_size = $_FILES['file']['size'];

        // ✅ Validasi ekstensi
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            die("Ekstensi file tidak diizinkan!");
        }

        // ✅ Validasi ukuran (max 2MB)
        if ($file_size > 2 * 1024 * 1024) {
            die("File terlalu besar! Maksimal 2MB.");
        }

        // ✅ Validasi MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp_file);
        finfo_close($finfo);

        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!in_array($mime, $allowed_mimes)) {
            die("Tipe file tidak valid!");
        }

        // ✅ Nama file acak
        $new_name = uniqid('upload_') . '.' . $ext;
        $target = $upload_dir . $new_name;

        if (move_uploaded_file($tmp_file, $target)) {
            $file_path = $target;
        } else {
            die("Gagal menyimpan file.");
        }
    }

    $stmt = $pdo->prepare("INSERT INTO articles (user_id, title, content, file_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $content, $file_path]);

    $message = "Artikel berhasil disimpan dengan aman!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel - Versi AMAN</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #74ABE2, #5563DE);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: white;
            width: 500px;
            padding: 35px 45px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            text-align: left;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 15px;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: #5563DE;
            box-shadow: 0 0 5px rgba(85, 99, 222, 0.3);
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background-color: #5563DE;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #4450C9;
        }

        .message {
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .success {
            background-color: #e6f9ee;
            color: #2b8a3e;
            border: 1px solid #a9e5b9;
        }

        .info {
            background-color: #eef4ff;
            color: #334;
            border: 1px solid #ccd8ff;
            font-size: 14px;
            margin-top: 15px;
            text-align: center;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-align: center;
            color: #5563DE;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Tulis Artikel (Versi Aman)</h2>

        <?php if ($message): ?>
            <div class="message success"><?= $message ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="title">Judul:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Isi Artikel:</label>
            <textarea id="content" name="content" required></textarea>

            <label for="file">File (opsional):</label>
            <input type="file" id="file" name="file">

            <button type="submit">💾 Simpan Artikel</button>
        </form>

        <div class="info">
            ✅ Versi ini hanya mengizinkan <strong>gambar (jpg, png, gif)</strong> dan <strong>PDF</strong>.  
            File berbahaya otomatis diblokir.
        </div>

        <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
    </div>
</body>
</html>
