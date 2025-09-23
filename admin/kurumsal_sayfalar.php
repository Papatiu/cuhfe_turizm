<?php
$page_title = "Kurumsal Sayfa Yönetimi";
require_once 'includes/header.php'; // Admin panelinin header'ı

// Veritabanından iki sayfayı da çekelim
try {
    // *** DEĞİŞİKLİK BURADA: Sorguyu ve veri çekme yöntemini güncelledik ***
    
    // SQL sorgumuz hala aynı kalabilir, çünkü tüm verilere ihtiyacımız var.
    $stmt = $db->query("SELECT * FROM pages WHERE page_slug IN ('hakkimizda', 'iptal_iade')");
    $all_pages = $stmt->fetchAll(PDO::FETCH_ASSOC); // Tüm veriyi bir diziye alıyoruz.

    // Gelen veriyi, slug'ları anahtar olacak şekilde manuel olarak yeni bir diziye atayalım.
    $pages_data = [];
    foreach($all_pages as $page) {
        $pages_data[$page['page_slug']] = $page;
    }
    
    // Artık verileri güvenle değişkenlere atayabiliriz.
    $hakkimizda = $pages_data['hakkimizda'] ?? ['id' => 0, 'title' => '', 'content' => '', 'image' => ''];
    $iptal_iade = $pages_data['iptal_iade'] ?? ['id' => 0, 'title' => '', 'content' => '', 'image' => ''];

} catch (PDOException $e) {
    // Hata durumunda boş veri ile devam etmesi için varsayılan değerler atıyoruz.
    $hakkimizda = ['id' => 0, 'title' => 'Hata', 'content' => 'Veri çekilemedi.', 'image' => ''];
    $iptal_iade = ['id' => 0, 'title' => 'Hata', 'content' => 'Veri çekilemedi.', 'image' => ''];
    // Hata mesajını ekrana basmak yerine loglayabiliriz veya bir uyarı gösterebiliriz.
    echo '<div class="alert alert-danger">Veritabanı hatası: Sayfa verileri çekilemedi.</div>';
    error_log("Kurumsal sayfalar çekilirken hata: " . $e->getMessage());
}
?>

<!-- Başarı/Hata Mesajları -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>


<form action="kurumsal_sayfa_kaydet.php" method="POST" enctype="multipart/form-data">
    <div class="card shadow">
        <div class="card-header">
            <!-- Sekmeli yapı (Tabs) -->
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="hakkimizda-tab" data-bs-toggle="tab" data-bs-target="#hakkimizda-panel" type="button" role="tab">Hakkımızda</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="iptal-tab" data-bs-toggle="tab" data-bs-target="#iptal-panel" type="button" role="tab">İptal ve İade Koşulları</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <!-- Sekme İçerikleri -->
            <div class="tab-content" id="myTabContent">

                <!-- Hakkımızda Sekmesi -->
                <div class="tab-pane fade show active" id="hakkimizda-panel" role="tabpanel">
                    <input type="hidden" name="hakkimizda_id" value="<?php echo $hakkimizda['id']; ?>">
                    <div class="mb-3">
                        <label for="hakkimizda_title" class="form-label">Sayfa Başlığı</label>
                        <input type="text" name="hakkimizda_title" id="hakkimizda_title" class="form-control" value="<?php echo htmlspecialchars($hakkimizda['title']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="hakkimizda_content" class="form-label">Sayfa İçeriği</label>
                        <textarea name="hakkimizda_content" id="hakkimizda_content" class="form-control ckeditor"><?php echo htmlspecialchars($hakkimizda['content']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="hakkimizda_image" class="form-label">Öne Çıkan Görsel (Değiştirmek için seçin)</label>
                            <input type="file" name="hakkimizda_image" id="hakkimizda_image" class="form-control">
                            <input type="hidden" name="hakkimizda_current_image" value="<?php echo $hakkimizda['image']; ?>">
                        </div>
                        <div class="col-md-4">
                            <p>Mevcut Görsel:</p>
                            <img src="../images/pages/<?php echo htmlspecialchars($hakkimizda['image'] ?? 'placeholder.png'); ?>" alt="Hakkımızda Görseli" class="img-thumbnail" width="200">
                        </div>
                    </div>
                </div>

                <!-- İptal ve İade Sekmesi -->
                <div class="tab-pane fade" id="iptal-panel" role="tabpanel">
                     <input type="hidden" name="iptal_iade_id" value="<?php echo $iptal_iade['id']; ?>">
                    <div class="mb-3">
                        <label for="iptal_iade_title" class="form-label">Sayfa Başlığı</label>
                        <input type="text" name="iptal_iade_title" id="iptal_iade_title" class="form-control" value="<?php echo htmlspecialchars($iptal_iade['title']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="iptal_iade_content" class="form-label">Sayfa İçeriği</label>
                        <textarea name="iptal_iade_content" id="iptal_iade_content" class="form-control ckeditor"><?php echo htmlspecialchars($iptal_iade['content']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Tüm Değişiklikleri Kaydet</button>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; // Admin panelinin footer'ı ?>