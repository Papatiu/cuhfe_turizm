<?php
require_once 'includes/db.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_file'])) {
    
    $upload_dir = '../images/gallery/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_info = pathinfo($_FILES['image_file']['name']);
    $file_ext = strtolower($file_info['extension']);
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($file_ext, $allowed_exts) && $_FILES['image_file']['error'] == 0) {
        $image_name = 'gallery_' . time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            $title = trim($_POST['title'] ?? '');
            
            $stmt = $db->prepare("INSERT INTO gallery (image_path, title) VALUES (?, ?)");
            $stmt->execute([$image_name, $title]);
            $_SESSION['success_message'] = "Resim başarıyla galeriye eklendi.";
        } else {
            $_SESSION['error_message'] = "Resim yüklenirken bir hata oluştu.";
        }
    } else {
        $_SESSION['error_message'] = "Geçersiz dosya formatı veya yükleme hatası!";
    }
}
header("Location: galeri_yonetimi.php");
exit;
?>