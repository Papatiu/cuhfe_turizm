<?php
// Session'ı başlatıyoruz
session_start();

// Veritabanı bağlantımızı dahil ediyoruz
require_once 'admin/includes/db.php';

// Sadece POST metodu ile bu sayfaya gelinmişse işlem yap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Formdan gelen verileri al ve temizle
    // htmlspecialchars XSS saldırılarına, trim() ise baştaki sondaki boşluklara karşı önlemdir.
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // 2. Zorunlu alanların dolu olup olmadığını kontrol et
    if (empty($name) || empty($surname) || empty($email) || empty($message)) {
        $_SESSION['form_mesaj'] = ['tur' => 'hata', 'metin' => 'Lütfen ad, soyad, e-posta ve mesaj alanlarını doldurunuz.'];
        header('Location: iletisim.php');
        exit;
    }

    // 3. E-posta adresinin geçerli olup olmadığını kontrol et
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['form_mesaj'] = ['tur' => 'hata', 'metin' => 'Lütfen geçerli bir e-posta adresi giriniz.'];
        header('Location: iletisim.php');
        exit;
    }

    // 4. Veritabanına kaydetmeye hazırla
    // Veritabanı tablosunda 'full_name' olduğu için ad ve soyadı birleştiriyoruz
    $full_name = $name . ' ' . $surname;

    try {
        // SQL Injection saldırılarını önlemek için Prepared Statements kullanmak ÇOK ÖNEMLİDİR.
        $sql = "INSERT INTO contacts (full_name, email, phone, message) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        // Sorguyu çalıştır
        $stmt->execute([$full_name, $email, $phone, $message]);

        // İşlem başarılıysa
        $_SESSION['form_mesaj'] = ['tur' => 'basari', 'metin' => 'Mesajınız başarıyla gönderilmiştir. En kısa sürede sizinle iletişime geçeceğiz. Teşekkür ederiz!'];

    } catch (PDOException $e) {
        // İşlem başarısızsa
        // Gerçek kullanıcıya detaylı veritabanı hatası göstermek yerine loglayıp genel bir mesaj veriyoruz.
        error_log("İletişim Formu Kayıt Hatası: " . $e->getMessage());
        $_SESSION['form_mesaj'] = ['tur' => 'hata', 'metin' => 'Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'];
    }

    // Her durumda kullanıcıyı iletişim sayfasına geri yönlendir
    header('Location: iletisim.php');
    exit;

} else {
    // Eğer bu sayfaya doğrudan (GET metoduyla) erişilmeye çalışılırsa, anasayfaya yönlendir.
    header('Location: index.php');
    exit;
}
?>