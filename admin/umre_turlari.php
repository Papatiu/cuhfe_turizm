<?php
// admin/umre_turlari.php
$page_title = "Umre Turları Yönetimi";
require_once 'includes/header.php';

// Veritabanından sadece 'umre' tipindeki turları çek
$stmt = $db->prepare("SELECT * FROM tours WHERE tour_type = 'umre' ORDER BY departure_date DESC");
$stmt->execute();
$umre_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- BAŞARI/HATA MESAJLARI -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="m-0">Umre Turları</h2>
    <!-- 'type=umre' parametresini gönderiyoruz -->
    <a href="tur_ekle.php?type=umre" class="btn btn-success"><i class="fas fa-plus me-2"></i> Yeni Umre Turu Ekle</a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="toursTable">
                <thead class="table-dark">
                    <tr>
                        <th>Tur Başlığı</th>
                        <th>Otel (Mekke/Medine)</th>
                        <th>Gidiş Tarihi</th>
                        <th>Fiyat</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($umre_tours) > 0): ?>
                        <?php foreach ($umre_tours as $tour): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tour['title']); ?></td>
                                <td><?php echo htmlspecialchars($tour['mecca_hotel']) . ' / ' . htmlspecialchars($tour['medina_hotel']); ?></td>
                                <td><?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?></td>
                                <td><?php echo htmlspecialchars($tour['price']) . ' ' . htmlspecialchars($tour['currency']); ?></td>
                                <td>
                                    <?php if ($tour['is_active']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="tur_duzenle.php?id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                                    <a href="tur_sil.php?id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-danger" title="Sil" onclick="return confirm('Bu turu silmek istediğinizden emin misiniz?');"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Henüz eklenmiş Umre turu bulunmamaktadır.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>