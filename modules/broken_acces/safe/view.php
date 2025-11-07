<?php
// safe/view.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

$uuid = $_GET['u'] ?? '';
// If token not provided in GET, show form to ask token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uuid = $_POST['u'] ?? '';
    $token = $_POST['token'] ?? '';
} else {
    $token = $_GET['t'] ?? '';
}

if (!$uuid) { http_response_code(400); exit('Missing uuid'); }

$stmt = $pdo->prepare("SELECT * FROM items_safe WHERE uuid = :u LIMIT 1");
$stmt->execute([':u'=>$uuid]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$item) { http_response_code(404); exit('Not found'); }

// Ownership check first (defense-in-depth)
if ($item['user_id'] != $_SESSION['user']['id']) {
    http_response_code(403); exit('Forbidden: not owner');
}

// If token not yet provided, ask user to input token (or provide via ?t=)
if (!$token) {
    // show simple form
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Enter Access Token</title>
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
                max-width: 400px;
                margin: 0 auto;
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            input[type="text"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 16px;
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
        <h2>Enter access token for UUID <?=htmlspecialchars($uuid)?></h2>
        <form method="post">
            <input type="hidden" name="u" value="<?=htmlspecialchars($uuid)?>">
            <input name="token" placeholder="paste token here" required>
            <button>View</button>
        </form>
        <p><a href="list.php">Back</a></p>
    </body>
    </html>
    <?php
    exit;
}

// Verify token (compare hash)
$provided_hash = token_hash($token);
if (!hash_equals($item['token_hash'], $provided_hash)) {
    http_response_code(403); exit('Invalid token');
}

// Passed checks — show safe content escaped
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=htmlspecialchars($item['title'])?></title>
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

        .content {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .uuid {
            font-style: italic;
            text-align: center;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
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
    <h2><?=htmlspecialchars($item['title'])?></h2>
    <div class="content">
        <p><?=nl2br(htmlspecialchars($item['content']))?></p>
        <p class="uuid"><i>UUID: <?=htmlspecialchars($item['uuid'])?></i></p>
    </div>
    <p><a href="list.php">Back</a></p>
</body>
</html>
