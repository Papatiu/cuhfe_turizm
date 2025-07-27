<?php
// admin/tur_bitir.php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || !isset($_GET['tour_id']) || !is_numeric($_GET['tour_id'])) {
    header("Location: index.php"); exit;
}

$tour_id = $_GET['tour_id'];

try {
    $stmt = $db->prepare("UPDATE tours SET status = 'completed' WHERE id = ?");
    $stmt->execute([$tour_id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success_message'] = "Tur başarıyla 'Biten Turlar' arşivi'ne taşındı.";
    } else {
        $_SESSION['error_message'] = "Tur güncellenirken bir sorun oluştu veya tur bulunamadı.";
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
}

// İşlem sonrası Aktif Turlar sayfasına geri dön
header("Location: aktif_turlar.php");
exit;
?>