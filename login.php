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
        $_SESSION['admin'] = true;
        header("Location: index.php");
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
</head>
<body>

<div class="glass-container" style="max-width: 420px; margin: auto; padding: 40px;">
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; margin-bottom: 8px;">🔐 Login Admin</h1>
        <p style="color: var(--text-muted);">Masuk ke dashboard SPGFood</p>
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

        <button type="submit" name="masuk" class="btn btn-primary w-100">
            <span>Masuk</span>
        </button>
    </form>

    <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--glass-border);">
        <p style="color: var(--text-muted); margin-bottom: 12px;">Pelanggan?</p>
        <a href="pemesanan_pelanggan/pesan_pelanggan.php" class="btn btn-outline w-100">
            <span>🍽️ Pesan Menu</span>
        </a>
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