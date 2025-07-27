<?php
// admin/tur_detay.php (HEADER HATASI GİDERİLMİŞ SON VERSİYON)

// 1. ADIM: Herhangi bir HTML basmadan önce veritabanını ve POST kontrolünü yap.
require_once 'includes/db.php'; 

// ID kontrolü
if (!isset($_GET['tour_id']) || !is_numeric($_GET['tour_id'])) {
    $_SESSION['error_message'] = "Geçersiz tur ID'si.";
    header("Location: aktif_turlar.php"); exit;
}
$tour_id = $_GET['tour_id'];

// --- FORM GÖNDERİLDİĞİNDE ÇALIŞACAK BÖLÜM (POST İŞLEMLERİ) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Yolcu Bilet/Not Bilgilerini Güncelleme
    if(isset($_POST['update_registrations'])) {
        if(isset($_POST['registration_id'])) {
            $registration_ids = $_POST['registration_id'];
            $ticket_infos = $_POST['ticket_info'];
            $notes = $_POST['notes'];
            $update_stmt = $db->prepare("UPDATE tour_registrations SET ticket_info = ?, notes = ? WHERE id = ?");
            for($i=0; $i<count($registration_ids); $i++) {
                $update_stmt->execute([$ticket_infos[$i], $notes[$i], $registration_ids[$i]]);
            }
            $_SESSION['success_message'] = "Yolcu bilgileri güncellendi.";
        }
    }

    // Yeni Araç Ekleme
    if(isset($_POST['add_vehicle'])) {
        $insert_stmt = $db->prepare("INSERT INTO tour_vehicles (tour_id, vehicle_type, company_name, plate_number, driver_name, driver_phone) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->execute([
            $tour_id, $_POST['vehicle_type'], $_POST['company_name'], $_POST['plate_number'], $_POST['driver_name'], $_POST['driver_phone']
        ]);
         $_SESSION['success_message'] = "Yeni araç eklendi.";
    }
    
    // YÖNLENDİRME ARTIK SORUNSUZ ÇALIŞACAK
    header("Location: tur_detay.php?tour_id=" . $tour_id);
    exit;
}


// 2. ADIM: Tüm PHP mantığı bittikten sonra sayfanın görünümünü hazırlamaya başla.
// HTML çıktısını başlatan header.php dosyasını burada çağır.
require_once 'includes/header.php'; 

// --- SAYFA YÜKLENDİĞİNDE ÇALIŞACAK BÖLÜM (GET İŞLEMLERİ) ---

// Tur ana bilgilerini çek
$tour_stmt = $db->prepare("SELECT * FROM tours WHERE id = ?");
$tour_stmt->execute([$tour_id]);
$tour = $tour_stmt->fetch(PDO::FETCH_ASSOC);

if (!$tour) {
     $_SESSION['error_message'] = "Tur bulunamadı.";
     header("Location: aktif_turlar.php"); exit; // Bu yönlendirme sorun çıkarmaz çünkü 'header.php'den önce
}
$page_title = "Tur Raporu: " . htmlspecialchars($tour['title']);

// Tura kayıtlı müşterileri çek
$stmt = $db->prepare("
    SELECT c.*, tr.ticket_info, tr.notes, tr.id as registration_id
    FROM tour_registrations tr JOIN customers c ON tr.customer_id = c.id
    WHERE tr.tour_id = ? ORDER BY c.last_name, c.first_name");
$stmt->execute([$tour_id]);
$registered_customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tura ait araçları çek
$vehicles_stmt = $db->prepare("SELECT * FROM tour_vehicles WHERE tour_id = ?");
$vehicles_stmt->execute([$tour_id]);
$tour_vehicles = $vehicles_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!-- Buradan sonraki HTML bölümü tamamen aynı kalıyor -->

<!-- Sayfayı yazdırmak için CSS -->
<style>
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area { position: absolute; left: 0; top: 0; width: 100%; }
    .no-print { display: none !important; }
}
</style>

<!-- BAŞARI/HATA MESAJLARI -->
<?php 
if(isset($_SESSION['success_message'])) { 
    echo '<div class="alert alert-success alert-dismissible fade show no-print" role="alert">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['success_message']);
}
?>

