<?php
// admin/aktif_turlar.php
$page_title = "Aktif & Yaklaşan Turlar";
require_once 'includes/header.php';

// Otomatik olarak tarihi geçen 'upcoming' turları 'active' yap.
// Ve tarihi geçen 'active' turları 'completed' yap.
// Artık hem yaklaşan hem de devam eden turları listeliyoruz.
$stmt = $db->prepare("SELECT * FROM tours WHERE status = 'upcoming' OR status = 'active' ORDER BY departure_date ASC");

$stmt = $db->prepare("SELECT * FROM tours WHERE status != 'completed' ORDER BY departure_date ASC");
$stmt->execute();
$active_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Aktif ve Yaklaşan Turlar</h2>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tur Adı</th>
                        <th>Gidiş Tarihi</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                // admin/aktif_turlar.php dosyasındaki <tbody>...</tbody> arasını değiştir

                <tbody>
                    <?php if (count($active_tours) > 0): ?>
                        <?php foreach ($active_tours as $tour): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tour['title']); ?></td>
                                <td><?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?></td>
                                <td>
                                    <?php
                                    $status_class = '';
                                    $status_text = '';
                                    if ($tour['status'] == 'active') {
                                        $status_class = 'bg-success';
                                        $status_text = 'Devam Ediyor';
                                    } else { // upcoming
                                        $status_class = 'bg-info';
                                        $status_text = 'Yaklaşıyor';
                                    }
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="tur_detay.php?tour_id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-warning"
                                        title="Kafile Raporu"><i class="fas fa-users"></i> Kafile Listesi</a>
                                    <a href="tur_duzenle.php?id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-primary"
                                        title="Tur Bilgilerini Düzenle"><i class="fas fa-edit"></i> Düzenle</a>
                                    <!-- Turu Bitirme Butonu -->
                                    <a href="tur_bitir.php?tour_id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-danger"
                                        title="Turu Tamamlandı Olarak İşaretle"
                                        onclick="return confirm('Bu turu bitirip arşive taşımak istediğinizden emin misiniz? Bu işlem geri alınamaz.');">
                                        <i class="fas fa-check-circle"></i> Turu Bitir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">Aktif veya yaklaşan tur bulunmamaktadır.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>