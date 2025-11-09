<?php
// safe/create.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF fail'); }
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title === '') { $err = "Title required"; }
    if (empty($err)) {
        $uuid = uuid4();
        $token = token_generate();
        $hash = token_hash($token);
        $stmt = $pdo->prepare("INSERT INTO items_safe (uuid, token_hash, token_expires_at, user_id, title, content)
                               VALUES (:uuid, :th, NULL, :uid, :t, :c)");
        $stmt->execute([
            ':uuid'=>$uuid, ':th'=>$hash, ':uid'=>$_SESSION['user']['id'],
            ':t'=>$title, ':c'=>$content
        ]);
        // Show token only once
        echo "<!doctype html><html lang='id'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Item created</title>";
        // reuse same style below for token page
        echo '<style>
          :root{--bg:#f6f9ff;--card:#ffffff;--accent:#4fd1c5;--text:#0b2540;--muted:#556;--radius:10px}
          body{font-family:Inter,Segoe UI,Arial; background:linear-gradient(180deg,#f8fbff,#eef6ff); padding:28px; display:flex; align-items:center; justify-content:center; min-height:100vh}
          .card{background:var(--card); padding:22px; border-radius:var(--radius); box-shadow:0 8px 30px rgba(15,23,42,0.06); max-width:760px; width:100%}
          pre{background:#f4f7f9;padding:12px;border-radius:6px;overflow:auto}
          a.btn{display:inline-block;margin-top:12px;padding:10px 14px;border-radius:8px;background:var(--accent);color:#001;font-weight:700;text-decoration:none}
        </style></head><body><div class="card">';
        echo "<h3 style='color:var(--text)'>Item created</h3>";
        echo "<p><b>UUID:</b> ".htmlspecialchars($uuid)."</p>";
        echo "<p><b>ACCESS TOKEN (save this now):</b><br><pre>".htmlspecialchars($token)."</pre></p>";
        echo '<p><a class="btn" href="list.php">Back</a></p>';
        echo '</div></body></html>';
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create SAFE Item</title>
  <style>
    :root{--bg:#f6f9ff;--card:#ffffff;--accent:#4fd1c5;--accent-strong:#38b2ac;--text:#0b2540;--muted:#556;--radius:10px}
    *{box-sizing:border-box}
    body{font-family:Inter,Segoe UI,Arial; background:linear-gradient(180deg,#f8fbff,#eef6ff); padding:28px; display:flex; align-items:center; justify-content:center; min-height:100vh}
    .card{background:var(--card); padding:22px; border-radius:var(--radius); box-shadow:0 8px 30px rgba(15,23,42,0.06); max-width:760px; width:100%}
    h2{margin:0 0 10px 0;color:var(--text)}
    form{display:flex;flex-direction:column;gap:12px}
    input[type="text"], textarea{width:100%;padding:10px;border:1px solid #e6edf6;border-radius:8px;font-size:15px}
    textarea{min-height:140px;resize:vertical}
    input[type="text"]:focus, textarea:focus{outline:none;box-shadow:0 6px 18px rgba(72,199,173,0.12);border-color:var(--accent)}
    .btn{display:inline-block;background:var(--accent);color:#001;padding:10px 14px;border-radius:8px;font-weight:700;border:none;cursor:pointer}
    .err{color:#b02a37;margin-bottom:8px}
    .note{color:var(--muted);font-size:14px;margin-top:8px}
    a.link{color:var(--muted);text-decoration:none;margin-top:12px;display:inline-block}
    a.link:hover{text-decoration:underline}
  </style>
</head>
<body>
  <div class="card">
    <h2>Create SAFE Item</h2>
    <?php if (!empty($err)) echo "<div class='err'>".htmlspecialchars($err)."</div>"; ?>
    <form method="post" autocomplete="off">
      <input name="title" placeholder="title" style="width:100%;" value="<?=htmlspecialchars($_POST['title'] ?? '')?>">
      <textarea name="content" placeholder="content"><?=htmlspecialchars($_POST['content'] ?? '')?></textarea>
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <div style="display:flex;gap:12px;align-items:center">
        <button class="btn" type="submit">Create</button>
        <a class="link" href="list.php">Back</a>
      </div>
    </form>
  </div>
</body>
</html>
