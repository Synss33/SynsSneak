<?php
session_start();
include 'koneksi.php';

$err = '';

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($koneksi, $_POST['username']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$user'";
    $res = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if (password_verify($pass, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: index.php#dashboard");
            exit;
        }
    }
    $err = 'Username atau password salah!';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin SynsSneak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .login-bg {
            position: fixed;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1556906781-9a412961c28c?q=80&w=1200') center/cover no-repeat;
            transform: scale(1.05);
            animation: bgZoom 14s ease-in-out infinite alternate;
        }
        @keyframes bgZoom {
            0% { transform: scale(1.05); }
            100% { transform: scale(1.15); }
        }
        .login-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.6) 50%, rgba(15,23,42,0.5) 100%);
        }
        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 44px 36px 36px;
            box-shadow: 0 24px 64px rgba(15,23,42,0.45);
            border: 1px solid rgba(37,99,235,0.2);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 8px;
        }
        .login-logo h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
        }
        .login-logo h1 span { color: #2563eb; }
        .login-logo p {
            font-size: 0.82rem;
            color: #64748b;
            margin-top: 2px;
            font-weight: 500;
        }
        .login-divider {
            width: 50px;
            height: 3px;
            background: #2563eb;
            border-radius: 4px;
            margin: 16px auto 24px;
        }
        .error-msg {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: #0f172a;
            background: #eef2f6;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s, background 0.3s;
        }
        .form-group input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.15);
            background: #ffffff;
        }
        .btn-login {
            width: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            color: #ffffff;
            background: #2563eb;
            border: none;
            border-radius: 100px;
            padding: 14px 32px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
            margin-top: 6px;
        }
        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }
        .login-footer a {
            font-size: 0.82rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .login-footer a:hover { color: #2563eb; }
        @media (max-width: 768px) {
            .login-card { max-width: 400px; padding: 32px 24px 28px; }
        }
        @media (max-width: 480px) {
            .login-card { margin: 12px; padding: 24px 18px 24px; }
            .login-logo h1 { font-size: 1.4rem; }
            .btn-login { font-size: 0.85rem; padding: 12px 24px; }
        }
    </style>
</head>
<body>
    <div class="login-bg"></div>
    <div class="login-overlay"></div>

    <div class="login-card">
        <div class="login-logo">
            <h1>Sneaker<span>Vault</span></h1>
            <p>Login Admin</p>
        </div>
        <div class="login-divider"></div>

        <?php if (!empty($err)) : ?>
        <div class="error-msg"><?= $err ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Masuk</button>
        </form>

        <div class="login-footer">
            <a href="index.php">&larr; Kembali ke Website</a>
        </div>
    </div>
</body>
</html>
