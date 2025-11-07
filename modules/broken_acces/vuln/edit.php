<?php
// vuln/edit.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(400); exit('Bad Request'); }

// Load (no ownership check)
$row = $pdo->query("SELECT * FROM items_vuln WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
if (!$row) { http_response_code(404); exit('Not found'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    // VULNERABLE: direct concatenation
    $sql = "UPDATE items_vuln SET title = '{$title}', content = '{$content}' WHERE id = $id";
    $pdo->exec($sql);
    header('Location: list.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit VULN Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #4fd1c5;
            outline: none;
        }

        button {
            padding: 10px 15px;
            background-color: #4fd1c5;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            width: 100%;
        }

        button:hover {
            background-color: #38b2ac;
        }

        p {
            text-align: center;
        }

        a {
            text-decoration: none;
            color: #4fd1c5;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Edit VULN Item (ID <?= $row['id'] ?>)</h2>
    <form method="post">
        <input name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
        <textarea name="content" rows="6" required><?= htmlspecialchars($row['content']) ?></textarea>
        <button>Save</button>
    </form>
    <p><a href="list.php">Back</a></p>
</body>
</html>
