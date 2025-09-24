<?php
$tour_type = $_GET['type'] ?? 'hac';
$page_title = ucfirst($tour_type) . " Turu Ekle";
require_once 'includes/header.php';
?>

<h2 class="mb-4">Yeni <?php echo ucfirst($tour_type); ?> Turu Oluştur</h2>
<div class="card shadow">
    <div class="card-body">
        <form action="tur_ekle_kaydet.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tour_type" value="<?php echo htmlspecialchars($tour_type); ?>">
            <!-- Tüm form içeriği... (Öncekiyle aynı) -->
            <div class="row g-3">
                <!-- Temel Bilgiler -->
                <div class="col-md-8"><label for="title" class="form-label">Tur Başlığı</label><input type="text" class="form-control" id="title" name="title" required></div>
                <div class="col-md-4"><label for="image" class="form-label">Tur Resmi</label><input class="form-control" type="file" id="image" name="image"></div>
                <!-- Otel Bilgileri -->
                <div class="col-md-6"><label for="mecca_hotel" class="form-label">Mekke Oteli</label><input type="text" class="form-control" id="mecca_hotel" name="mecca_hotel"></div>
                <div class="col-md-6"><label for="medina_hotel" class="form-label">Medine Oteli</label><input type="text" class="form-control" id="medina_hotel" name="medina_hotel"></div>
                <!-- Süre ve Tarih -->
                <div class="col-md-3"><label for="duration_mecca" class="form-label">Süre (Mekke)</label><input type="number" class="form-control" id="duration_mecca" name="duration_mecca" value="0"></div>
                <div class="col-md-3"><label for="duration_medina" class="form-label">Süre (Medine)</label><input type="number" class="form-control" id="duration_medina" name="duration_medina" value="0"></div>
                <div class="col-md-3"><label for="departure_date" class="form-label">Gidiş Tarihi</label><input type="date" class="form-control" id="departure_date" name="departure_date"></div>
                <div class="col-md-3"><label for="return_date" class="form-label">Dönüş Tarihi</label><input type="date" class="form-control" id="return_date" name="return_date"></div>
                <!-- Fiyatlandırma -->
                <div class="col-md-4"><label for="price" class="form-label">Fiyat</label><input type="text" class="form-control" id="price" name="price" required></div>
                <div class="col-md-4"><label for="old_price" class="form-label">Eski Fiyat (Ops.)</label><input type="text" class="form-control" id="old_price" name="old_price"></div>
                <div class="col-md-4"><label for="currency" class="form-label">Para Birimi</label><select class="form-select" id="currency" name="currency">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="TRY">TRY</option>
                    </select></div>

                <div class="col-12">
                    <hr class="my-4">
                </div>

                <!-- <<<< YENİ EKLENEN HİZMET ALANLARI >>>> -->
                <div class="col-md-6 mb-3">
                    <label for="included_services" class="form-label">Fiyata Dahil Olan Hizmetler</label>
                    <textarea name="included_services" id="included_services" class="form-control ckeditor" rows="8"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="excluded_services" class="form-label">Fiyata Dahil Olmayan Hizmetler</label>
                    <textarea name="excluded_services" id="excluded_services" class="form-control ckeditor" rows="8"></textarea>
                </div>

                <div class="col-12">
                    <hr class="my-4">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">Bu tur sitede aktif olarak görünsün mü?</label>
                </div>
                <div class="text-end">
                    <a href="<?php echo htmlspecialchars($tour_type); ?>_turlari.php" class="btn btn-secondary">İptal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Turu Kaydet</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>