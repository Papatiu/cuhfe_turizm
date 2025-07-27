<?php
// admin/mesaj_sil.php
require_once 'includes/db.php';

// Güvenlik: Sadece admin giriş yaptıysa bu işlemi yapabilsin.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// ID'nin gelip gelmediğini kontrol et
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Geçersiz mesaj ID'si.";
    header("Location: gelen_mesajlar.php");
    exit;
}

$message_id = $_GET['id'];

try {
    $stmt = $db->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$message_id]);
    
    $_SESSION['success_message'] = "Mesaj başarıyla silindi.";

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Silme işlemi sırasında bir hata oluştu: " . $e->getMessage();
}

header("Location: gelen_mesajlar.php");
exit;
?>