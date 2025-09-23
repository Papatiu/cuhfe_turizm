<?php
// Gerekli dosyaları ve veritabanı bağlantısını dahil et
require_once 'admin/includes/db.php';

// ... (dosyanın üst kısmı aynı kalıyor) ...
$slug = $_GET['slug'] ?? null;
if (!$slug) { header("Location: index.php"); exit; }

// ... (Ayarlar ve veritabanı sorgusu aynı kalıyor) ...
try {
    $post_query = $db->prepare("SELECT title, content, featured_image, created_at FROM posts WHERE slug = ? AND status = 'published'");
    $post_query->execute([$slug]);
    $post = $post_query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) { /*...*/ $post = false; }
if (!$post) { /*...*/ exit; }


// *** GÜNCELLENEN SİHİRBAZLIK BÖLÜMÜ ***
$content = $post['content'];
$image_html = '';

if (!empty($post['featured_image']) && $post['featured_image'] !== 'default_blog.jpg') {
    // RESMİ SARAN DİV'İ VE CSS CLASS'LARINI GÜNCELLEDİK
    $image_html = '<div class="post-image-card my-4 shadow-sm">
                       <img src="images/blog/' . htmlspecialchars($post['featured_image']) . '" 
                            alt="' . htmlspecialchars($post['title']) . '" 
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
// *** SİHİRBAZLIK BÖLÜMÜ BİTTİ ***
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <!-- ... (head kısmı aynı) ... -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - <?php echo htmlspecialchars($settings['site_title'] ?? 'Cuhfe Turizm'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'header_menu.php'; ?>
    
    <header class="page-header" style="background-image: url('images/slider4.jpg');">
        <div class="page-header-overlay"></div>
        <div class="container text-center">
            <h1 class="page-title-single"><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="post-meta mt-2">
                <span><i class="fas fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($post['created_at'])); ?></span>
            </div>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <article class="blog-content">
                        <?php echo $display_content; ?>
                    </article>
                    <hr class="my-5">
                    <div class="text-center">
                        <a href="blog.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Diğer Yazılara Göz At</a>
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