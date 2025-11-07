<?php
// You can add PHP code here if needed in the future
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SQL Injection Demo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
            font-size: 2.5em;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin: 30px 0;
        }

        .menu a {
            background-color: white;
            padding: 25px;
            text-align: center;
            text-decoration: none;
            color: #333;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .menu a:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .menu h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.4em;
        }

        .menu p {
            color: #666;
            font-size: 0.95em;
        }

        .safe {
            border: 3px solid #4CAF50;
            position: relative;
        }

        .vulnerable {
            border: 3px solid #f44336;
            position: relative;
        }

        .safe::before, .vulnerable::before {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .safe::before {
            content: "SAFE";
            background-color: #e8f5e9;
            color: #4CAF50;
        }

        .vulnerable::before {
            content: "VULNERABLE";
            background-color: #ffebee;
            color: #f44336;
        }

        .about {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 40px;
        }

        .about h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .about ul {
            margin: 20px 0;
            padding-left: 20px;
        }

        .about li {
            margin: 10px 0;
        }

        .about p {
            margin: 15px 0;
        }

        strong {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <h1>SQL Injection Demo Application</h1>
    
    <div class="menu">
        <a href="login_safe.php" class="safe">
            <h3>Safe Login</h3>
            <p>Login page with SQL injection protection</p>
        </a>
        
        <a href="login_vul.php" class="vulnerable">
            <h3>Vulnerable Login</h3>
            <p>Login page vulnerable to SQL injection</p>
        </a>
        
        <a href="create_user_safe.php" class="safe">
            <h3>Safe User Creation</h3>
            <p>Create user with SQL injection protection</p>
        </a>
        
        <a href="create_user_vul.php" class="vulnerable">
            <h3>Vulnerable User Creation</h3>
            <p>Create user vulnerable to SQL injection</p>
        </a>
    </div>

    <div class="about">
        <h2>About This Demo</h2>
        <p>This application demonstrates the importance of protecting against SQL injection attacks:</p>
        <ul>
            <li><strong>Safe versions</strong> use prepared statements and proper input validation</li>
            <li><strong>Vulnerable versions</strong> show common security mistakes to avoid</li>
        </ul>
        <p><strong>Note:</strong> The vulnerable versions are for educational purposes only and should never be used in production.</p>
    </div>
</body>
</html>