<div class="print-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0"><?php echo htmlspecialchars($tour['title']); ?> - Kafile Listesi</h2>
            <p class="lead text-muted">Gidiş: <?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?> - Dönüş: <?php echo date('d.m.Y', strtotime($tour['return_date'])); ?></p>
        </div>
        <div class="no-print">
            <button onclick="window.print();" class="btn btn-info"><i class="fas fa-print me-2"></i> Yazdır</button>
            <a href="aktif_turlar.php" class="btn btn-secondary">Geri Dön</a>
        </div>
    </div>
    
    <!-- YOLCU LİSTESİ FORMU -->
    <form action="tur_detay.php?tour_id=<?php echo $tour_id; ?>" method="POST">
        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="m-0 fw-bold text-primary">Yolcu Listesi (<?php echo count($registered_customers); ?> Kişi)</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Adı Soyadı</th>
                                <th>Telefon</th>
                                <th>TC / Pasaport No</th>
                                <th style="width: 15%;">Bilet Bilgisi (PNR)</th>
                                <th style="width: 25%;">Özel Notlar (Oda Tipi vb.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($registered_customers) > 0): ?>
                                <?php foreach($registered_customers as $index => $customer): ?>
                                    <tr>
                                        <input type="hidden" name="registration_id[]" value="<?php echo $customer['registration_id']; ?>">
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($customer['passport_no'] ?: $customer['tc_no']); ?></td>
                                        <td><input type="text" class="form-control form-control-sm" name="ticket_info[]" value="<?php echo htmlspecialchars($customer['ticket_info'] ?? ''); ?>"></td>
                                        <td><input type="text" class="form-control form-control-sm" name="notes[]" value="<?php echo htmlspecialchars($customer['notes'] ?? ''); ?>"></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-3">Bu tura henüz yolcu kaydedilmemiş.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if(count($registered_customers) > 0): ?>
            <div class="card-footer text-end no-print">
                <button type="submit" name="update_registrations" class="btn btn-primary"><i class="fas fa-save me-2"></i>Bilet/Not Bilgilerini Güncelle</button>
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ARAÇ BİLGİLERİ BÖLÜMÜ -->
<div class="card shadow mb-4 no-print">
    <div class="card-header"><h5 class="m-0 fw-bold text-primary">Araç Bilgileri</h5></div>
    <div class="card-body">
        <h6 class="card-title">Yeni Araç Ekle</h6>
        <form action="tur_detay.php?tour_id=<?php echo $tour_id; ?>" method="POST" class="mb-4">
             <div class="row g-2 align-items-end">
                <div class="col"><label class="form-label small">Araç Tipi</label><input name="vehicle_type" class="form-control form-control-sm" placeholder="Otobüs" value="Otobüs"></div>
                <div class="col"><label class="form-label small">Firma Adı</label><input name="company_name" class="form-control form-control-sm" placeholder="Firma Adı"></div>
                <div class="col"><label class="form-label small">Plaka</label><input name="plate_number" class="form-control form-control-sm" placeholder="34 ABC 123"></div>
                <div class="col"><label class="form-label small">Şoför Adı</label><input name="driver_name" class="form-control form-control-sm" placeholder="Şoför Adı"></div>
                <div class="col"><label class="form-label small">Şoför Telefon</label><input name="driver_phone" class="form-control form-control-sm" placeholder="Şoför Telefon"></div>
                <div class="col-auto"><button type="submit" name="add_vehicle" class="btn btn-success btn-sm">Araç Ekle</button></div>
            </div>
        </form>
        <hr>
        <h6 class="card-title">Mevcut Araçlar</h6>
        <ul class="list-group">
            <?php if(count($tour_vehicles) > 0): ?>
                <?php foreach($tour_vehicles as $vehicle): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($vehicle['plate_number']); ?></strong> - <?php echo htmlspecialchars($vehicle['vehicle_type']); ?>
                            <small class="text-muted d-block"><?php echo htmlspecialchars($vehicle['company_name']); ?> | Şoför: <?php echo htmlspecialchars($vehicle['driver_name']); ?> (<?php echo htmlspecialchars($vehicle['driver_phone']); ?>)</small>
                        </div>
                        <!-- Silme butonu eklenebilir -->
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">Bu tura henüz araç eklenmemiş.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>