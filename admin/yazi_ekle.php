<?php
// admin/yazi_ekle.php
$page_title = "Yeni Yazı Ekle";
require_once 'includes/header.php'; // Header'ı çağır

// Kategorileri veritabanından çek
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Yeni Blog Yazısı Oluştur</h2>

<div class="card shadow">
    <div class="card-body">
        <!-- Formu merkezi bir dosyaya göndereceğiz -->
        <form action="yazi_kaydet.php" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-9">
                    <div class="mb-3">
                        <label for="title" class="form-label">Yazı Başlığı</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Yazı İçeriği</label>
                        <textarea class="form-control" id="content" name="content" rows="10"></textarea>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Yayınlama Ayarları</h5>
                             <div class="mb-3">
                                <label for="status" class="form-label">Durum</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="published" selected>Yayınlandı</option>
                                    <option value="draft">Taslak</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select id="category_id" name="category_id" class="form-select">
                                    <option value="">Kategori Seçiniz...</option>
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                             <div class="mb-3">
                                 <label for="featured_image" class="form-label">Öne Çıkan Görsel</label>
                                 <input class="form-control" type="file" id="featured_image" name="featured_image">
                             </div>
                            <div class="d-grid">
                                 <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Kaydet</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>

<!-- Zengin Metin Editörü (CKEditor) Scripti -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>