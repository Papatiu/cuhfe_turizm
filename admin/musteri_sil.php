<?php
// admin/musteri_sil.php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php"); exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: musteriler.php"); exit;
}

$customer_id = $_GET['id'];

try {
    // Silmeden önce müşterinin resim adını al
    $stmt_find = $db->prepare("SELECT photo FROM customers WHERE id = ?");
    $stmt_find->execute([$customer_id]);
    $customer = $stmt_find->fetch(PDO::FETCH_ASSOC);
    
    if($customer) {
        // Veritabanından müşteriyi sil
        $stmt_delete = $db->prepare("DELETE FROM customers WHERE id = ?");
        $stmt_delete->execute([$customer_id]);

        // İlişkili resmi sunucudan sil (eğer varsayılan resim değilse)
        $photo_path = '../images/customers/' . $customer['photo'];
        if ($customer['photo'] && $customer['photo'] != 'default_user.png' && file_exists($photo_path)) {
            unlink($photo_path);
        }
        
        $_SESSION['success_message'] = "Müşteri başarıyla silindi.";
    } else {
         $_SESSION['error_message'] = "Silinecek müşteri bulunamadı.";
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Silme işlemi sırasında bir hata oluştu: " . $e->getMessage();
}

header("Location: musteriler.php");
exit;
?>