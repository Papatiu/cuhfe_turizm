<?php
// admin/tur_kaydet.php
require_once 'includes/db.php';

// Güvenlik: Sadece admin giriş yaptıysa bu işlemi yapabilsin.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al
    $tour_id = $_POST['tour_id'] ?? null; // Düzenleme için
    $tour_type = $_POST['tour_type'];
    $title = trim($_POST['title']);
    $mecca_hotel = trim($_POST['mecca_hotel']);
    $medina_hotel = trim($_POST['medina_hotel']);
    $duration_mecca = $_POST['duration_mecca'];
    $duration_medina = $_POST['duration_medina'];
    $departure_date = $_POST['departure_date'];
    $return_date = $_POST['return_date'];
    $price = $_POST['price'];
    $old_price = $_POST['old_price'] ?: null; // Boşsa NULL olarak ayarla
    $currency = $_POST['currency'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_name = $_POST['current_image'] ?? null; // Mevcut resim adı

    // --- Resim Yükleme İşlemi ---
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../images/tours/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['image']['name']);
        $file_ext = strtolower($file_info['extension']);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            // Güvenli ve eşsiz bir dosya adı oluştur
            $image_name = uniqid('tour_', true) . '.' . $file_ext;
            $target_file = $upload_dir . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $_SESSION['error_message'] = "Resim yüklenirken bir hata oluştu.";
                header("Location: " . $tour_type . "_turlari.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Geçersiz resim formatı. Sadece JPG, PNG, GIF, WEBP izin verilir.";
            header("Location: " . $tour_type . "_turlari.php");
            exit;
        }
    }


    try {
        if ($tour_id) {
            // --- GÜNCELLEME İŞLEMİ ---
            $sql = "UPDATE tours SET tour_type=?, title=?, medina_hotel=?, mecca_hotel=?, duration_medina=?, duration_mecca=?, departure_date=?, return_date=?, price=?, old_price=?, currency=?, image=?, is_active=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $tour_type, $title, $medina_hotel, $mecca_hotel, $duration_medina, $duration_mecca, $departure_date, $return_date, $price, $old_price, $currency, $image_name, $is_active, $tour_id
            ]);
            $_SESSION['success_message'] = "Tur başarıyla güncellendi.";

        } else {
            // --- YENİ KAYIT İŞLEMİ ---
            $sql = "INSERT INTO tours (tour_type, title, medina_hotel, mecca_hotel, duration_medina, duration_mecca, departure_date, return_date, price, old_price, currency, image, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $tour_type, $title, $medina_hotel, $mecca_hotel, $duration_medina, $duration_mecca, $departure_date, $return_date, $price, $old_price, $currency, $image_name, $is_active
            ]);
            $new_tour_id = $db->lastInsertId(); // Eklenen turun ID'sini al
            $_SESSION['success_message'] = "Yeni tur başarıyla eklendi.";

            // YENİ TUR EKLENDİĞİNDE BİLDİRİM OLUŞTUR
            $notification_message = "Yeni bir " . ucfirst($tour_type) . " turu eklendi: " . $title;
            $notif_stmt = $db->prepare("INSERT INTO notifications (message, type, related_id) VALUES (?, ?, ?)");
            $notif_stmt->execute([$notification_message, 'new_tour', $new_tour_id]);
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
    }
    
    // İşlem bittikten sonra ilgili tur listesine yönlendir
    header("Location: " . $tour_type . "_turlari.php");
    exit;

} else {
    header("Location: dashboard.php");
    exit;
}
?>