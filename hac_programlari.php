<?php
// Gerekli dosyaları dahil et
require_once 'admin/includes/db.php';

// Ayarları veritabanından çek
try {
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    error_log("Ayarlar çekilirken veritabanı hatası: " . $e->getMessage());
    $settings = [];
}

// SADECE Hac turlarını veritabanından çek ('hac' tipindekiler)
try {
    $tours_query = $db->prepare("SELECT * FROM tours WHERE tour_type = 'hac' AND is_active = 1 ORDER BY departure_date ASC");
    $tours_query->execute();
    $hac_tours = $tours_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Hac turları çekilirken hata: " . $e->getMessage());
    $hac_tours = [];
}

// Galerideki resimleri veritabanından çek
try {
    $gallery_query = $db->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
    $gallery_images = $gallery_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Galeri çekilirken hata: " . $e->getMessage());
    $gallery_images = [];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hac Programlarımız - <?php echo htmlspecialchars($settings['site_title'] ?? 'Cuhfe Turizm'); ?></title>
    <!-- Gerekli CSS dosyaları -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Lightbox CSS'i (Resimleri büyütmek için) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <?php include 'header_menu.php'; // Header ve menüyü ayrı bir dosyaya almak daha temizdir. Şimdilik buraya kopyalayalım. ?>
    <!-- Anasayfadaki nav bar kodunu buraya kopyalayabilirsin ya da include edebilirsin. Şimdilik boş bırakıyorum. -->
    
    <!-- Sayfa Başlığı Banner'ı -->
    <header class="page-header" style="background-image: url('images/slider4.jpg');">
        <div class="page-header-overlay"></div>
        <div class="container">
            <h1 class="page-title">Hac Programlarımız</h1>
            <p class="page-subtitle">Kutsal topraklara yapacağınız manevi yolculuk için en özel programlar</p>
        </div>
    </header>

    <!-- Hac Turları Listesi Bölümü -->
    <section class="hac-tours-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">2025 Hac Programları</h2>
            <div class="row">
                <?php if (!empty($hac_tours)): ?>
                    <?php foreach ($hac_tours as $tour): ?>
                        <div class="col-lg-4 col-md-6 mb-4 d-flex">
                           <!-- Tur kartı tasarımını anasayfadan alıyoruz -->
                           <div class="card tour-card-v2 h-100 shadow-sm w-100">
                               <div class="tour-image-container">
                                    <img src="images/tours/<?php echo htmlspecialchars($tour['image']); ?>" class="card-img-top"
                                        alt="<?php echo htmlspecialchars($tour['title']); ?>"
                                        onerror="this.src='images/turcard.png';">
                                    <div class="tour-price-banner">
                                        <?php if (!empty($tour['old_price']) && $tour['old_price'] > 0): ?>
                                            <span class="old-price"><?php echo number_format($tour['old_price'], 0); ?>
                                                <?php echo htmlspecialchars($tour['currency']); ?></span>
                                        <?php endif; ?>
                                        <?php echo number_format($tour['price'], 0); ?>
                                        <?php echo htmlspecialchars($tour['currency']); ?> <small>Kişi başı</small>
                                    </div>
                                </div>
                               <div class="card-body d-flex flex-column">
                                   <h5 class="card-title"><?php echo htmlspecialchars($tour['title']); ?></h5>
                                   <ul class="tour-details list-unstyled">
                                       <li><i class="fas fa-map-marker-alt"></i> <strong>Medine:</strong> <?php echo htmlspecialchars($tour['medina_hotel']); ?></li>
                                       <li><i class="fas fa-map-marker-alt"></i> <strong>Mekke:</strong> <?php echo htmlspecialchars($tour['mecca_hotel']); ?></li>
                                       <li><i class="fas fa-moon"></i> <strong>Süre:</strong> <?php echo htmlspecialchars($tour['duration_medina'] + $tour['duration_mecca']); ?> Gün</li>
                                       <li><i class="fas fa-plane-departure"></i> <strong>Gidiş:</strong> <?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?></li>
                                   </ul>
                                   <a href="#" class="btn btn-outline-primary w-100 mt-auto">Turu İncele</a>
                               </div>
                           </div>
                       </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12"><div class="alert alert-info text-center">Şu an için aktif bir Hac programı bulunmamaktadır.</div></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Galeri Bölümü -->
    <section class="gallery-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Hac ve Umre Hatıraları</h2>
            <div class="row g-3">
                <?php if (!empty($gallery_images)): ?>
                    <?php foreach($gallery_images as $image): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="images/gallery/<?php echo htmlspecialchars($image['image_path']); ?>" 
                           data-lightbox="hac-gallery" 
                           data-title="<?php echo htmlspecialchars($image['title']); ?>"
                           class="gallery-item">
                            <img src="images/gallery/<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>" class="img-fluid rounded shadow-sm">
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                     <div class="col-12"><div class="alert alert-secondary text-center">Galeride henüz hiç resim bulunmamaktadır.</div></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

  <?php include('footer.php'); ?>
    
    <!-- Gerekli JS Dosyaları -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Lightbox için jQuery gerekli -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="js/script.js"></script>

</body>
</html>