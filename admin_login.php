<?php
session_start();
$pesan = "";
$tampil = false;

if(isset($_POST['masuk'])){
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if($user == "" || $pass == ""){
        $pesan = "Masukkan Nama Pengguna dan Kata Sandi dengan benar!";
        $tampil = true;
    }
    elseif($user == "admin" && $pass == "1234"){
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user;
        header("Location: dashboard.php");
        exit;
    }
    else {
        $pesan = "Kata sandi salah, ulangi lagi";
        $tampil = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SPGFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        
        .login-container {
            max-width: 400px;
            width: 90%;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-logo {
            font-size: 3rem;
            margin-bottom: 16px;
        }
        
        .login-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            color: #333 !important;
            background: white !important;
        }
        
        .form-control::placeholder {
            color: #999 !important;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            color: #333 !important;
            background: white !important;
        }
        
        input[type="text"],
        input[type="password"] {
            color: #333 !important;
            background: white !important;
        }
        
        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #999 !important;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .back-link {
            text-align: center;
            margin-top: 24px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <div class="login-logo">🔐</div>
        <h1 class="login-title">Login Admin</h1>
        <p class="login-subtitle">Masuk ke dashboard SPGFood</p>
    </div>

    <form method="post" id="loginForm">
        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required autocomplete="off">
        </div>

        <button type="submit" name="masuk" class="btn-login">
            <span>Masuk</span>
        </button>
    </form>

    <div class="back-link">
        <a href="index.php">← Kembali ke Menu Utama</a>
    </div>
</div>

<script src="assets/js/app.js"></script>
<script>
<?php if($tampil){ ?>
    alert('<?= $pesan ?>');
<?php } ?>
</script>

</body>
</html>
