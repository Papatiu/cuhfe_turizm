<?php
// admin/tur_ekle.php

// Gelen 'type' parametresini al (hac mı, umre mi)
$tour_type = $_GET['type'] ?? 'hac';
$page_title = ucfirst($tour_type) . " Turu Ekle"; // Sayfa başlığını dinamik yap
require_once 'includes/header.php';

// Form gönderildi mi kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // BURAYA FORM KAYDETME PHP KODLARI GELECEK. BİR SONRAKİ ADIMDA YAPACAĞIZ.
    // Şimdilik formu gösterelim.
}

?>

<h2 class="mb-4">Yeni <?php echo ucfirst($tour_type); ?> Turu Oluştur</h2>

<div class="card shadow">
    <div class="card-body">
        <form action="tur_ekle_kaydet.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tour_type" value="<?php echo htmlspecialchars($tour_type); ?>">

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tur Başlığı / Kodu</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="image" class="form-label">Tur Resmi</label>
                    <input class="form-control" type="file" id="image" name="image">
                </div>
            </div>

            <h5 class="mt-4 border-bottom pb-2 mb-3">Otel Bilgileri</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="mecca_hotel" class="form-label">Mekke Oteli</label>
                    <input type="text" class="form-control" id="mecca_hotel" name="mecca_hotel">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="medina_hotel" class="form-label">Medine Oteli</label>
                    <input type="text" class="form-control" id="medina_hotel" name="medina_hotel">
                </div>
            </div>

            <h5 class="mt-4 border-bottom pb-2 mb-3">Süre ve Tarih</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="duration_mecca" class="form-label">Süre (Mekke - Gece)</label>
                    <input type="number" class="form-control" id="duration_mecca" name="duration_mecca" value="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="duration_medina" class="form-label">Süre (Medine - Gece)</label>
                    <input type="number" class="form-control" id="duration_medina" name="duration_medina" value="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="departure_date" class="form-label">Gidiş Tarihi</label>
                    <input type="date" class="form-control" id="departure_date" name="departure_date">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="return_date" class="form-label">Dönüş Tarihi</label>
                    <input type="date" class="form-control" id="return_date" name="return_date">
                </div>
            </div>

            <h5 class="mt-4 border-bottom pb-2 mb-3">Fiyatlandırma</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">İndirimli Fiyat</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="old_price" class="form-label">Normal Fiyat (Üstü Çizili)</label>
                    <input type="text" class="form-control" id="old_price" name="old_price">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="currency" class="form-label">Para Birimi</label>
                    <select class="form-select" id="currency" name="currency">
                        <option value="USD">USD ($)</option>
                        <option value="EUR">EUR (€)</option>
                        <option value="TRY">TRY (₺)</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Tur Durumu</label>
                    <select class="form-select" id="status" name="status">
                        <option value="upcoming" selected>Yaklaşan</option>
                        <option value="active">Aktif (Devam Eden)</option>
                    </select>
                </div>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">Bu tur sitede aktif olarak görünsün mü?</label>
            </div>

            <div class="text-end">
                <a href="hac_turlari.php" class="btn btn-secondary">İptal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Turu Kaydet</button>
            </div>
        </form>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>