<?php
// GEREKLİ DOSYALARI DAHİL ET
require_once 'admin/includes/db.php';

// === SAYFA İÇİN GEREKLİ VERİLERİ ÇEK ===

// Slug parametresini URL'den güvenli bir şekilde al
$slug = $_GET['slug'] ?? null;

// Slug yoksa, en mantıklı sayfa olan hac_programlari.php'ye yönlendir
if (!$slug) {
    header("Location: hac_programlari.php");
    exit;
}

try {
    // Genel site ayarlarını çek
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);

    // Slug'a göre ilgili ve aktif olan turu çek
    $tour_query = $db->prepare("SELECT * FROM tours WHERE slug = ? AND is_active = 1");
    $tour_query->execute([$slug]);
    $tour = $tour_query->fetch(PDO::FETCH_ASSOC);

    // Eğer veritabanında böyle bir tur yoksa, 404 hatası ver ve işlemi durdur
    if (!$tour) {
        http_response_code(404);
        // header.php'yi include etmeden basit bir hata sayfası gösteriyoruz
        echo "<!DOCTYPE html><html><head><title>404 Tur Bulunamadı</title></head><body><h1>404 - Tur Bulunamadı</h1><p>Aradığınız tur mevcut değil veya yayından kaldırılmış olabilir.</p><a href='hac_programlari.php'>Tüm Turlara Göz At</a></body></html>";
        exit;
    }
} catch (PDOException $e) {
    error_log("Tur detayları çekilirken hata: " . $e->getMessage());
    die("Veritabanı hatası nedeniyle sayfa yüklenemedi. Lütfen daha sonra tekrar deneyiniz.");
}

// === SAYFAYA ÖZEL SEO DEĞİŞKENLERİNİ HAZIRLA ===
$page_title = htmlspecialchars($tour['title']) . ' - Cuhfe Turizm';
// Tur açıklamasından ilk 160 karakteri alarak SEO için dinamik açıklama oluştur
$page_description = mb_substr(strip_tags($tour['description'] ?? ''), 0, 160, 'UTF-8') . '...';
$page_keywords = 'hac, umre, ' . htmlspecialchars($tour['title']) . ', ' . htmlspecialchars($tour['medina_hotel']) . ', ' . htmlspecialchars($tour['mecca_hotel']);
// Sosyal medyada paylaşım için tur resmini kullan
$page_image = 'images/tours/' . htmlspecialchars($tour['image']);


// === İÇERİK ve RESMİ BİRLEŞTİRME MANTIĞI (ORİJİNAL YAPI KORUNDU) ===
$content = $tour['description'] ?? ''; // Tur açıklaması
$image_html = '';

if (!empty($tour['image'])) {
    // Resim varsa, gösterilecek HTML kodunu hazırla.
    $image_html = '
    <div 
        class="post-image-card my-4 shadow-sm" 
        style="
            width: 700px; /* Dış çerçevenin sabit genişliği */
            max-width: 100%; /* Mobil cihazlarda taşmayı engellemek için */
            margin: 1.5rem auto; 
            padding: 10px;
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 15px;
        "
    >
        <img 
            src="' . htmlspecialchars($page_image) . '" 
            alt="' . htmlspecialchars($tour['title']) . '" 
            class="img-fluid"
            style="
                width: 100%;
                height: 400px; /* Resmin sabit yüksekliği */
                object-fit: cover; /* Görüntü oranını bozmadan alanı kapla */
                border-radius: 10px;
            "
        >
    </div>';
}

// Resim kodunu, içeriğin ilk paragrafından sonra ekle
$first_p_end_pos = strpos($content, '</p>');
if ($first_p_end_pos !== false) {
    $part1 = substr($content, 0, $first_p_end_pos + 4);
    $part2 = substr($content, $first_p_end_pos + 4);
    $display_content = $part1 . $image_html . $part2;
} else {
    // Eğer içerikte paragraf yoksa, resim en başta görünsün
    $display_content = $image_html . $content;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    <!-- Diğer tüm meta, favicon ve sosyal medya etiketleri... -->
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
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
                                <li><i class="fas fa-calendar-alt"></i> <strong>Gidiş Tarihi:</strong> <?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?></li>
                                <li><i class="fas fa-calendar-check"></i> <strong>Dönüş Tarihi:</strong> <?php echo date('d.m.Y', strtotime($tour['return_date'])); ?></li>
                                <li><i class="fas fa-moon"></i> <strong>Medine Süresi:</strong> <?php echo htmlspecialchars($tour['duration_medina']); ?> Gece</li>
                                <li><i class="fas fa-moon"></i> <strong>Mekke Süresi:</strong> <?php echo htmlspecialchars($tour['duration_mecca']); ?> Gece</li>
                                <li><i class="far fa-clock"></i> <strong>Toplam Süre:</strong> <?php echo htmlspecialchars($tour['duration_medina'] + $tour['duration_mecca']); ?> Gün</li>
                                <li><i class="fas fa-hotel"></i> <strong>Medine Oteli:</strong> <?php echo htmlspecialchars($tour['medina_hotel']); ?></li>
                                <li><i class="fas fa-star"></i> <strong>Mekke Oteli:</strong> <?php echo htmlspecialchars($tour['mecca_hotel']); ?></li>
                                <li><i class="fas fa-plane"></i> <strong>Havayolu:</strong> <?php echo htmlspecialchars($tour['airline'] ?? 'Belirtilmedi'); ?></li>
                            </ul>
                            <div class="text-center mt-4">
                                <a href="#reservationForm" class="btn btn-primary w-100">Hemen Rezervasyon Yap</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Kolon: Tur Açıklaması -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4 p-4 p-md-5">
                        <h3 class="mb-4 section-title-left text-primary">Tur Detayları ve Programı</h3>
                        <article class="blog-content">
                            <?php echo $display_content; // Resim ve açıklamanın birleşmiş hali 
                            ?>
                        </article>
                    </div>

                    <!-- Dahil Olan ve Olmayan Hizmetler (HATALARI GİDERİLMİŞ) -->
                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100 p-3">
                                <h5 class="card-title text-success"><i class="fas fa-check-circle me-2"></i> Fiyata Dahil Hizmetler</h5>
                                <div class="card-body px-0">
                                    <?php echo $tour['included_services'] ?? '<p>Bu tur için dahil olan hizmetler henüz belirtilmemiştir.</p>'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100 p-3">
                                <h5 class="card-title text-danger"><i class="fas fa-times-circle me-2"></i> Fiyata Dahil Olmayan Hizmetler</h5>
                                <div class="card-body px-0">
                                    <?php echo $tour['excluded_services'] ?? '<p>Bu tur için hariç olan hizmetler henüz belirtilmemiştir.</p>'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rezervasyon Formu -->
                    <div id="reservationForm" class="card shadow-lg border-0 mt-5 p-4 p-md-5 bg-light-subtle">
                        <!-- ... (form içeriği aynı kalıyor) ... -->
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