<?php
// 1. Veritabanı bağlantısı SADECE buradan yapılacak. config.php yok.
require_once 'admin/includes/db.php';

// 2. Ayarları Çek
try {
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    // Veritabanına ulaşılamazsa sitenin çökmesini engellemek için varsayılan değerler ata
    error_log("Veritabanı Ayar Çekme Hatası: " . $e->getMessage());
    $settings = [];
}

$page_title = $settings['site_title'] ?? 'Cuhfe Turizm - Hac ve Umre Organizasyonları';
$page_description = $settings['site_description'] ?? 'Cuhfe Turizm ile güvenli ve huzurlu Hac ve Umre yolculukları. Yılların tecrübesi, Diyanet\'e uygun programlar ve uzman rehberler eşliğinde kutsal topraklara manevi bir seyahat.';
$page_keywords = $settings['site_keywords'] ?? 'hac, umre, hac fiyatları 2025, umre turları, cuhfe turizm, mekke, medine, kudüs turları';
$page_image = 'images/logo.png'; // Paylaşımlarda görünecek varsayılan resim

// 3. Öne Çıkan Turları Çek (Anasayfa için 3 tane)
try {
    $tours_query = $db->query("
        SELECT * FROM tours 
        WHERE is_active = 1 AND status != 'completed'
        ORDER BY departure_date ASC 
    ");
    $featured_tours = $tours_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Veritabanı Tur Çekme Hatası: " . $e->getMessage());
    $featured_tours = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 1. Temel SEO Etiketleri (Her Sayfa İçin Dinamik) -->
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="author" content="Cuhfe Turizm">
    <link rel="canonical" href="http://www.cuhfeturizm.com.tr<?php echo $_SERVER['REQUEST_URI']; ?>" />

    <!-- 2. Faviconlar (images/ klasöründen çağrılıyor) -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/kaaba.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/mekke.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/kaaba.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#0a2e5c">
    <meta name="theme-color" content="#ffffff">

    <!-- 3. Sosyal Medya (Open Graph - Facebook, LinkedIn, WhatsApp) Etiketleri -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.cuhfeturizm.com.tr<?php echo $_SERVER['REQUEST_URI']; ?>" />
    <meta property="og:image" content="http://www.cuhfeturizm.com.tr/<?php echo $page_image; ?>" />
    <meta property="og:site_name" content="Cuhfe Turizm" />
    <meta property="og:locale" content="tr_TR" />
    
    <!-- 4. Twitter Card Etiketleri -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="twitter:image" content="http://www.cuhfeturizm.com.tr/<?php echo $page_image; ?>">

    <!-- 5. Diğer Teknik Etiketler ve Kütüphaneler -->
    <meta name="robots" content="index, follow"> <!-- Arama motorlarına sayfayı indexle ve linkleri takip et diyoruz -->
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Kendi CSS Dosyamız -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="preloader">
        <div class="loader-container">
            <div class="spinner"></div>
            <img src="images/loading.png" alt="Yükleniyor..." class="loader-image">
        </div>
    </div>

    <!-- === ÜST BİLGİ BARI (DİNAMİK) === -->
  <?php include 'header_menu.php'; // Header ve menüyü ayrı bir dosyaya almak daha temizdir. Şimdilik buraya kopyalayalım. ?>

    <!-- === ANA MANŞET & TUR ARAMA FORMU (ORİJİNAL) === -->
    <header class="hero-section">
        <div class="hero-image" style="background-image: url('images/slider2.webp');"></div>
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-4 fw-bold text-white">Maneviyata Açılan Kapınız</h1>
                    <p class="lead text-white mb-4">Cuhfe Turizm güvencesiyle unutulmaz bir Hac & Umre deneyimi yaşayın.
                    </p>
                </div>
                <div class="col-lg-12">
                    <div class="tour-search-form shadow-lg">
                        <form action="turlar.php" method="GET" class="row g-3 align-items-center">
                            <div class="col-lg-3 col-md-6">
                                <input type="text" class="form-control" placeholder="Kelime ile ara (Örn: Ramazan)">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <select class="form-select">
                                    <option selected>Tur Tipi</option>
                                    <option value="umre">Umre</option>
                                    <option value="hac">Hac</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <select class="form-select">
                                    <option selected>Hangi Ay Gidilecek?</option>
                                    <option value="1">Ocak</option>
                                    <option value="2">Şubat</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="date" class="form-control" placeholder="Dönüş Tarihi">
                            </div>
                            <div class="col-lg-2 col-md-12">
                                <button type="submit" class="btn btn-primary w-100">Turları Listele</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- === ÖNE ÇIKAN TURLAR (DİNAMİK) === -->
    <section class="featured-tours py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Öne Çıkan Programlarımız</h2>
            <div class="row mt-4">
                <?php if (!empty($featured_tours)): ?>
                    <?php foreach ($featured_tours as $tour): ?>
                        <div class="col-lg-4 col-md-6 mb-4 d-flex">
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
                                        <li><i class="fas fa-map-marker-alt"></i> <strong>Medine:</strong>
                                            <?php echo htmlspecialchars($tour['medina_hotel']); ?></li>
                                        <li><i class="fas fa-map-marker-alt"></i> <strong>Mekke:</strong>
                                            <?php echo htmlspecialchars($tour['mecca_hotel']); ?></li>
                                        <li><i class="fas fa-moon"></i> <strong>Süre:</strong>
                                            <?php echo htmlspecialchars($tour['duration_medina']); ?> G. Medine -
                                            <?php echo htmlspecialchars($tour['duration_mecca']); ?> G. Mekke</li>
                                        <li><i class="fas fa-plane-departure"></i> <strong>Gidiş Tarihi:</strong>
                                            <?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?></li>
                                        <li><i class="fas fa-plane-arrival"></i> <strong>Dönüş Tarihi:</strong>
                                            <?php echo date('d.m.Y', strtotime($tour['return_date'])); ?></li>
                                    </ul>
                                    <a href="#" class="btn btn-outline-primary w-100 mt-auto">Turu İncele</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Tur Yoksa Gösterilecek Mesaj -->
                    <div class="col-12">
                        <div class="alert alert-info text-center" role="alert">
                            Yaklaşan bir tur programı bulunmamaktadır. Lütfen daha sonra tekrar kontrol ediniz.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- === OFİSİMİZE BEKLERİZ BÖLÜMÜ (DİNAMİK) === -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-white">
                    <h2 class="display-5 fw-bold">Ofisimize gelin ve<br>bir kahvemizi için.</h2>
                    <p class="lead">
                        <?php echo htmlspecialchars($settings['footer_text'] ?? 'Hayalinizdeki kutlu yolculuğu planlamak, aklınızdaki soruları yanıtlamak ve size en uygun programı birlikte seçmek için buradayız. Sizi ağırlamaktan mutluluk duyarız.'); ?>
                    </p>
                </div>
                <div class="col-lg-5">
                    <div class="contact-info-box">
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-map-marked-alt fa-2x"></i></div>
                            <div class="text">
                                <h5>Adres</h5>
                                <p><?php echo htmlspecialchars($settings['contact_address'] ?? 'Örnek Mah. Turizm Sk. No:1, İstanbul'); ?>
                                </p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-phone-alt fa-2x"></i></div>
                            <div class="text">
                                <h5>Bizi Arayın</h5>
                                <p><?php echo htmlspecialchars($settings['contact_phone'] ?? '+90 212 123 45 67'); ?>
                                </p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-envelope-open-text fa-2x"></i></div>
                            <div class="text">
                                <h5>Email Adresimiz</h5>
                                <p><?php echo htmlspecialchars($settings['contact_email'] ?? 'info@cuhfeturizm.com'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- === HARİTA BÖLÜMÜ (ORİJİNAL) === -->
    <section class="map-section py-5">
        <div class="container">
            <h2 class="section-title text-center">Ofisimiz Nerede?</h2>
            <div class="map-container shadow-lg">
                <div id="gmap_canvas"></div>
            </div>
        </div>
    </section>

    <!-- === FOOTER (ORİJİNAL - Dinamik yıl ile) === -->
    <!-- === FOOTER (YENİ VE GELİŞMİŞ TASARIM) === -->
      <?php include('footer.php'); ?>

    <!-- === WHATSAPP BUTONU (DİNAMİK) === -->
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', ($settings['contact_whatsapp'] ?? '')); ?>?text=Merhaba, sitenizden ulaşıyorum. Turlar hakkında bilgi alabilir miyim?"
        class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
</body>

</html>
