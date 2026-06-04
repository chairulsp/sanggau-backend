# 🔧 Troubleshooting Error 500 - Database Connection

## ❌ Problem Identified

Error log menunjukkan:
```
SQLSTATE[HY000] [2002] No connection could be made 
because the target machine actively refused it.
```

**Root Cause:** MySQL/MariaDB di XAMPP belum running.

---

## ✅ Solution - Start MySQL di XAMPP

### **Option 1: Via XAMPP Control Panel (RECOMMENDED)**

1. **Buka XAMPP Control Panel**
   - Cari "XAMPP Control Panel" di Start Menu
   - Atau jalankan: `C:\xampp\xampp-control.exe`

2. **Start MySQL Module**
   - Klik tombol **"Start"** di baris **MySQL**
   - Tunggu hingga status berubah menjadi hijau (Running)
   - Apache juga harus running (hijau)

3. **Test Database Connection**
   ```bash
   # Di browser, buka phpMyAdmin
   http://localhost/phpmyadmin
   
   # Check database 'diskomi5_sanggau_db' ada
   # User: diskomi5_chairul
   ```

4. **Restart Laravel Server**
   ```bash
   # Stop server (Ctrl+C)
   # Start ulang
   php artisan serve
   ```

5. **Test Application**
   ```
   http://127.0.0.1:8000
   ```

---

### **Option 2: Via Command Line** (jika XAMPP Control Panel tidak ada)

```bash
# Navigate ke XAMPP folder
cd C:\xampp

# Start MySQL
mysql\bin\mysqld.exe --console

# Buka terminal baru untuk Laravel
cd C:\xampp\htdocs\sanggau-backend
php artisan serve
```

---

### **Option 3: Install MySQL as Windows Service**

```bash
# Run as Administrator
cd C:\xampp\mysql\bin

# Install service
mysqld.exe --install MySQL

# Start service
net start MySQL

# Test Laravel
cd C:\xampp\htdocs\sanggau-backend
php artisan serve
```

---

## 🧪 Quick Test - Check MySQL Status

### **Check if MySQL is running:**

```bash
# Via PowerShell
Test-NetConnection -ComputerName localhost -Port 3306

# Via Command Prompt
telnet localhost 3306

# Via PHP
php -r "new PDO('mysql:host=localhost', 'diskomi5_chairul', 'Dayat040500!');" && echo "Connected!"
```

**Expected Result:** Connection successful

---

## 🔄 After MySQL is Running

### **1. Clear Laravel Cache**
```bash
cd C:\xampp\htdocs\sanggau-backend

php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### **2. Test Database Connection**
```bash
php artisan migrate:status
```

**Expected Output:** List of migrations

### **3. Restart Laravel Server**
```bash
# Stop current server (Ctrl+C)
php artisan serve
```

### **4. Access Application**
```
http://127.0.0.1:8000
```

**Expected:** Homepage loads without error 500

---

## 🐛 Alternative: Test Without Database

Jika MySQL tidak bisa dijalankan, buat test page sederhana tanpa database:

### **Create Test Route**

Edit `routes/web.php`, tambahkan di paling atas:

```php
Route::get('/test', function() {
    return view('test');
});
```

### **Create Test View**

Buat file: `resources/views/test.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Test Page - No Database</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f5f5f5; }
        .card { background: white; padding: 2rem; border-radius: 8px; max-width: 600px; margin: 0 auto; }
        .success { color: #10b981; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Laravel is Working!</h1>
        <p class="success">Server running successfully</p>
        <p>Application: {{ config('app.name') }}</p>
        <p>Environment: {{ config('app.env') }}</p>
        <p>PHP Version: {{ PHP_VERSION }}</p>
        <p>Laravel Version: {{ app()->version() }}</p>
        <hr>
        <p><strong>Next Step:</strong> Start MySQL di XAMPP Control Panel</p>
        <p>Then access: <a href="/">Homepage</a></p>
    </div>
</body>
</html>
```

### **Test It**
```
http://127.0.0.1:8000/test
```

This will work even without database!

---

## 📋 Checklist

Pastikan semua ini sudah running:

**XAMPP Services:**
- [x] Apache (Port 80, 443)
- [x] MySQL (Port 3306)

**Laravel:**
- [x] php artisan serve (Port 8000)
- [x] Config cleared
- [x] Assets compiled

**Database:**
- [x] Database exists: `diskomi5_sanggau_db`
- [x] User has access: `diskomi5_chairul`
- [x] Tables migrated

**Files:**
- [x] .env configured
- [x] Storage linked
- [x] Views exist

---

## 🎯 Expected Flow

```
1. Start XAMPP Control Panel
   └─> Start Apache
   └─> Start MySQL (✅ IMPORTANT!)

2. Start Laravel Server
   └─> php artisan serve

3. Access Application
   └─> http://127.0.0.1:8000

4. ✅ Success!
```

---

## 🔍 Debug Commands

```bash
# Check Laravel config
php artisan config:show database

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check migrations
php artisan migrate:status

# Check routes
php artisan route:list

# Check views
php artisan view:cache
```

---

## 💡 Common Issues

### **Issue 1: Port 3306 already in use**
```bash
# Find process using port 3306
netstat -ano | findstr :3306

# Kill process (replace PID)
taskkill /PID <process_id> /F
```

### **Issue 2: MySQL won't start in XAMPP**
1. Check error log: `C:\xampp\mysql\data\mysql_error.log`
2. Try repair: `C:\xampp\mysql\bin\mysqlcheck.exe`
3. Reinstall MySQL module

### **Issue 3: Access Denied for user**
```bash
# Reset password via phpMyAdmin
# Or via command:
mysql -u root
> ALTER USER 'diskomi5_chairul'@'localhost' IDENTIFIED BY 'Dayat040500!';
> FLUSH PRIVILEGES;
```

---

## 🚀 Once Fixed

After MySQL is running and error is resolved:

1. ✅ Test homepage: http://127.0.0.1:8000/
2. ✅ Test berita: http://127.0.0.1:8000/berita
3. ✅ Test galeri: http://127.0.0.1:8000/galeri
4. ✅ Check navbar works
5. ✅ Test responsive (F12 → device toolbar)

---

## 📞 Still Having Issues?

Check these files for more help:
- `SETUP-COMPLETE.md` - Full setup guide
- `QUICK-START.md` - Quick troubleshooting
- `storage/logs/laravel.log` - Detailed error logs

---

**Summary:** Start MySQL di XAMPP Control Panel, then refresh browser! 🎉
