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
    <title><?php echo htmlspecialchars($settings['site_title'] ?? 'Cuhfe Turizm - Hac ve Umre Organizasyonları'); ?>
    </title>
    <meta name="description"
        content="Cuhfe Turizm ile güvenli ve huzurlu Hac ve Umre yolculukları. Tecrübeli rehberler eşliğinde kutsal topraklara manevi bir seyahat.">
    <meta name="keywords"
        content="<?php echo htmlspecialchars($settings['site_keywords'] ?? 'hac, umre, turizm, cuhfe, mekke, medine'); ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (İkonlar için) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@300;400;500&display=swap"
        rel="stylesheet">
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
    <div class="top-bar text-white py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <a href="tel:<?php echo htmlspecialchars($settings['contact_whatsapp'] ?? '+905551234567'); ?>"
                    class="text-white me-3"><i class="fas fa-phone me-1"></i>
                    <?php echo htmlspecialchars($settings['contact_whatsapp'] ?? '+90 555 123 45 67'); ?></a>
                <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@cuhfeturizm.com'); ?>"
                    class="text-white"><i class="fas fa-envelope me-1"></i>
                    <?php echo htmlspecialchars($settings['contact_email'] ?? 'info@cuhfeturizm.com'); ?></a>
            </div>
            <div>
                <a href="<?php echo htmlspecialchars($settings['social_facebook'] ?? '#'); ?>" target="_blank"
                    class="text-white me-2"><i class="fab fa-facebook"></i></a>
                <a href="<?php echo htmlspecialchars($settings['social_instagram'] ?? '#'); ?>" target="_blank"
                    class="text-white me-2"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo htmlspecialchars($settings['social_youtube'] ?? '#'); ?>" target="_blank"
                    class="text-white"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- === NAVİGASYON MENÜSÜ (ORİJİNAL - Mobil uyumluluğu düzelttiğimiz haliyle) === -->
    <nav class="navbar navbar-expand-lg bg-light sticky-top shadow-sm main-nav">
        <div class="container">
            <a class="navbar-brand d-lg-none mx-auto" href="index.php"><img src="images/logo.png"
                    alt="Cuhfe Turizm Logo" class="main-logo-mobile"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span></button>
            <a class="navbar-brand d-none d-lg-block mx-auto" href="index.php"><img src="images/logo.png"
                    alt="Cuhfe Turizm Logo" class="main-logo"></a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100 d-flex justify-content-between">
                    <div class="d-lg-flex">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Anasayfa</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Kurumsal</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Hac Programları</a></li>
                    </div>
                    <div class="d-lg-flex">
                        <li class="nav-item"><a class="nav-link" href="#">Umre Programları</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Kudüs Turları</a></li>
                        <li class="nav-item"><a class="nav-link" href="iletisim.php">İletişim</a></li>
                    </div>
                </ul>
            </div>
        </div>
    </nav>

    <!-- === ANA MANŞET & TUR ARAMA FORMU (ORİJİNAL) === -->
    <header class="hero-section">
        <div class="hero-image" style="background-image: url('images/slider1.webp');"></div>
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
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <!-- Sütun 1: Şirket Bilgisi -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="index.php"><img src="images/logo.png" alt="Cuhfe Turizm Logo"></a>
                        </div>
                        <p class="footer-text mt-3">
                            <?php echo htmlspecialchars($settings['footer_text'] ?? 'Huzur ve güvenle çıktığınız bu kutlu yolda, manevi rehberiniz olmak için buradayız.'); ?>
                        </p>
                        <div class="footer-social-icons mt-3">
                            <a href="<?php echo htmlspecialchars($settings['social_facebook'] ?? '#'); ?>"
                                target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="<?php echo htmlspecialchars($settings['social_instagram'] ?? '#'); ?>"
                                target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="<?php echo htmlspecialchars($settings['social_youtube'] ?? '#'); ?>"
                                target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Sütun 2: Hızlı Linkler -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4 class="widget-title">Sayfalar</h4>
                        <ul class="list-unstyled footer-links">
                            <li><a href="index.php">Anasayfa</a></li>
                            <li><a href="#">Kurumsal</a></li>
                            <li><a href="#">Hac Programları</a></li>
                            <li><a href="#">Umre Programları</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="iletisim.php">İletişim</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Sütun 3: Turlarımız -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4 class="widget-title">Turlarımız</h4>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#">Ekonomik Umre</a></li>
                            <li><a href="#">Lüks Umre</a></li>
                            <li><a href="#">Ramazan Umresi</a></li>
                            <li><a href="#">2025 Hac Programı</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Sütun 4: İletişim Bilgileri -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4 class="widget-title">İletişim</h4>
                        <ul class="list-unstyled footer-contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <p><?php echo htmlspecialchars($settings['contact_address'] ?? 'Adres bilgisi girilmemiş.'); ?>
                                </p>
                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <a
                                    href="tel:<?php echo htmlspecialchars($settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone'] ?? 'Telefon bilgisi girilmemiş.'); ?></a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a
                                    href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email'] ?? 'Email bilgisi girilmemiş.'); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Copyright Bölümü -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-text">
                        <p>© <?php echo date("Y"); ?> Cuhfe Turizm. Tüm Hakları Saklıdır.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

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