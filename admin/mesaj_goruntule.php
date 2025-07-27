<?php
// admin/mesaj_goruntule.php
$page_title = "Mesaj Detayı";
require_once 'includes/header.php';

// ID'nin gelip gelmediğini kontrol et
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Geçersiz mesaj ID'si.";
    header("Location: gelen_mesajlar.php");
    exit;
}

$message_id = $_GET['id'];

try {
    // Mesajı 'okundu' olarak işaretle (is_read = 1)
    $update_stmt = $db->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?");
    $update_stmt->execute([$message_id]);

    // Mesaj bilgilerini çek
    $stmt = $db->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->execute([$message_id]);
    $message = $stmt->fetch(PDO::FETCH_ASSOC);

    // Eğer mesaj bulunamazsa
    if (!$message) {
        $_SESSION['error_message'] = "Mesaj bulunamadı.";
        header("Location: gelen_mesajlar.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
    header("Location: gelen_mesajlar.php");
    exit;
}

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="m-0">Mesaj Detayı</h2>
    <a href="gelen_mesajlar.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Geri Dön</a>
</div>

<div class="card shadow">
    <div class="card-header bg-light">
        <div class="row">
            <div class="col-md-6">
                <strong>Gönderen:</strong> <?php echo htmlspecialchars($message['full_name']); ?>
            </div>
            <div class="col-md-6 text-md-end">
                <strong>Tarih:</strong> <?php echo date('d.m.Y H:i', strtotime($message['created_at'])); ?>
            </div>
        </div>
        <hr class="my-2">
         <div class="row">
            <div class="col-md-6">
                <strong>E-Posta:</strong> <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a>
            </div>
            <div class="col-md-6 text-md-end">
                <strong>Telefon:</strong> <?php echo htmlspecialchars($message['phone']); ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title mb-3">Mesaj İçeriği</h5>
        <p class="card-text bg-light p-3 rounded" style="white-space: pre-wrap;"><?php echo htmlspecialchars($message['message']); ?></p>
    </div>
    <div class="card-footer text-end">
         <a href="mesaj_sil.php?id=<?php echo $message['id']; ?>" class="btn btn-danger" onclick="return confirm('Bu mesajı kalıcı olarak silmek istediğinizden emin misiniz?');"><i class="fas fa-trash-alt me-2"></i> Sil</a>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>