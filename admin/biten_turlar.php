<?php
// admin/biten_turlar.php
$page_title = "Tamamlanan Turlar";
require_once 'includes/header.php';

// 'completed' statüsündeki turları çek
$stmt = $db->prepare("SELECT * FROM tours WHERE status = 'completed' ORDER BY return_date DESC");
$stmt->execute();
$completed_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Biten Tur Arşivi</h2>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tur Adı</th>
                        <th>Bitiş Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($completed_tours) > 0): ?>
                        <?php foreach ($completed_tours as $tour): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tour['title']); ?></td>
                                <td><?php echo date('d.m.Y', strtotime($tour['return_date'])); ?></td>
                                <td>
                                    <a href="tur_detay.php?tour_id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-secondary" title="Arşiv Raporunu Görüntüle"><i class="fas fa-archive"></i> Arşivi Görüntüle</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center py-4">Henüz tamamlanmış bir tur yok.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>