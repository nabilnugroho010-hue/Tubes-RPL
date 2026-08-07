<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
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
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            padding: 20px;
        }
        
        .login-card {
            max-width: 450px;
            width: 100%;
            padding: 48px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-logo {
            font-size: 4rem;
            margin-bottom: 16px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }
        
        .login-form {
            margin-bottom: 32px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .form-control {
            width: 100%;
            padding: 14px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 245, 255, 0.2);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            color: var(--bg-primary);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 245, 255, 0.3);
        }
        
        .login-footer {
            text-align: center;
            padding-top: 32px;
            border-top: 1px solid rgba(0, 245, 255, 0.1);
        }
        
        .login-footer-text {
            color: var(--text-muted);
            margin-bottom: 16px;
        }
        
        .btn-customer {
            display: inline-block;
            padding: 12px 24px;
            background: transparent;
            border: 2px solid var(--primary);
            border-radius: 12px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-customer:hover {
            background: var(--primary);
            color: var(--bg-primary);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .login-card {
                padding: 32px;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">🔐</div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Login to access your admin dashboard</p>
        </div>

        <form method="post" id="loginForm" class="login-form">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="off">
            </div>

            <button type="submit" name="masuk" class="btn-login">
                <span>🚀 Login to Dashboard</span>
            </button>
        </form>

        <div class="login-footer">
            <p class="login-footer-text">Not an admin?</p>
            <a href="pemesanan_pelanggan/pesan_pelanggan.php" class="btn-customer">
                <span>🍽️ Order as Customer</span>
            </a>
        </div>
    </div>
</div>

<script src="assets/js/app.js"></script>
<script>
<?php if($tampil){ ?>
    document.addEventListener('DOMContentLoaded', () => {
        Modal.alert('<?= $pesan ?>');
    });
<?php } ?>
</script>

</body>
</html>