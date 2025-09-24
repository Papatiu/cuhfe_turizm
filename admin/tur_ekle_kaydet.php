<?php
// admin/tur_ekle_kaydet.php (TAMAMEN GÜNCELLENMİŞ)

require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen tüm verileri al
    $tour_id = $_POST['tour_id'] ?? null;
    $tour_type = $_POST['tour_type'];
    $title = trim($_POST['title']);
    $slug = generateSlug($title); // Slug'ı oluştur

    // Slug'ın eşsiz olduğundan emin ol
    $check_sql = "SELECT id FROM tours WHERE slug = ? AND id != ?";
    $stmt_check = $db->prepare($check_sql);
    $stmt_check->execute([$slug, $tour_id ?? 0]);
    if ($stmt_check->rowCount() > 0) {
        $slug .= '-' . time();
    }

    $mecca_hotel = trim($_POST['mecca_hotel']);
    $medina_hotel = trim($_POST['medina_hotel']);
    $duration_mecca = $_POST['duration_mecca'];
    $duration_medina = $_POST['duration_medina'];
    $departure_date = $_POST['departure_date'];
    $return_date = $_POST['return_date'];
    $price = $_POST['price'];
    $old_price = $_POST['old_price'] ?: null;
    $currency = $_POST['currency'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // << YENİ EKLENEN VERİLER >>
    $description = $_POST['description'] ?? '';
    $included_services = $_POST['included_services'] ?? '';
    $excluded_services = $_POST['excluded_services'] ?? '';

    $image_name = $_POST['current_image'] ?? null;

    // Resim Yükleme İşlemi (Bu kısım aynı kalıyor)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../images/tours/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_info = pathinfo($_FILES['image']['name']);
        $file_ext = strtolower($file_info['extension']);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($file_ext, $allowed_exts)) {
            $image_name = uniqid('tour_', true) . '.' . $file_ext;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                $_SESSION['error_message'] = "Resim yüklenirken bir hata oluştu.";
                header("Location: " . $tour_type . "_turlari.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Geçersiz resim formatı.";
            header("Location: " . $tour_type . "_turlari.php");
            exit;
        }
    }

    try {
        if ($tour_id) {
            // GÜNCELLEME SORGUSU (YENİ SÜTUNLAR EKLENDİ)
            $sql = "UPDATE tours SET tour_type=?, title=?, slug=?, medina_hotel=?, mecca_hotel=?, duration_medina=?, duration_mecca=?, departure_date=?, return_date=?, price=?, old_price=?, currency=?, image=?, is_active=?, description=?, included_services=?, excluded_services=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $tour_type,
                $title,
                $slug,
                $medina_hotel,
                $mecca_hotel,
                $duration_medina,
                $duration_mecca,
                $departure_date,
                $return_date,
                $price,
                $old_price,
                $currency,
                $image_name,
                $is_active,
                $description,
                $included_services,
                $excluded_services,
                $tour_id
            ]);
            $_SESSION['success_message'] = "Tur başarıyla güncellendi.";
        } else {
            // YENİ KAYIT SORGUSU (YENİ SÜTUNLAR EKLENDİ)
            $sql = "INSERT INTO tours (tour_type, title, slug, medina_hotel, mecca_hotel, duration_medina, duration_mecca, departure_date, return_date, price, old_price, currency, image, is_active, description, included_services, excluded_services) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $tour_type,
                $title,
                $slug,
                $medina_hotel,
                $mecca_hotel,
                $duration_medina,
                $duration_mecca,
                $departure_date,
                $return_date,
                $price,
                $old_price,
                $currency,
                $image_name,
                $is_active,
                $description,
                $included_services,
                $excluded_services
            ]);
            $_SESSION['success_message'] = "Yeni tur başarıyla eklendi.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
    }

    header("Location: " . $tour_type . "_turlari.php");
    exit;
}
