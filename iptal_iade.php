<?php
require_once 'admin/includes/db.php';

// Ayarları ve sayfa içeriğini çekelim
try {
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);

    $page_slug = 'iptal_iade';
    $page_query = $db->prepare("SELECT * FROM pages WHERE page_slug = ?");
    $page_query->execute([$page_slug]);
    $page = $page_query->fetch(PDO::FETCH_ASSOC);

    if (!$page) {
        die("İptal/İade koşulları sayfası içeriği bulunamadı.");
    }
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['title']); ?> - <?php echo htmlspecialchars($settings['site_title'] ?? 'Cuhfe Turizm'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'header_menu.php'; ?>
    
    <header class="page-header" style="background-image: url('images/slider.webp');"> <!-- Farklı bir banner resmi -->
        <div class="page-header-overlay"></div>
        <div class="container text-center">
            <h1 class="page-title-single"><?php echo htmlspecialchars($page['title']); ?></h1>
             <p class="page-subtitle text-white-50">Seyahat Sözleşmesi ve Yasal Bilgiler</p>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9"> <!-- Metin daha fazla olabileceği için alanı biraz genişlettim (col-lg-9) -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-md-5">
                             <article class="blog-content">
                                <?php echo $page['content']; ?>
                            </article>
                        </div>
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