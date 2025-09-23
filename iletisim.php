<?php
// Session'ı başlatıyoruz (formdan gelecek başarı/hata mesajları için)
session_start();

// Veritabanı bağlantısını ve ayarları çekme işlemini dahil ediyoruz.
require_once 'admin/includes/db.php';

try {
    // Admin panelinde girdiğin ayarları veritabanından çekiyoruz
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    // Hata olursa varsayılan değerler atayalım ki site çökmesin
    error_log("Ayarlar çekilirken veritabanı hatası: " . $e->getMessage());
    $settings = [];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim - <?php echo htmlspecialchars($settings['site_title'] ?? 'Cuhfe Turizm'); ?></title>
    <meta name="description" content="Cuhfe Turizm ile iletişime geçin. Sorularınız, önerileriniz veya tur talepleriniz için bize ulaşın.">

    <!-- Gerekli Stil Dosyaları -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div id="preloader">
        <div class="loader-container">
            <div class="spinner"></div>
            <img src="images/loading.png" alt="Yükleniyor..." class="loader-image">
        </div>
    </div>

    <!-- === ÜST BİLGİ BARI (Dinamik) === -->
    <!-- === ÜST BİLGİ BARI (Dinamik) === -->
<div class="top-bar text-white py-2">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <a href="tel:<?php echo htmlspecialchars($settings['contact_phone'] ?? '+905551234567'); ?>" class="text-white me-3"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($settings['contact_phone'] ?? '+90 555 123 45 67'); ?></a>
            <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@cuhfeturizm.com'); ?>" class="text-white"><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($settings['contact_email'] ?? 'info@cuhfeturizm.com'); ?></a>
        </div>
        <div>
            <a href="<?php echo htmlspecialchars($settings['social_facebook'] ?? '#'); ?>" target="_blank" class="text-white me-2"><i class="fab fa-facebook"></i></a>
            <a href="<?php echo htmlspecialchars($settings['social_instagram'] ?? '#'); ?>" target="_blank" class="text-white me-2"><i class="fab fa-instagram"></i></a>
            <a href="<?php echo htmlspecialchars($settings['social_youtube'] ?? '#'); ?>" target="_blank" class="text-white"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</div>

<!-- === NAVİGASYON MENÜSÜ === -->
<nav class="navbar navbar-expand-lg bg-light sticky-top shadow-sm main-nav">
    <div class="container">
        <!-- Mobil Logo ve Toggle Butonu -->
        <a class="navbar-brand d-lg-none mx-auto" href="index.php"><img src="images/logo.png" alt="Cuhfe Turizm Logo" style="height:50px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavMenu" aria-controls="mainNavMenu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

        <!-- Menü İçeriği -->
        <div class="collapse navbar-collapse" id="mainNavMenu">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); // Aktif menüyü belirlemek için mevcut sayfa adını alalım ?>
            <ul class="navbar-nav w-100 d-flex justify-content-between">
                <!-- Sol Menü -->
                <div class="d-lg-flex">
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Anasayfa</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'kurumsal.php') ? 'active' : ''; ?>" href="#">Kurumsal</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'hac_programlari.php') ? 'active' : ''; ?>" href="hac_programlari.php">Hac Programları</a></li>
                </div>
                
                <!-- Ortadaki Logo (Sadece Masaüstü) -->
                <a class="navbar-brand d-none d-lg-block" href="index.php"><img src="images/logo.png" alt="Cuhfe Turizm Logo" class="main-logo"></a>
                
                <!-- Sağ Menü -->
                <div class="d-lg-flex">
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'umre_programlari.php') ? 'active' : ''; ?>" href="#">Umre Programları</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'kudus_turlari.php') ? 'active' : ''; ?>" href="#">Kudüs Turları</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'iletisim.php') ? 'active' : ''; ?>" href="iletisim.php">İletişim</a></li>
                </div>
            </ul>
        </div>
    </div>
