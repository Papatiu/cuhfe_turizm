<?php
require_once 'admin/includes/db.php';

// Ayarları ve sayfa içeriğini çekelim
try {
    $settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
    $settings = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);

    $page_slug = 'hakkimizda';
    $page_query = $db->prepare("SELECT * FROM pages WHERE page_slug = ?");
    $page_query->execute([$page_slug]);
    $page = $page_query->fetch(PDO::FETCH_ASSOC);

    if (!$page) {
        die("Hakkımızda sayfası içeriği bulunamadı.");
    }
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

// *** BLOG DETAY SAYFASINDAKİ AYNI SİHİRBAZLIK ***
$content = $page['content'];
$image_html = '';

if (!empty($page['image'])) {
    // Blog yazısındaki .post-image-card stilini burada da kullanıyoruz
    $image_html = '<div class="post-image-card my-4 shadow-sm">
                       <img src="images/pages/' . htmlspecialchars($page['image']) . '" 
                            alt="' . htmlspecialchars($page['title']) . '" 
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
    <title><?php echo htmlspecialchars($page['title']); ?> - <?php echo htmlspecialchars($settings['site_title'] ?? 'Cuhfe Turizm'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css"> <!-- Tüm stillerimiz burada -->
</head>
<body>

    <?php include 'header_menu.php'; ?>
    
    <header class="page-header" style="background-image: url('images/pages/<?php echo htmlspecialchars($page['image'] ?? 'slider4.jpeg'); ?>');">
        <div class="page-header-overlay"></div>
        <div class="container text-center">
            <h1 class="page-title-single"><?php echo htmlspecialchars($page['title']); ?></h1>
            <p class="page-subtitle text-white-50">Cuhfe Turizm'in Kuruluş Hikayesi ve Değerleri</p>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <article class="blog-content">
                        <?php echo $display_content; ?>
                    </article>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>

</body>
</html>