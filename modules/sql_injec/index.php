<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>SQL Injection Demo</title>
  <style>
    /* Reset */
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html,body { height: 100%; }

    :root{
      --bg: #0f1724;
      --panel: #0f1a2b;
      --muted: #98a8c6;
      --text: #e6eef8;
      --accent: #3dd7c9;
      --safe: #4CAF50;
      --danger: #ff6b6b;
      --glass: rgba(255,255,255,0.04);
      --radius: 12px;
      --container-w: 1100px;
      --shadow: 0 10px 30px rgba(2,6,23,0.6);
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }

    body {
      background: linear-gradient(180deg,#061021 0%, #071431 60%);
      color: var(--text);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding: 36px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .wrap {
      width: 100%;
      max-width: var(--container-w);
    }

    header.top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 28px;
    }

    .brand {
      display:flex;
      gap:14px;
      align-items:center;
    }

    .logo {
      width:56px;
      height:56px;
      border-radius:12px;
      background: linear-gradient(135deg,var(--accent), #0aa99c);
      display:flex;
      align-items:center;
      justify-content:center;
      color:#002;
      font-weight:800;
      font-size:18px;
      box-shadow:0 8px 24px rgba(3,115,102,0.12);
    }

    .brand h1 {
      font-size:20px;
      letter-spacing:0.2px;
      color:var(--text);
      margin-bottom:4px;
    }
    .brand p { color:var(--muted); font-size:13px; margin:0; }

    .actions { display:flex; gap:10px; align-items:center; }

    .btn {
      background:var(--accent);
      color:#002;
      padding:10px 14px;
      border-radius:10px;
      font-weight:700;
      text-decoration:none;
      border: none;
      cursor:pointer;
    }
    .btn.ghost {
      background:transparent;
      border:1px solid rgba(255,255,255,0.06);
      color:var(--muted);
      font-weight:600;
    }

    /* BACK BUTTON */
    .btn-back {
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:8px;
      background: rgba(255,255,255,0.03);
      color: var(--text);
      text-decoration:none;
      border:1px solid rgba(255,255,255,0.04);
      font-weight:700;
      transition: transform .12s ease, box-shadow .12s ease;
    }
    .btn-back:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(2,6,23,0.45); }

    /* panel */
    .panel {
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(0,0,0,0.05));
      border-radius: var(--radius);
      padding: 26px;
      box-shadow: var(--shadow);
      border: 1px solid rgba(255,255,255,0.03);
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .card {
      background: linear-gradient(180deg,#071426, #071020);
      border-radius: 12px;
      padding: 22px;
      border: 1px solid rgba(255,255,255,0.03);
      transition: transform .12s ease, box-shadow .12s ease;
      box-shadow: 0 8px 22px rgba(2,6,23,0.5);
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      min-height:150px;
      text-decoration: none;
      color: var(--text);
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 40px rgba(2,6,23,0.6);
    }

    .card .head {
      display:flex;
      align-items:flex-start;
      gap:12px;
    }
    .chip {
      width:44px;height:44px;border-radius:10px;
      display:inline-flex;align-items:center;justify-content:center;
      font-weight:700;color:#001;
      background: linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03));
    }

    .title { font-size:18px; color:var(--accent); margin-bottom:6px; font-weight:700; }
    .desc  { color:var(--muted); font-size:14px; margin-bottom:14px; }

    .badge {
      display:inline-block;
      padding:6px 10px;
      border-radius:999px;
      font-weight:700;
      font-size:12px;
    }
    .badge.safe { background:#e8f6ee; color:var(--safe); }
    .badge.vuln { background:#fff0f0; color:var(--danger); }

    .card .foot {
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-top:12px;
    }
    .card .link {
      text-decoration:none;
      background: rgba(255,255,255,0.03);
      padding:8px 12px;
      border-radius:8px;
      color:var(--text);
      font-weight:700;
    }

    .about {
      margin-top:20px;
      background: linear-gradient(180deg, rgba(255,255,255,0.015), rgba(0,0,0,0.04));
      padding:18px;
      border-radius:10px;
      border:1px solid rgba(255,255,255,0.02);
      color:var(--muted);
      font-size:14px;
    }
    .about h2 { color:var(--text); margin-bottom:8px; font-size:16px; }

    @media (max-width:900px){
      .grid { grid-template-columns: 1fr; }
      body { padding:20px; }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <header class="top">
      <div class="brand">
        <div class="logo">SQL</div>
        <div>
          <h1>SQL Injection Demo</h1>
          <p>Praktik keamanan dan demonstrasi kerentanan</p>
        </div>
      </div>

      <div class="actions">
        <!-- Tombol kembali ke dashboard; jika file ini berada di modules/sql_injec/
             gunakan ../../dashboard.php . Jika dashboard.php ada langsung di webroot
             dan project folder bernama DVWA_LITE, kamu bisa ganti ke /DVWA_LITE/dashboard.php -->
        <a class="btn-back" href="../../dashboard.php" aria-label="Kembali ke Dashboard">← Kembali ke Dashboard</a>
      </div>
    </header>

    <main class="panel" role="main">
      <div class="grid">
        <a class="card" href="login_safe.php">
          <div>
            <div class="head">
              <div class="chip">S</div>
              <div>
                <div class="title">Safe Login</div>
                <div class="desc">Login page with SQL injection protection (prepared statements)</div>
              </div>
            </div>
          </div>
          <div class="foot">
            <span class="badge safe">SAFE</span>
            <span class="link">Open →</span>
          </div>
        </a>

        <a class="card" href="login_vul.php">
          <div>
            <div class="head">
              <div class="chip">V</div>
              <div>
                <div class="title">Vulnerable Login</div>
                <div class="desc">Login page vulnerable to SQL injection (for learning)</div>
              </div>
            </div>
          </div>
          <div class="foot">
            <span class="badge vuln">VULNERABLE</span>
            <span class="link">Open →</span>
          </div>
        </a>

        <a class="card" href="create_user_safe.php">
          <div>
            <div class="head">
              <div class="chip">C</div>
              <div>
                <div class="title">Safe User Creation</div>
                <div class="desc">Create user with input validation and prepared statements</div>
              </div>
            </div>
          </div>
          <div class="foot">
            <span class="badge safe">SAFE</span>
            <span class="link">Open →</span>
          </div>
        </a>

        <a class="card" href="create_user_vul.php">
          <div>
            <div class="head">
              <div class="chip">V</div>
              <div>
                <div class="title">Vulnerable User Creation</div>
                <div class="desc">Create user vulnerable to SQL injection (educational)</div>
              </div>
            </div>
          </div>
          <div class="foot">
            <span class="badge vuln">VULNERABLE</span>
            <span class="link">Open →</span>
          </div>
        </a>
      </div>

      <section class="about" aria-labelledby="aboutHeading">
        <h2 id="aboutHeading">About This Demo</h2>
        <p>This application demonstrates the importance of protecting against SQL injection attacks.</p>
        <ul style="margin-top:12px; color:var(--muted);">
          <li><strong>Safe versions</strong> use prepared statements and proper input validation.</li>
          <li><strong>Vulnerable versions</strong> show common mistakes so you can learn mitigations.</li>
        </ul>
        <p style="margin-top:12px; color:var(--muted);"><strong>Note:</strong> The vulnerable versions are intentionally insecure for educational use — do not deploy them publicly.</p>
      </section>
    </main>
  </div>
</body>
</html>
