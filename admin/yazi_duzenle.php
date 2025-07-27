<?php
// admin/yazi_duzenle.php
require_once 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: blog.php"); exit;
}

$post_id = $_GET['id'];

// Düzenlenecek yazıyı çek
$stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: blog.php"); exit;
}

// Kategorileri çek
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Düzenle: " . htmlspecialchars($post['title']);
?>

<h2 class="mb-4">Yazıyı Düzenle</h2>

<div class="card shadow">
    <div class="card-body">
        <form action="yazi_kaydet.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($post['featured_image']); ?>">
            
            <div class="row g-3">
                <div class="col-md-9">
                    <div class="mb-3">
                        <label for="title" class="form-label">Yazı Başlığı</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Yazı İçeriği</label>
                        <textarea class="form-control" id="content" name="content" rows="10"><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                             <h5 class="card-title mb-3">Yayınlama Ayarları</h5>
                             <div class="mb-3">
                                <label for="status" class="form-label">Durum</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="published" <?php echo ($post['status'] == 'published') ? 'selected' : ''; ?>>Yayınlandı</option>
                                    <option value="draft" <?php echo ($post['status'] == 'draft') ? 'selected' : ''; ?>>Taslak</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select id="category_id" name="category_id" class="form-select">
                                    <option value="">Kategori Seçiniz...</option>
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo ($post['category_id'] == $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                 <label for="featured_image" class="form-label">Öne Çıkan Görsel</label>
                                 <input class="form-control" type="file" id="featured_image" name="featured_image">
                                 <small class="form-text text-muted">Mevcut: <?php echo htmlspecialchars($post['featured_image']); ?></small>
                             </div>
                             <div class="d-grid">
                                 <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Güncelle</button>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>