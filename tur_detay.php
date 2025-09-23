<?php
require_once 'admin/includes/db.php';

// --- SAYFAYA ÖZEL SEO DEĞİŞKENLERİ ---
// Varsayılan değerler
$page_title = 'Tur Detayları - Cuhfe Turizm';
$page_description = 'Cuhfe Turizm turları hakkında detaylı bilgi, otel konaklamaları, fiyatlar ve kayıt fırsatları.';
$page_keywords = 'hac turu detay, umre turu detay, tur fiyatları, tur programı';
$page_image = 'images/social_media_banner.jpg'; // Paylaşımlarda görünecek varsayılan resim

// Slug parametresini URL'den al
$slug = $_GET['slug'] ?? null;

// Ayarları ve tur detayını çekelim
try {
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);

    if (!$slug) {
        // Slug yoksa tüm turların listesine yönlendir
        header("Location: hac_turlari.php"); // Veya genel bir tur listesi sayfasına
        exit;
    }

    $tour_query = $db->prepare("SELECT * FROM tours WHERE slug = ? AND is_active = 1");
    $tour_query->execute([$slug]);
    $tour = $tour_query->fetch(PDO::FETCH_ASSOC);

    if (!$tour) {
        // Tur bulunamazsa 404 göster
        http_response_code(404);
        echo "<h1>404 - Tur Bulunamadı</h1><p>Aradığınız tur mevcut değil veya yayından kaldırılmış olabilir.</p><a href='hac_turlari.php'>Turlara geri dön</a>";
        exit;
    }

    // Tur detaylarına göre SEO değişkenlerini güncelle
    $page_title = htmlspecialchars($tour['title']) . ' - Cuhfe Turizm';
    $page_description = mb_substr(strip_tags($tour['description']), 0, 160, 'UTF-8') . '...';
    $page_keywords = 'hac, umre, ' . htmlspecialchars($tour['title']) . ', ' . htmlspecialchars($tour['medina_hotel']) . ', ' . htmlspecialchars($tour['mecca_hotel']);
    $page_image = 'images/tours/' . htmlspecialchars($tour['image']);
} catch (PDOException $e) {
    error_log("Tur detayları çekilirken hata: " . $e->getMessage());
    die("Veritabanı hatası: Tur bilgileri çekilemedi.");
}

// Resim ve İçeriği Birleştirme Sihirbazlığı (Blog yazısındaki gibi)
$content = $tour['description']; // Açıklama sütununu content olarak kullanıyoruz.
$image_html = '';

if (!empty($tour['image'])) {
    // .post-image-card stilini burada da kullanabiliriz veya .tour-detail-image-card özel bir stil tanımlayabiliriz
    $image_html = '<div class="post-image-card my-4 shadow-sm">
                       <img src="' . htmlspecialchars($page_image) . '" 
                            alt="' . htmlspecialchars($tour['title']) . '" 
                            class="img-fluid">
                   </div>';
}