</nav>
    
    <!-- === SAYFA BAŞLIĞI BANNER'I (DÜZENLENDİ) === -->
     <header class="hero-section">
        <div class="hero-image" style="background-image: url('images/slider.webp');"></div>
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-4 fw-bold text-white">Maneviyata Açılan Kapınız</h1>
                    <p class="lead text-white mb-4">Cuhfe Turizm güvencesiyle unutulmaz bir Hac & Umre deneyimi yaşayın.
                    </p>
                </div>
                
            </div>
        </div>
    </header>
    <!-- === İLETİŞİM İÇERİK BÖLÜMÜ === -->
    <main class="contact-page-section py-5">
        <div class="container">
            <div class="row">
                <!-- Sol Taraf: Açıklama ve Harita (Dinamik Bilgiler) -->
                <div class="col-lg-6 mb-4 mb-lg-0 d-flex">
                    <div class="contact-left-panel w-100">
                        <h3 class="panel-title">Bizimle İrtibata Geçin</h3>
                        <p class="lead">Size yardımcı olmak için buradayız. Tur programlarımız, kayıt işlemleri veya aklınıza takılan herhangi bir soru için bize aşağıdaki bilgilerden ulaşabilir veya formu doldurabilirsiniz.</p>

                        <div class="row my-4 text-center">
                            <div class="col-md-4 contact-info-item">
                                <i class="fas fa-map-marked-alt fa-2x"></i>
                                <h6>Adres</h6>
                                <p><?php echo htmlspecialchars($settings['contact_address'] ?? 'Adres bilgisi girilmemiş.'); ?></p>
                            </div>
                            <div class="col-md-4 contact-info-item">
                                <i class="fas fa-phone-alt fa-2x"></i>
                                <h6>Arayın</h6>
                                <p><?php echo htmlspecialchars($settings['contact_phone'] ?? 'Telefon bilgisi girilmemiş.'); ?></p>
                            </div>
                            <div class="col-md-4 contact-info-item">
                                <i class="fas fa-envelope-open-text fa-2x"></i>
                                <h6>Email</h6>
                                <p><?php echo htmlspecialchars($settings['contact_email'] ?? 'Email bilgisi girilmemiş.'); ?></p>
                            </div>
                        </div>

                        <div class="map-container shadow mt-auto">
                            <div id="gmap_canvas"></div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Taraf: İletişim Formu -->
                <div class="col-lg-6 d-flex">
                    <div class="contact-form-panel p-4 p-md-5 shadow-lg w-100">
                        <h3 class="panel-title">Bize Mesaj Gönderin</h3>
                        <?php
                        if (isset($_SESSION['form_mesaj'])) {
                            $mesaj_tur = $_SESSION['form_mesaj']['tur'] == 'basari' ? 'success' : 'danger';
                            echo '<div class="alert alert-' . $mesaj_tur . ' alert-dismissible fade show" role="alert">' .
                                 $_SESSION['form_mesaj']['metin'] .
                                 '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' .
                                 '</div>';
                            unset($_SESSION['form_mesaj']);
                        }
                        ?>
                        <form action="mesaj_gonder.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label for="name" class="form-label">Adınız</label><input type="text" class="form-control" id="name" name="name" required></div>
                                <div class="col-md-6 mb-3"><label for="surname" class="form-label">Soyadınız</label><input type="text" class="form-control" id="surname" name="surname" required></div>
                            </div>
                            <div class="mb-3"><label for="email" class="form-label">Email Adresiniz</label><input type="email" class="form-control" id="email" name="email" required></div>
                            <div class="mb-3"><label for="phone" class="form-label">Telefon (Opsiyonel)</label><input type="tel" class="form-control" id="phone" name="phone"></div>
                            <div class="mb-3"><label for="message" class="form-label">Mesajınız</label><textarea class="form-control" id="message" name="message" rows="5" required></textarea></div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Mesajı Gönder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- === FOOTER (Dinamik ve Tam Tasarımlı) === -->
     <?php include('footer.php'); ?>

    <!-- === WHATSAPP BUTONU (Dinamik) === -->
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', ($settings['contact_whatsapp'] ?? '')); ?>?text=Merhaba, sitenizden ulaşıyorum." class="whatsapp-float" target="_blank"><i class="fab fa-whatsapp"></i></a>

    <!-- Gerekli Script Dosyaları -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>

</body>
</html>