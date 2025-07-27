<?php
// admin/musteriler.php
$page_title = "Müşteri Yönetimi";
require_once 'includes/header.php';

// Veritabanından tüm müşterileri çek
$stmt = $db->query("SELECT * FROM customers ORDER BY created_at DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="m-0">Müşteriler</h2>
    <a href="musteri_ekle.php" class="btn btn-success"><i class="fas fa-user-plus me-2"></i> Yeni Müşteri Ekle</a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="customersTable">
                <thead class="table-dark">
                    <tr>
                        <th>TC Kimlik No</th>
                        <th>Adı Soyadı</th>
                        <th>Telefon</th>
                        <th>E-Posta</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['tc_no']); ?></td>
                                <td><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td><?php echo date('d.m.Y', strtotime($customer['created_at'])); ?></td>
                                <td>
                                    <a href="musteri_duzenle.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm btn-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                                    <a href="musteri_sil.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm btn-danger" title="Sil" onclick="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?');"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Kayıtlı müşteri bulunmamaktadır.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>