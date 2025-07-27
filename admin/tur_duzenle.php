<?php
// admin/tur_duzenle.php
require_once 'includes/header.php';

// ID'nin gelip gelmediğini kontrol et
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Geçersiz tur ID'si.";
    header("Location: dashboard.php");
    exit;
}

$tour_id = $_GET['id'];

// Veritabanından düzenlenecek tur bilgilerini çek
$stmt = $db->prepare("SELECT * FROM tours WHERE id = ?");
$stmt->execute([$tour_id]);
$tour = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tour) {
    $_SESSION['error_message'] = "Tur bulunamadı.";
    header("Location: dashboard.php");
    exit;
}

$page_title = "Düzenle: " . htmlspecialchars($tour['title']);

?>

<h2 class="mb-4">Turu Düzenle</h2>

<div class="card shadow">
    <div class="card-body">
        <form action="tur_kaydet.php" method="POST" enctype="multipart/form-data">
            <!-- Düzenleme için tur ID'sini ve mevcut resmi gizli input'larla gönder -->
            <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($tour['image']); ?>">
            <input type="hidden" name="tour_type" value="<?php echo htmlspecialchars($tour['tour_type']); ?>">

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tur Başlığı / Kodu</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="<?php echo htmlspecialchars($tour['title']); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="image" class="form-label">Tur Resmi (Değiştirmek için seçin)</label>
                    <input class="form-control" type="file" id="image" name="image">
                    <?php if ($tour['image']): ?>
                        <small class="form-text text-muted">Mevcut resim:
                            <?php echo htmlspecialchars($tour['image']); ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <h5 class="mt-4 border-bottom pb-2 mb-3">Otel Bilgileri</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="mecca_hotel" class="form-label">Mekke Oteli</label>
                    <input type="text" class="form-control" id="mecca_hotel" name="mecca_hotel"
                        value="<?php echo htmlspecialchars($tour['mecca_hotel']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="medina_hotel" class="form-label">Medine Oteli</label>
                    <input type="text" class="form-control" id="medina_hotel" name="medina_hotel"
                        value="<?php echo htmlspecialchars($tour['medina_hotel']); ?>">
                </div>
            </div>

            <!-- Diğer form alanları (Süre, Tarih, Fiyat) da aynı 'value' mantığıyla doldurulacak -->

            <h5 class="mt-4 border-bottom pb-2 mb-3">Süre ve Tarih</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="duration_mecca" class="form-label">Süre (Mekke - Gece)</label>
                    <input type="number" class="form-control" id="duration_mecca" name="duration_mecca"
                        value="<?php echo $tour['duration_mecca']; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="duration_medina" class="form-label">Süre (Medine - Gece)</label>
                    <input type="number" class="form-control" id="duration_medina" name="duration_medina"
                        value="<?php echo $tour['duration_medina']; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="departure_date" class="form-label">Gidiş Tarihi</label>
                    <input type="date" class="form-control" id="departure_date" name="departure_date"
                        value="<?php echo $tour['departure_date']; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="return_date" class="form-label">Dönüş Tarihi</label>
                    <input type="date" class="form-control" id="return_date" name="return_date"
                        value="<?php echo $tour['return_date']; ?>">
                </div>
            </div>

            <h5 class="mt-4 border-bottom pb-2 mb-3">Fiyatlandırma</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">İndirimli Fiyat</label>
                    <input type="text" class="form-control" id="price" name="price"
                        value="<?php echo htmlspecialchars($tour['price']); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="old_price" class="form-label">Normal Fiyat (Üstü Çizili)</label>
                    <input type="text" class="form-control" id="old_price" name="old_price"
                        value="<?php echo htmlspecialchars($tour['old_price'] ?? ''); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="currency" class="form-label">Para Birimi</label>
                    <select class="form-select" id="currency" name="currency">
                        <option value="USD" <?php echo ($tour['currency'] == 'USD') ? 'selected' : ''; ?>>USD ($)</option>
                        <option value="EUR" <?php echo ($tour['currency'] == 'EUR') ? 'selected' : ''; ?>>EUR (€)</option>
                        <option value="TRY" <?php echo ($tour['currency'] == 'TRY') ? 'selected' : ''; ?>>TRY (₺)</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Tur Durumu</label>
                    <select class="form-select" id="status" name="status">
                        <option value="upcoming" <?php echo ($tour['status'] == 'upcoming') ? 'selected' : ''; ?>>Yaklaşan
                        </option>
                        <option value="active" <?php echo ($tour['status'] == 'active') ? 'selected' : ''; ?>>Aktif (Devam
                            Eden)</option>
                    </select>
                </div>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php echo $tour['is_active'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_active">Bu tur sitede aktif olarak görünsün mü?</label>
            </div>

            <div class="text-end">
                <a href="<?php echo htmlspecialchars($tour['tour_type']); ?>_turlari.php"
                    class="btn btn-secondary">İptal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Değişiklikleri
                    Kaydet</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>