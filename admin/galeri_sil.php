<?php
require_once 'includes/db.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: index.php"); exit; }

if (isset($_GET['id']) && isset($_GET['file'])) {
    $image_id = $_GET['id'];
    $file_name = $_GET['file'];
    
    // Veritabanından sil
    $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$image_id]);
    
    // Sunucudan dosyayı sil
    $file_path = '../images/gallery/' . $file_name;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    $_SESSION['success_message'] = "Resim başarıyla silindi.";
}
header("Location: galeri_yonetimi.php");
exit;
?>