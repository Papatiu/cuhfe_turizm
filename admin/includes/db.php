<?php
// admin/includes/db.php

// Oturum (Session) başlatma. Login durumunu kontrol etmek için gereklidir.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Veritabanı bağlantı bilgileri
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // XAMPP için varsayılan kullanıcı
define('DB_PASS', '');     // XAMPP için varsayılan şifre boş
define('DB_NAME', 'cuhfe_turizm_db');

// Veritabanına bağlanma
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    // Hata modunu ayarla
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
?>