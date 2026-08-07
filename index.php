<?php
/**
 * Landing Page - SPGFood
 * Default page for Railway deployment
 * Shows options for Admin and Customer
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPGFood - Modern Restaurant Ordering System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .landing-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
        }
        
        .landing-content {
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        
        .landing-logo {
            font-size: 4rem;
            margin-bottom: 16px;
        }
        
        .landing-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .landing-subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 48px;
        }
        
        .landing-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .landing-card {
            padding: 32px;
            border-radius: 16px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
        }
        
        .landing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 32px rgba(0, 245, 255, 0.2);
        }
        
        .landing-card-icon {
            font-size: 3rem;
            margin-bottom: 16px;
        }
        
        .landing-card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        
        .landing-card-description {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 16px;
        }
        
        .landing-card-button {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .landing-card.admin .landing-card-button {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--bg-primary);
        }
        
        .landing-card.customer .landing-card-button {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .landing-card.customer:hover .landing-card-button {
            background: var(--primary);
            color: var(--bg-primary);
        }
        
        .landing-footer {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 32px;
        }
        
        @media (max-width: 768px) {
            .landing-title {
                font-size: 2rem;
            }
            
            .landing-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="landing-container">
    <div class="landing-content">
        <div class="landing-logo">🍽️</div>
        <h1 class="landing-title">SPGFood</h1>
        <p class="landing-subtitle">Modern Restaurant Ordering System</p>
        
        <div class="landing-cards">
            <a href="login.php" class="landing-card admin glass-card">
                <div class="landing-card-icon">👨‍💼</div>
                <div class="landing-card-title">Admin Panel</div>
                <div class="landing-card-description">Login untuk mengelola menu, pesanan, dan laporan</div>
                <div class="landing-card-button">Login Admin</div>
            </a>
            
            <a href="pemesanan_pelanggan/pesan_pelanggan.php" class="landing-card customer glass-card">
                <div class="landing-card-icon">🍽️</div>
                <div class="landing-card-title">Pesan Menu</div>
                <div class="landing-card-description">Pesan makanan dan minuman favorit Anda</div>
                <div class="landing-card-button">Pesan Sekarang</div>
            </a>
        </div>
        
        <div class="landing-footer">
            <p>© 2026 SPGFood. All rights reserved.</p>
        </div>
    </div>
</div>

<script src="assets/js/app.js"></script>

</body>
</html>
