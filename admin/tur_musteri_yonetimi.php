<?php
// admin/tur_musteri_yonetimi.php (HEADER HATASI GİDERİLMİŞ VERSİYON)

// 1. ADIM: Herhangi bir HTML basmadan önce veritabanını ve POST kontrolünü yap.
require_once 'includes/db.php'; 

// Form gönderildi mi diye kontrol et (POST İşlemleri)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_id_to_update = $_POST['tour_id'] ?? null;
    $selected_customer_ids = $_POST['customer_ids'] ?? [];

    if ($tour_id_to_update) {
        try {
            // Önce bu tura ait tüm eski kayıtları sil
            $delete_stmt = $db->prepare("DELETE FROM tour_registrations WHERE tour_id = ?");
            $delete_stmt->execute([$tour_id_to_update]);
            
            // Şimdi seçilen yeni müşterileri ekle
            if (!empty($selected_customer_ids)) {
                $insert_sql = "INSERT INTO tour_registrations (tour_id, customer_id) VALUES (?, ?)";
                $insert_stmt = $db->prepare($insert_sql);
                foreach ($selected_customer_ids as $customer_id) {
                    $insert_stmt->execute([$tour_id_to_update, $customer_id]);
                }
            }
            $_SESSION['success_message'] = "Tur kayıtları başarıyla güncellendi!";

        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Kayıt güncellenirken bir hata oluştu: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Güncellenecek tur seçilmedi.";
    }
    
    // YÖNLENDİRME ARTIK SORUNSUZ ÇALIŞACAK
    header("Location: tur_musteri_yonetimi.php?tour_id=" . $tour_id_to_update);
    exit;
}


// 2. ADIM: Tüm PHP mantığı bittikten sonra sayfanın görünümünü hazırlamaya başla.
$page_title = "Tur Müşteri Yönetimi";
// HTML çıktısını başlatan header.php dosyasını burada çağır.
require_once 'includes/header.php'; 


// 3. ADIM: Sayfada gösterilecek verileri çek (GET İşlemleri)
// (Bu kodlar zaten vardı, sadece yerleri değişti)

// Aktif ve yaklaşan turları çek
try {
    $tours_stmt = $db->query("SELECT id, title, departure_date FROM tours WHERE status != 'completed' AND is_active = 1 ORDER BY departure_date ASC");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tours = [];
    echo '<div class="alert alert-danger">Tur verileri çekilirken hata oluştu: ' . $e->getMessage() . '</div>';
}

// Tüm müşterileri çek
try {
    $customers_stmt = $db->query("SELECT id, tc_no, first_name, last_name FROM customers ORDER BY first_name ASC, last_name ASC");
    $customers = $customers_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $customers = [];
    echo '<div class="alert alert-danger">Müşteri verileri çekilirken hata oluştu: ' . $e->getMessage() . '</div>';
}

$selected_tour_id = $_GET['tour_id'] ?? null;
$registered_customers_ids = []; 
if ($selected_tour_id) {
    try {
        $reg_stmt = $db->prepare("SELECT customer_id FROM tour_registrations WHERE tour_id = ?");
        $reg_stmt->execute([$selected_tour_id]);
        $registered_customers_ids = $reg_stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $registered_customers_ids = [];
        echo '<div class="alert alert-danger">Kayıtlı müşteri verileri çekilirken hata oluştu: ' . $e->getMessage() . '</div>';
    }
}
?>

<!-- 4. ADIM: HTML içeriğini ekrana bas -->
<!-- (Bu bölüm tamamen aynı kalıyor) -->

<!-- Başarı/Hata Mesajları -->
<?php 
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
     echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['error_message']);
}
?>

<div class="card shadow">
    <div class="card-header">
        <h5 class="m-0 fw-bold text-primary">Tura Müşteri Ata</h5>
    </div>
    <div class="card-body">
        <form action="tur_musteri_yonetimi.php" method="GET" id="tourSelectForm" class="mb-4">
            <label for="tour_id_select" class="form-label fw-bold">1. Adım: Yönetilecek Turu Seçin</label>
            <div class="input-group">
                <select id="tour_id_select" name="tour_id" class="form-select form-select-lg">
                    <option value="">Lütfen bir tur seçin...</option>
                    <?php if (!empty($tours)): ?>
                        <?php foreach($tours as $tour): ?>
                            <option value="<?php echo $tour['id']; ?>" <?php echo ($selected_tour_id == $tour['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tour['title']); ?> (Tarih: <?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Yönetilecek aktif tur bulunamadı.</option>
                    <?php endif; ?>
                </select>
                <button class="btn btn-outline-primary" type="submit">Müşterileri Getir</button>
            </div>
        </form>

        <?php if ($selected_tour_id): ?>
            <hr>
            <form action="tur_musteri_yonetimi.php" method="POST">
                <input type="hidden" name="tour_id" value="<?php echo $selected_tour_id; ?>">
                <label class="form-label fw-bold">2. Adım: Tura Kaydedilecek Müşterileri İşaretleyin</label>
                
                <div class="customer-list-box border p-3 rounded" style="height: 400px; overflow-y: auto;">
                    <?php if (!empty($customers)): ?>
                        <?php foreach($customers as $customer): ?>
                            <div class="form-check fs-5 my-1">
                                <input class="form-check-input" type="checkbox" name="customer_ids[]" value="<?php echo $customer['id']; ?>" id="customer_<?php echo $customer['id']; ?>"
                                <?php echo in_array($customer['id'], $registered_customers_ids) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="customer_<?php echo $customer['id']; ?>">
                                    <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                    <small class="text-muted">(TC: <?php echo htmlspecialchars($customer['tc_no']); ?>)</small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">Hiç kayıtlı müşteri bulunamadı. <a href="musteri_ekle.php">Buradan</a> yeni müşteri ekleyebilirsiniz.</div>
                    <?php endif; ?>
                </div>
                
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save me-2"></i> Bu Tura Ait Kayıtları Güncelle</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-info text-center">Müşteri listesini görmek ve yönetmek için lütfen yukarıdan bir tur seçin ve "Müşterileri Getir" butonuna tıklayın.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>