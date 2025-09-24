<?php
// 1. Veritabanı bağlantısı
require_once 'admin/includes/db.php';

// 2. Ayarları Çek
try {
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    error_log("Veritabanı Ayar Çekme Hatası: " . $e->getMessage());
    $settings = [];
}

// 3. SEO Değişkenlerini Tanımla
$page_title = $settings['site_title'] ?? 'Cuhfe Turizm - Hac ve Umre Organizasyonları';
$page_description = $settings['site_description'] ?? 'Cuhfe Turizm ile güvenli ve huzurlu Hac ve Umre yolculukları.';
$page_keywords = $settings['site_keywords'] ?? 'hac, umre, hac fiyatları, umre turları, cuhfe turizm';
$page_image = 'images/logo.png';

// 4. Öne Çıkan Turları Çek (Hem Hac hem Umre, en yeni 3 tanesi)
try {
    // is_active=1 olanları, oluşturulma tarihine göre en yeni 3 turu çek. Bu hem Hac hem Umre'yi kapsar.
    $featured_tours_query = $db->query("SELECT * FROM tours WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3");
    $featured_tours = $featured_tours_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_tours = [];
    error_log("Öne çıkan turlar çekilirken hata: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Etiketleri -->
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="author" content="Cuhfe Turizm">
    <link rel="canonical" href="http://www.cuhfeturizm.com.tr/" /> <!-- Anasayfa için statik link daha doğrudur -->

    <!-- Faviconlar (Lütfen dosyaları ana dizine veya /images/favicon/ klasörüne koyun) -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

    <!-- Sosyal Medya Etiketleri -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>" />
    <meta property="og:image" content="http://www.cuhfeturizm.com.tr/<?php echo $page_image; ?>" />

    <!-- CSS Dosyaları -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'header_menu.php'; ?>

    <!-- === ANA MANŞET === -->
    <header class="hero-section" style="background-image: url('images/slider2.webp');">
        <div class="hero-overlay"></div>
        <div class="container hero-content text-center">
            <h1 class="display-4 fw-bold text-white">Maneviyata Açılan Kapınız</h1>
            <p class="lead text-white-75 mb-4">Cuhfe Turizm güvencesiyle unutulmaz bir Hac & Umre deneyimi yaşayın.</p>
            <a href="#one-cikan-turlar" class="btn btn-warning btn-lg">Programlarımızı Keşfedin</a>
        </div>
    </header>

    <!-- === ÖNE ÇIKAN TURLAR (TAMAMEN YENİLENMİŞ VE HATASIZ) === -->
    <section id="one-cikan-turlar" class="featured-tours py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Öne Çıkan Programlarımız</h2>
            <div class="row mt-4 justify-content-center">

                <?php if (!empty($featured_tours)): ?>
                    <?php foreach ($featured_tours as $tour): ?>
                        <div class="col-lg-4 col-md-6 mb-4 d-flex">
                            <div class="card tour-card-v2 h-100 shadow-sm w-100">
                                <div class="tour-image-container">
                                    <?php
                                    // SAVUNMALI LİNK OLUŞTURMA
                                    // Eğer slug varsa link oluştur, yoksa link '#' olsun ki site patlamasın.
                                    $tour_slug = $tour['slug'] ?? null;
                                    $tour_link = $tour_slug ? 'tur_detay.php?slug=' . htmlspecialchars($tour_slug) : '#';
                                    ?>
                                    <a href="<?php echo $tour_link; ?>">
                                        <img src="images/tours/<?php echo htmlspecialchars($tour['image'] ?? 'turcard.png'); ?>" class="card-img-top"
                                            alt="<?php echo htmlspecialchars($tour['title'] ?? 'Tur afişi'); ?>"
                                            onerror="this.src='images/turcard.png';">
                                    </a>
                                    <div class="tour-price-banner">
                                        <?php echo number_format($tour['price'] ?? 0, 0); ?> <?php echo htmlspecialchars($tour['currency'] ?? 'USD'); ?> <small>Kişi başı</small>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($tour['title'] ?? 'Tur Başlığı'); ?></h5>
                                    <ul class="tour-details list-unstyled">
                                        <li><i class="fas fa-map-marker-alt"></i> <strong>Medine:</strong> <?php echo htmlspecialchars($tour['medina_hotel'] ?? 'Belirtilmedi'); ?></li>
                                        <li><i class="fas fa-map-marker-alt"></i> <strong>Mekke:</strong> <?php echo htmlspecialchars($tour['mecca_hotel'] ?? 'Belirtilmedi'); ?></li>
                                        <li><i class="fas fa-moon"></i> <strong>Süre:</strong> <?php echo htmlspecialchars(($tour['duration_medina'] ?? 0) + ($tour['duration_mecca'] ?? 0)); ?> Gün</li>
                                        <li><i class="fas fa-plane-departure"></i> <strong>Gidiş Tarihi:</strong> <?php echo date('d.m.Y', strtotime($tour['departure_date'] ?? 'now')); ?></li>
                                    </ul>
                                    <a href="<?php echo $tour_link; ?>" class="btn btn-outline-primary w-100 mt-auto">Turu İncele</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center" role="alert">
                            Yaklaşan bir tur programı bulunmamaktadır. Lütfen daha sonra tekrar kontrol ediniz.
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <!-- === OFİSİMİZE BEKLERİZ BÖLÜMÜ === -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-white">
                    <h2 class="display-5 fw-bold">Ofisimize gelin ve bir kahvemizi için.</h2>
                    <p class="lead"><?php echo htmlspecialchars($settings['footer_text'] ?? 'Hayalinizdeki kutlu yolculuğu planlamak için buradayız.'); ?></p>
                </div>
                <div class="col-lg-5">
                    <div class="contact-info-box">
                        <div class="info-item"><i class="fas fa-map-marked-alt"></i>
                            <div class="text">
                                <h5>Adres</h5>
                                <p><?php echo htmlspecialchars($settings['contact_address'] ?? 'Adres bilgisi'); ?></p>
                            </div>
                        </div>
                        <div class="info-item"><i class="fas fa-phone-alt"></i>
                            <div class="text">
                                <h5>Bizi Arayın</h5>
                                <p><?php echo htmlspecialchars($settings['contact_phone'] ?? 'Telefon bilgisi'); ?></p>
                            </div>
                        </div>
                        <div class="info-item"><i class="fas fa-envelope-open-text"></i>
                            <div class="text">
                                <h5>Email Adresimiz</h5>
                                <p><?php echo htmlspecialchars($settings['contact_email'] ?? 'E-posta bilgisi'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('footer.php'); ?>

    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', ($settings['contact_whatsapp'] ?? '')); ?>?text=Merhaba, sitenizden ulaşıyorum." class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <!-- Harita için Google Maps API anahtarını eklemeyi unutma -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script> -->
</body>

</html>