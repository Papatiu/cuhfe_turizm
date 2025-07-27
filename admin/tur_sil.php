<?php
// admin/tur_sil.php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php"); exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php"); exit;
}

$tour_id = $_GET['id'];

try {
    // Silmeden önce tur bilgilerini al (tour_type'ı bilmek için) ve resim adını al
    $stmt_find = $db->prepare("SELECT tour_type, image FROM tours WHERE id = ?");
    $stmt_find->execute([$tour_id]);
    $tour = $stmt_find->fetch(PDO::FETCH_ASSOC);
    
    if($tour) {
        $tour_type = $tour['tour_type'];

        // Veritabanından turu sil
        $stmt_delete = $db->prepare("DELETE FROM tours WHERE id = ?");
        $stmt_delete->execute([$tour_id]);

        // İlişkili resmi sunucudan sil
        if ($tour['image'] && file_exists('../images/tours/' . $tour['image'])) {
            unlink('../images/tours/' . $tour['image']);
        }
        
        $_SESSION['success_message'] = "Tur başarıyla silindi.";
        header("Location: " . $tour_type . "_turlari.php");
        exit;
    } else {
         $_SESSION['error_message'] = "Silinecek tur bulunamadı.";
         header("Location: dashboard.php");
         exit;
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Silme işlemi sırasında bir hata oluştu: " . $e->getMessage();
    header("Location: dashboard.php");
    exit;
}