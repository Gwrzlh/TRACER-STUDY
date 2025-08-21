<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Alumni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            margin: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        h1 {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1.8rem;
        }
        
        .welcome-text {
            color: #666;
            margin-bottom: 30px;
            font-size: 1rem;
        }
        
        .menu-list {
            display: flex;
            gap: 15px;
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
            justify-content: center;
        }
        
        .menu-item {
            margin-bottom: 0;
        }
        
        .menu-link {
            display: inline-block;
            padding: 16px 30px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid #007bff;
            transition: all 0.2s ease;
            font-weight: 500;
            min-width: 150px;
            text-align: center;
        }
        
        .menu-link:hover {
            background: #0056b3;
            border-color: #0056b3;
            color: white;
        }
        
        .logout-section {
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.2s ease;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px 10px;
            }
            
            .container {
                padding: 25px 20px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .menu-list {
                flex-direction: column;
                gap: 10px;
            }
            
            .menu-link {
                padding: 14px 16px;
                font-size: 15px;
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang Di Alumni</h1>
        <p class="welcome-text">Dashboard, <?= session('username'); ?>!</p>

        <ul class="menu-list">
            <li class="menu-item">
                <a href="<?= base_url('alumni/isi') ?>" class="menu-link">Isi</a>
            </li>
            <li class="menu-item">
                <a href="<?= base_url('alumni/teman') ?>" class="menu-link">Lihat Teman</a>
            </li>
        </ul>

        <div class="logout-section">
            <a href="<?= base_url('logout'); ?>" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>