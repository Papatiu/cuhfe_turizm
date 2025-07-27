<?php
// admin/gelen_mesajlar.php
$page_title = "Gelen Mesajlar";
require_once 'includes/header.php';

// Veritabanından tüm mesajları çek, en yeniden eskiye doğru sırala
$stmt = $db->query("SELECT * FROM contacts ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- BAŞARI/HATA MESAJLARI -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-header">
        <h5 class="m-0 fw-bold text-primary">İletişim Formu Mesajları</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="messagesTable">
                <thead class="table-dark">
                    <tr>
                        <th>Gönderen</th>
                        <th>E-Posta</th>
                        <th>Telefon</th>
                        <th>Gönderim Tarihi</th>
                        <th>Durum</th>
                        <th style="width: 120px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($messages) > 0): ?>
                        <?php foreach ($messages as $message): ?>
                            <tr class="<?php echo !$message['is_read'] ? 'table-warning fw-bold' : ''; ?>">
                                <td><?php echo htmlspecialchars($message['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td><?php echo htmlspecialchars($message['phone']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($message['created_at'])); ?></td>
                                <td>
                                    <?php if ($message['is_read']): ?>
                                        <span class="badge bg-success">Okundu</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Yeni Mesaj</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="mesaj_goruntule.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-info" title="Oku"><i class="fas fa-eye"></i></a>
                                    <a href="mesaj_sil.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-danger" title="Sil" onclick="return confirm('Bu mesajı kalıcı olarak silmek istediğinizden emin misiniz?');"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Gelen kutunuz boş.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>