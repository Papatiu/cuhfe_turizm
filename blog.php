<?php
// Gerekli dosyaları ve veritabanı bağlantısını dahil et
require_once 'admin/includes/db.php';

// Ayarları veritabanından çek (header ve footer'da kullanılacak)
try {
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    error_log("Ayarlar çekilirken veritabanı hatası: " . $e->getMessage());
    $settings = [];
}

// *** SADECE POSTS TABLOSUNDAN VERİ ÇEKEN BASİTLEŞTİRİLMİŞ SORGUSU ***
try {
    // Sadece posts tablosundan, durumu 'published' olanları çekiyoruz.
    $posts_query = $db->query("
        SELECT 
            title, 
            slug, 
            content, 
            featured_image, 
            created_at
        FROM posts
        WHERE status = 'published' 
        ORDER BY created_at DESC
    ");
    $posts = $posts_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Ekrana hata basmak yerine log dosyasına yazmak daha güvenlidir.
    error_log("Blog yazıları çekilirken hata: " . $e->getMessage());
    // Bir hata olursa, kullanıcıya boş bir sayfa göstermek yerine boş bir dizi ile devam et.
    $posts = [];
}

// Metin kısaltma fonksiyonu
function kisalt($metin, $uzunluk = 120) {
    // HTML tag'larını temizle
    $metin = strip_tags($metin);
    if (mb_strlen($metin, 'UTF-8') > $uzunluk) {
        $metin = mb_substr($metin, 0, $uzunluk, 'UTF-8');
        // Kelimeyi yarım kesmemek için son boşluğu bul
        $son_bosluk = mb_strrpos($metin, ' ', 0, 'UTF-8');
        if ($son_bosluk) {
             $metin = mb_substr($metin, 0, $son_bosluk, 'UTF-8');
        }
        return $metin . '...';
    }
    return $metin;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - <?php echo htmlspecialchars($settings['site_title'] ?? 'Cuhfe Turizm'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <?php include 'header_menu.php'; ?>
    
    <header class="page-header" style="background-image: url('images/slider4.jpg');">
        <div class="page-header-overlay"></div>
        <div class="container">
            <h1 class="page-title">Blog</h1>
            <p class="page-subtitle">Hac, Umre ve Kutsal Topraklara Dair Bilgiler ve Tavsiyeler</p>
        </div>
    </header>

    <section class="blog-list-section py-5">
        <div class="container">
            <div class="row">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                           <div class="card blog-card h-100 shadow-sm w-100">
                                <a href="yazi.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                    <img src="images/blog/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($post['title']); ?>"
                                         onerror="this.src='images/blog/default_blog.jpg';">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2 text-muted small">
                                        <i class="fas fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($post['created_at'])); ?>
                                        <!-- Kategori ve Yazar bilgisi artık çekilmediği için kaldırıldı -->
                                    </div>
                                    <h5 class="card-title">
                                        <a href="yazi.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text flex-grow-1">
                                        <?php echo kisalt($post['content']); // Kısaltma fonksiyonuna gönderiyoruz ?>
                                    </p>
                                    <a href="yazi.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="btn btn-outline-primary mt-auto align-self-start">Devamını Oku <i class="fas fa-arrow-right ms-1"></i></a>
                                </div>
                           </div>
                       </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">Henüz yayınlanmış bir blog yazısı bulunmamaktadır.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>