$first_p_end_pos = strpos($content, '</p>');
if ($first_p_end_pos !== false) {
    $part1 = substr($content, 0, $first_p_end_pos + 4);
    $part2 = substr($content, $first_p_end_pos + 4);
    $display_content = $part1 . $image_html . $part2;
} else {
    $display_content = $image_html . $content;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon Kodları -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#0a2e5c">
    <meta name="theme-color" content="#ffffff">

    <!-- Temel SEO Etiketleri -->
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="author" content="Cuhfe Turizm">
    <link rel="canonical" href="http://www.cuhfeturizm.com.tr<?php echo $_SERVER['REQUEST_URI']; ?>" />

    <!-- Sosyal Medya (Open Graph) Etiketleri -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.cuhfeturizm.com.tr<?php echo $_SERVER['REQUEST_URI']; ?>" />
    <meta property="og:image" content="http://www.cuhfeturizm.com.tr/<?php echo $page_image; ?>" />
    <meta property="og:site_name" content="Cuhfe Turizm" />
    <meta property="og:locale" content="tr_TR" />

    <!-- Twitter Card Etiketleri -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="twitter:image" content="http://www.cuhfeturizm.com.tr/<?php echo $page_image; ?>">

    <!-- Diğer Teknik Etiketler ve Kütüphaneler -->
    <meta name="robots" content="index, follow">

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

    <?php include 'header_menu.php'; ?>

    <header class="page-header" style="background-image: url('<?php echo htmlspecialchars($page_image); ?>');">
        <div class="page-header-overlay"></div>
        <div class="container text-center">
            <h1 class="page-title-single"><?php echo htmlspecialchars($tour['title']); ?></h1>
            <p class="page-subtitle"><?php echo date('d F Y', strtotime($tour['departure_date'])); ?> Tarihli Tur</p>
            <div class="tour-header-price mt-3">
                <?php if (!empty($tour['old_price']) && $tour['old_price'] > 0): ?>
                    <span class="old-price-lg"><?php echo number_format($tour['old_price'], 0); ?> <?php echo htmlspecialchars($tour['currency']); ?></span>
                <?php endif; ?>
                <span class="current-price-lg"><?php echo number_format($tour['price'], 0); ?> <?php echo htmlspecialchars($tour['currency']); ?></span> <small>kişi başı</small>
            </div>
            <a href="#reservationForm" class="btn btn-warning btn-lg mt-4">Şimdi Rezervasyon Yap</a>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            <div class="row">
                <!-- Sol Kolon: Tur Detay Bilgileri -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-0 tour-info-card">
                        <div class="card-body">
                            <h4 class="card-title text-center text-primary mb-4">Tur Bilgileri</h4>
                            <ul class="list-unstyled tour-details-list">
                                <li><i class="fas fa-calendar-alt me-2 text-secondary"></i> <strong>Gidiş Tarihi:</strong> <?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?></li>
                                <li><i class="fas fa-calendar-alt me-2 text-secondary"></i> <strong>Dönüş Tarihi:</strong> <?php echo date('d.m.Y', strtotime($tour['return_date'])); ?></li>
                                <li><i class="fas fa-moon me-2 text-secondary"></i> <strong>Medine Süresi:</strong> <?php echo htmlspecialchars($tour['duration_medina']); ?> Gece</li>
                                <li><i class="fas fa-moon me-2 text-secondary"></i> <strong>Mekke Süresi:</strong> <?php echo htmlspecialchars($tour['duration_mecca']); ?> Gece</li>
                                <li><i class="fas fa-clock me-2 text-secondary"></i> <strong>Toplam Süre:</strong> <?php echo htmlspecialchars($tour['duration_medina'] + $tour['duration_mecca']); ?> Gün</li>
                                <li><i class="fas fa-hotel me-2 text-secondary"></i> <strong>Medine Oteli:</strong> <?php echo htmlspecialchars($tour['medina_hotel']); ?></li>
                                <li><i class="fas fa-star me-2 text-secondary"></i> <strong>Mekke Oteli:</strong> <?php echo htmlspecialchars($tour['mecca_hotel']); ?></li>
                                <li><i class="fas fa-plane me-2 text-secondary"></i> <strong>Havayolu:</strong> <?php echo htmlspecialchars($tour['airline'] ?? 'Belirtilmedi'); ?></li>
                            </ul>
                            <div class="text-center mt-4">
                                <a href="#reservationForm" class="btn btn-primary btn-block">Hemen Rezervasyon Yap</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Kolon: Tur Açıklaması -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4 p-4 p-md-5">
                        <h3 class="mb-4 section-title-left text-primary">Tur Detayları ve Programı</h3>
                        <article class="blog-content">
                            <?php echo $display_content; ?>
                        </article>
                    </div>

                    <!-- Dahil Olan ve Olmayan Hizmetler -->
                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100 p-3">
                                <h5 class="card-title text-success"><i class="fas fa-check-circle me-2"></i> Fiyata Dahil Hizmetler</h5>
                                <div class="card-body px-0">
                                    <?php echo $tour['included_services']; // Burası CKEditor'dan gelirse HTML içerir 
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100 p-3">
                                <h5 class="card-title text-danger"><i class="fas fa-times-circle me-2"></i> Fiyata Dahil Olmayan Hizmetler</h5>
                                <div class="card-body px-0">
                                    <?php echo $tour['excluded_services']; // Burası da CKEditor'dan gelirse HTML içerir 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rezervasyon Formu veya Bilgi İsteme -->
                    <div id="reservationForm" class="card shadow-lg border-0 mt-5 p-4 p-md-5 bg-light-subtle">
                        <h3 class="mb-4 text-center section-title">Rezervasyon Talep Formu</h3>
                        <p class="text-center text-muted mb-4">Tur programı hakkında daha fazla bilgi almak veya rezervasyon talebinde bulunmak için aşağıdaki formu doldurunuz.</p>
                        <form action="mesaj_gonder.php" method="POST">
                            <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                            <input type="hidden" name="tour_title" value="<?php echo htmlspecialchars($tour['title']); ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Adınız Soyadınız" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control" placeholder="E-posta Adresiniz" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" name="phone" class="form-control" placeholder="Telefon Numaranız" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" name="num_people" class="form-control" placeholder="Katılımcı Sayısı" min="1" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="form-control" rows="4" placeholder="Eklemek istediğiniz notlar veya sorularınız"></textarea>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-warning btn-lg mt-3"><i class="fas fa-paper-plane me-2"></i> Talep Gönder</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>