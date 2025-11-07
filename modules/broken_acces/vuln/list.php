<?php
// vuln/list.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

$res = $pdo->query("SELECT items_vuln.*, users.username FROM items_vuln JOIN users ON items_vuln.user_id = users.id ORDER BY items_vuln.id DESC");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VULN — Items</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ccc;
        }

        th {
            background-color: #4fd1c5;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-links {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <h2>VULN — Items</h2>
    <p><a href="create.php">Create</a> | <a href="../index.php">Back to Dashboard</a></p>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Author</th>
            <th>Action</th>
        </tr>
        <?php foreach($res as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= $r['title'] ?></td>
            <!-- intentionally not escaped (stored XSS demonstration) -->
            <td><?= $r['content'] ?></td>
            <td><?= $r['username'] ?></td>
            <td class="action-links">
                <a href="edit.php?id=<?= $r['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
