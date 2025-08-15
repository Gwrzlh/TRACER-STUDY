<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Alumni</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        h1 {
            font-weight: 500;
            color: #333;
            margin-bottom: 10px;
        }
        
        p {
            color: #666;
            margin-bottom: 30px;
        }
        
        .btn-container {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            background-color: #001BB7;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            min-width: 200px;
            justify-content: center;
        }
        
        .btn:hover {
            background-color: #001a9e;
        }
        
        .btn-icon {
            margin-right: 8px;
            font-size: 18px;
        }
        
        .logout-btn {
            background-color: #dc3545;
            min-width: auto;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: normal;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang Alumni</h1>
        <p>Halo, <?= session('username'); ?>! Anda sedang di dashboard alumni.</p>

        <div class="btn-container">
            <a href="<?= base_url('alumni') ?>" class="btn">
                Daftar Alumni
            </a>
            <a href="<?= base_url('alumnisurveyor') ?>" class="btn">
                Daftar Alumni Surveyor
            </a>
        </div>
        
        <a href="<?= base_url('logout'); ?>" class="btn logout-btn">
            Logout
        </a>
    </div>
</body>
</html>