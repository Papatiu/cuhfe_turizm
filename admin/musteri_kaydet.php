<?php
// admin/musteri_kaydet.php
require_once 'includes/db.php';

// Güvenlik: Sadece admin giriş yaptıysa bu işlemi yapabilsin.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen tüm verileri al
    $customer_id = $_POST['customer_id'] ?? null; // Düzenleme için var olacak
    $tc_no = trim($_POST['tc_no']);
    $serial_no = trim($_POST['serial_no']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);
    $birth_place = trim($_POST['birth_place']);
    $birth_date = $_POST['birth_date'] ?: null;
    $gender = $_POST['gender'];
    $marital_status = $_POST['marital_status'];
    $religion = trim($_POST['religion']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $nationality = trim($_POST['nationality']);
    $passport_no = trim($_POST['passport_no']);
    $passport_expiry = $_POST['passport_expiry'] ?: null;

    $photo_name = $_POST['current_photo'] ?? 'default_user.png'; // Mevcut resim adı

    // --- Resim Yükleme İşlemi ---
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = '../images/customers/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['photo']['name']);
        $file_ext = strtolower($file_info['extension']);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            $photo_name = uniqid('customer_', true) . '.' . $file_ext;
            $target_file = $upload_dir . $photo_name;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $_SESSION['error_message'] = "Resim yüklenirken bir hata oluştu.";
                header("Location: musteriler.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Geçersiz resim formatı. Sadece JPG, PNG, GIF, WEBP izin verilir.";
            header("Location: musteriler.php");
            exit;
        }
    }

    try {
        if ($customer_id) {
            // --- GÜNCELLEME İŞLEMİ ---
            $sql = "UPDATE customers SET 
                tc_no = ?, serial_no = ?, first_name = ?, last_name = ?, father_name = ?, mother_name = ?, 
                birth_place = ?, birth_date = ?, gender = ?, marital_status = ?, religion = ?, phone = ?, 
                email = ?, address = ?, nationality = ?, passport_no = ?, passport_expiry = ?, photo = ?
                WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $tc_no, $serial_no, $first_name, $last_name, $father_name, $mother_name,
                $birth_place, $birth_date, $gender, $marital_status, $religion, $phone,
                $email, $address, $nationality, $passport_no, $passport_expiry, $photo_name,
                $customer_id
            ]);
            $_SESSION['success_message'] = "Müşteri bilgileri başarıyla güncellendi.";
        } else {
            // --- YENİ KAYIT İŞLEMİ ---
            $sql = "INSERT INTO customers (
                tc_no, serial_no, first_name, last_name, father_name, mother_name, 
                birth_place, birth_date, gender, marital_status, religion, phone, 
                email, address, nationality, passport_no, passport_expiry, photo, created_by_admin_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $tc_no, $serial_no, $first_name, $last_name, $father_name, $mother_name,
                $birth_place, $birth_date, $gender, $marital_status, $religion, $phone,
                $email, $address, $nationality, $passport_no, $passport_expiry, $photo_name,
                $_SESSION['admin_id']
            ]);
            $_SESSION['success_message'] = "Yeni müşteri başarıyla eklendi.";
        }
    } catch (PDOException $e) {
        // TC Kimlik No zaten kayıtlıysa, özel bir hata mesajı ver
        if ($e->errorInfo[1] == 1062) { // 1062 = Duplicate entry
            $_SESSION['error_message'] = "Bu TC Kimlik Numarası zaten kayıtlı.";
        } else {
            $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
        }
    }
    
    // İşlem bittikten sonra müşteri listesine yönlendir
    header("Location: musteriler.php");
    exit;

} else {
    // POST metodu ile gelinmediyse, dashboard'a yönlendir
    header("Location: dashboard.php");
    exit;
}
?>