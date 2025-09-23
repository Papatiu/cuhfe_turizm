<?php
$page_title = "Galeri Yönetimi";
require_once 'includes/header.php';

// Veritabanından galerideki tüm resimleri çek
$stmt = $db->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Başarı/Hata Mesajları -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
    </div>
<?php endif; ?>


<!-- Resim Yükleme Formu -->
<div class="card shadow mb-4">
    <div class="card-header"><h5 class="m-0 fw-bold text-primary">Yeni Resim Yükle</h5></div>
    <div class="card-body">
        <form action="galeri_upload.php" method="POST" enctype="multipart/form-data">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label for="image_file" class="form-label">Resim Dosyası Seç (JPG, PNG, WEBP)</label>
                    <input type="file" class="form-control" id="image_file" name="image_file" required>
                </div>
                <div class="col-md-5">
                    <label for="title" class="form-label">Resim Başlığı (Opsiyonel)</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Örn: 2024 Hac Kafilesi Medine">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-upload me-2"></i> Yükle</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Yüklenmiş Resimler -->
<div class="card shadow">
    <div class="card-header"><h5 class="m-0 fw-bold text-primary">Galerideki Resimler</h5></div>
    <div class="card-body">
        <div class="row">
            <?php if (count($images) > 0): ?>
                <?php foreach($images as $image): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <img src="../images/gallery/<?php echo htmlspecialchars($image['image_path']); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                        <div class="card-body p-2">
                            <p class="card-text small"><?php echo htmlspecialchars($image['title'] ?? 'Başlık Yok'); ?></p>
                        </div>
                        <div class="card-footer p-2 text-end">
                             <a href="galeri_sil.php?id=<?php echo $image['id']; ?>&file=<?php echo $image['image_path']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu resmi silmek istediğinizden emin misiniz?');">
                                <i class="fas fa-trash-alt"></i> Sil
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><p class="text-center">Galeride henüz hiç resim yok.</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>