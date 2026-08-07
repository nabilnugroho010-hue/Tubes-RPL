<?php
/**
 * Landing Page Simple - SPGFood
 * Tampilan awal sederhana dengan 2 pilihan
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPGFood - Restaurant Ordering System</title>
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
        
        .container {
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
        
        .logo {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .title {
            color: white;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 40px;
        }
        
        .options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .option-btn {
            padding: 20px;
            background: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .option-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .option-btn.admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .option-btn.customer {
            background: white;
            color: #333;
        }
        
        .icon {
            font-size: 1.5rem;
        }
        
        .footer {
            color: rgba(255, 255, 255, 0.6);
            margin-top: 40px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">🍽️</div>
    <h1 class="title">SPGFood</h1>
    <p class="subtitle">Restaurant Ordering System</p>
    
    <div class="options">
        <a href="login.php" class="option-btn admin">
            <span class="icon">👨‍💼</span>
            <span>Admin Panel</span>
        </a>
        
        <a href="pemesanan_pelanggan/pesan_pelanggan.php" class="option-btn customer">
            <span class="icon">🍽️</span>
            <span>Pesan Menu</span>
        </a>
    </div>
    
    <p class="footer">© 2026 SPGFood. All rights reserved.</p>
</div>

</body>
</html>
