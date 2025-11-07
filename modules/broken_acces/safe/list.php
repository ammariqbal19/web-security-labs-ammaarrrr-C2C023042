<?php
// safe/list.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

$stmt = $pdo->prepare("SELECT id, uuid, title, created_at FROM items_safe WHERE user_id = :u ORDER BY created_at DESC");
$stmt->execute([':u' => $_SESSION['user']['id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFE — Items</title>
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

        button {
            padding: 5px 10px;
            background-color: #e53e3e;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #c53030;
        }
    </style>
</head>
<body>
    <h2>SAFE — Items (your items)</h2>
    <p><a href="create.php">Create</a> | <a href="../index.php">Back to Dashboard</a></p>
    <table>
        <tr>
            <th>UUID</th>
            <th>Title</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
        <?php foreach($rows as $r): ?>
        <tr>
            <td><?=htmlspecialchars($r['uuid'])?></td>
            <td><?=htmlspecialchars($r['title'])?></td>
            <td><?=htmlspecialchars($r['created_at'])?></td>
            <td class="action-links">
                <a href="view.php?u=<?=urlencode($r['uuid'])?>">View</a> |
                <a href="edit.php?u=<?=urlencode($r['uuid'])?>">Edit</a> |
                <form action="delete.php" method="post" style="display:inline" onsubmit="return confirm('Delete?')">
                    <input type="hidden" name="uuid" value="<?=htmlspecialchars($r['uuid'])?>">
                    <input type="hidden" name="csrf" value="<?=csrf_token()?>">
                    <button>Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
