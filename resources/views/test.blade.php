<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Laravel - Diskominfo Sanggau</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #10b981;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin: 1.5rem 0;
            font-weight: 600;
            border-left: 4px solid #10b981;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 2rem 0;
        }
        .info-item {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 10px;
        }
        .info-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-weight: 700;
            color: #1f2937;
            font-size: 1.1rem;
        }
        .warning {
            background: #fef3c7;
            color: #92400e;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            border-left: 4px solid #f59e0b;
        }
        .warning h3 {
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        .steps {
            list-style: none;
            margin: 1rem 0;
        }
        .steps li {
            padding: 0.5rem 0;
            padding-left: 2rem;
            position: relative;
        }
        .steps li::before {
            content: '✓';
            position: absolute;
            left: 0;
            background: #10b981;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            margin-top: 1rem;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .divider {
            border: none;
            border-top: 2px solid #e5e7eb;
            margin: 2rem 0;
        }
        @media (max-width: 768px) {
            .info-grid { grid-template-columns: 1fr; }
            .container { padding: 2rem 1.5rem; }
            h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <span style="font-size: 3rem;">✅</span>
            Laravel is Working!
        </h1>
        
        <div class="success">
            🎉 Aplikasi Laravel berhasil berjalan tanpa error!
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Application Name</div>
                <div class="info-value">{{ config('app.name') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Environment</div>
                <div class="info-value">{{ config('app.env') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">PHP Version</div>
                <div class="info-value">{{ PHP_VERSION }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Laravel Version</div>
                <div class="info-value">{{ app()->version() }}</div>
            </div>
        </div>
        
        <hr class="divider">
        
        <div class="warning">
            <h3>⚠️ Error 500 di Homepage?</h3>
            <p style="margin-bottom: 1rem;">Kemungkinan MySQL belum running. Ikuti langkah ini:</p>
            <ol class="steps">
                <li>Buka <strong>XAMPP Control Panel</strong></li>
                <li>Klik tombol <strong>"Start"</strong> di baris <strong>MySQL</strong></li>
                <li>Tunggu hingga status berubah menjadi hijau</li>
                <li>Refresh browser ini</li>
            </ol>
            <p style="margin-top: 1rem;">
                📄 <strong>Panduan lengkap:</strong> 
                <code style="background: white; padding: 0.25rem 0.5rem; border-radius: 4px;">TROUBLESHOOTING-ERROR-500.md</code>
            </p>
        </div>
        
        <hr class="divider">
        
        <div style="text-align: center;">
            <p style="margin-bottom: 1rem; color: #6b7280;">Setelah MySQL running, klik tombol di bawah:</p>
            <a href="/" class="btn">🏠 Ke Homepage</a>
            <a href="/berita" class="btn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">📰 Ke Berita</a>
        </div>
        
        <hr class="divider">
        
        <div style="text-align: center; color: #9ca3af; font-size: 0.9rem;">
            <p>Dinas Komunikasi dan Informatika</p>
            <p>Kabupaten Sanggau</p>
        </div>
    </div>
</body>
</html>
