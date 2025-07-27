<?php
// admin/login_check.php

// Veritabanı bağlantısı ve session başlatma
require_once 'includes/db.php';

// Formdan veri gelip gelmediğini kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Gelen verileri al
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kullanıcı adının veya şifrenin boş olup olmadığını kontrol et
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = "Kullanıcı adı veya şifre boş bırakılamaz.";
        header("Location: index.php");
        exit;
    }

    try {
        // Kullanıcıyı veritabanında ara
        $sql = "SELECT * FROM admins WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Eğer kullanıcı bulunduysa
        if ($stmt->rowCount() == 1) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // Gelen şifre ile veritabanındaki hash'lenmiş şifreyi karşılaştır
            if (password_verify($password, $admin['password'])) {
                
                // Şifre doğru, giriş başarılı! Oturum (session) değişkenlerini ayarla
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_full_name'] = $admin['full_name'];
                
                // Başarı mesajı (isteğe bağlı)
                // $_SESSION['success_message'] = "Giriş başarılı! Hoş geldiniz.";

                // Dashboard'a yönlendir
                header("Location: dashboard.php");
                exit;

            } else {
                // Şifre yanlış
                $_SESSION['error_message'] = "Kullanıcı adı veya şifre hatalı.";
                header("Location: index.php");
                exit;
            }
        } else {
            // Kullanıcı bulunamadı
            $_SESSION['error_message'] = "Kullanıcı adı veya şifre hatalı.";
            header("Location: index.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Bir veritabanı hatası oluştu: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }

} else {
    // POST metodu ile gelinmediyse, anasayfaya yönlendir
    header("Location: index.php");
    exit;
}
?>