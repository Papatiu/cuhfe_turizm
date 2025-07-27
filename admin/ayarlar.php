<?php
// admin/ayarlar.php

// !!! EN ÖNEMLİ DEĞİŞİKLİK BURADA !!!
// Bütün PHP mantığını, session'ı ve veritabanı bağlantısını, herhangi bir HTML'den ÖNCE çağırıyoruz.
require_once 'includes/db.php'; 

// --- AYARLARI GÜNCELLEME İŞLEMİ (Eğer form gönderildiyse) ---
// Bu blok, sayfanın en üstüne taşındı.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $allowed_settings = [
        'contact_phone', 'contact_whatsapp', 'contact_email', 'contact_address',
        'social_facebook', 'social_instagram', 'social_youtube',
        'footer_text', 'site_title', 'site_keywords'
    ];

    try {
        $db->beginTransaction();

        foreach ($_POST as $key => $value) {
            if (in_array($key, $allowed_settings)) {
                $sql = "UPDATE settings SET setting_value = :setting_value WHERE setting_name = :setting_name";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':setting_value' => trim($value),
                    ':setting_name' => $key
                ]);
            }
        }

        $db->commit();
        $_SESSION['success_message'] = "Ayarlar başarıyla güncellendi!";

    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['error_message'] = "Bir hata oluştu: " . $e->getMessage();
    }
    
    // Yönlendirme komutu artık sorunsuz çalışacak çünkü henüz hiçbir HTML basılmadı.
    header("Location: ayarlar.php");
    exit;
}


// --- SAYFA GÖRÜNÜMÜNÜ HAZIRLAMA KODLARI ---
$page_title = "Genel Ayarlar"; 
// HTML'i basmaya başlayacak olan header'ı, tüm PHP mantığı bittikten SONRA çağırıyoruz.
require_once 'includes/header.php'; 

// --- AYARLARI VERİTABANINDAN ÇEKME (Sayfa yüklendiğinde) ---
$settings_query = $db->query("SELECT setting_name, setting_value FROM settings");
$settings_list = $settings_query->fetchAll(PDO::FETCH_KEY_PAIR);

?>

<!-- BAŞARI/HATA MESAJLARINI GÖSTERME -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<form action="ayarlar.php" method="POST">
    <div class="row g-4">
        <!-- İletişim Bilgileri Kartı -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-phone-alt me-2"></i> İletişim Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Telefon Numarası</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($settings_list['contact_phone'] ?? ''); ?>">
                    </div>
                    <!-- Diğer form alanları aynı şekilde kalacak -->
                    <div class="mb-3">
                        <label for="contact_whatsapp" class="form-label">WhatsApp Numarası</label>
                        <input type="text" class="form-control" id="contact_whatsapp" name="contact_whatsapp" value="<?php echo htmlspecialchars($settings_list['contact_whatsapp'] ?? ''); ?>">
                    </div>
                     <div class="mb-3">
                        <label for="contact_email" class="form-label">E-Posta Adresi</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings_list['contact_email'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="contact_address" class="form-label">Adres</label>
                        <textarea class="form-control" id="contact_address" name="contact_address" rows="3"><?php echo htmlspecialchars($settings_list['contact_address'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sosyal Medya ve SEO Kartı -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                 <div class="card-header">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-share-alt me-2"></i> Sosyal Medya & Site</h5>
                </div>
                <div class="card-body">
                    <!-- Diğer tüm form alanları aynı kalacak -->
                    <div class="mb-3">
                        <label for="social_facebook" class="form-label">Facebook URL</label>
                        <input type="url" class="form-control" id="social_facebook" name="social_facebook" value="<?php echo htmlspecialchars($settings_list['social_facebook'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="social_instagram" class="form-label">Instagram URL</label>
                        <input type="url" class="form-control" id="social_instagram" name="social_instagram" value="<?php echo htmlspecialchars($settings_list['social_instagram'] ?? ''); ?>">
                    </div>
                     <div class="mb-3">
                        <label for="social_youtube" class="form-label">YouTube URL</label>
                        <input type="url" class="form-control" id="social_youtube" name="social_youtube" value="<?php echo htmlspecialchars($settings_list['social_youtube'] ?? ''); ?>">
                    </div>
                    <hr>
                     <div class="mb-3">
                        <label for="site_title" class="form-label">Site Başlığı (Title)</label>
                        <input type="text" class="form-control" id="site_title" name="site_title" value="<?php echo htmlspecialchars($settings_list['site_title'] ?? ''); ?>">
                    </div>
                     <div class="mb-3">
                        <label for="site_keywords" class="form-label">Site Anahtar Kelimeleri (Keywords)</label>
                        <input type="text" class="form-control" id="site_keywords" name="site_keywords" value="<?php echo htmlspecialchars($settings_list['site_keywords'] ?? ''); ?>">
                    </div>
                     <div class="mb-3">
                        <label for="footer_text" class="form-label">Footer Yazısı</label>
                        <textarea class="form-control" id="footer_text" name="footer_text" rows="2"><?php echo htmlspecialchars($settings_list['footer_text'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button type="submit" class="btn btn-lg btn-success"><i class="fas fa-save me-2"></i> Ayarları Kaydet</button>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>