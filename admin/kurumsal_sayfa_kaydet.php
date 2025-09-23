<?php
require_once 'includes/db.php';
require_once 'includes/functions.php'; // Güvenlik için

if (!isset($_SESSION['admin_logged_in'])) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->beginTransaction(); // Hata olursa işlemleri geri almak için

    try {
        // ----- HAKKIMIZDA BİLGİLERİNİ GÜNCELLE -----
        $h_id = $_POST['hakkimizda_id'];
        $h_title = $_POST['hakkimizda_title'];
        $h_content = $_POST['hakkimizda_content'];
        $h_image = $_POST['hakkimizda_current_image'];

        // Yeni resim yüklendiyse
        if (isset($_FILES['hakkimizda_image']) && $_FILES['hakkimizda_image']['error'] == 0) {
            $upload_dir = '../images/pages/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $new_image_name = 'page_' . uniqid() . '.' . pathinfo($_FILES['hakkimizda_image']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['hakkimizda_image']['tmp_name'], $upload_dir . $new_image_name)) {
                $h_image = $new_image_name; // Resim adını güncelle
            }
        }
        
        $stmt_h = $db->prepare("UPDATE pages SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt_h->execute([$h_title, $h_content, $h_image, $h_id]);


        // ----- İPTAL/İADE BİLGİLERİNİ GÜNCELLE -----
        $i_id = $_POST['iptal_iade_id'];
        $i_title = $_POST['iptal_iade_title'];
        $i_content = $_POST['iptal_iade_content'];
        
        $stmt_i = $db->prepare("UPDATE pages SET title = ?, content = ? WHERE id = ?");
        $stmt_i->execute([$i_title, $i_content, $i_id]);


        $db->commit(); // Tüm işlemler başarılı, kaydet
        $_SESSION['success_message'] = "Kurumsal sayfalar başarıyla güncellendi.";

    } catch (PDOException $e) {
        $db->rollBack(); // Hata oluştu, tüm değişiklikleri geri al
        $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
    }

    header("Location: kurumsal_sayfalar.php");
    exit;
}