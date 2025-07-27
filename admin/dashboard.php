<?php
// admin/dashboard.php

$page_title = "Dashboard"; // Header'da gösterilecek sayfa başlığı
require_once 'includes/header.php'; // Header'ı çağır (Bu zaten session ve db bağlantısını içeriyor)

// --- DASHBOARD VERİLERİNİ ÇEKME ---

// TODO: Toplam tur sayısını 'tours' tablosundan dinamik olarak çek. Şimdilik sabit.
$total_tours = 12; 

// TODO: Toplam müşteri sayısını 'customers' tablosundan dinamik olarak çek.
$total_customers = 75;

// Okunmamış mesaj sayısını al
$unread_messages_count = $db->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();

// Son 5 mesajı veritabanından çek
$stmt = $db->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
$latest_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Özet Kartları -->
<div class="row g-3 my-4">
    <div class="col-md-3">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded summary-card">
            <div>
                <h3 class="fs-2"><?php echo $total_tours; ?></h3>
                <p class="fs-5 text-primary">Toplam Tur</p>
            </div>
            <i class="fas fa-plane-departure fs-1 text-gray-300"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded summary-card" style="border-left-color: #1cc88a;">
            <div>
                <h3 class="fs-2"><?php echo $total_customers; ?></h3>
                <p class="fs-5" style="color: #1cc88a;">Müşteriler</p>
            </div>
            <i class="fas fa-users fs-1 text-gray-300"></i>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded summary-card" style="border-left-color: #f6c23e;">
            <div>
                <h3 class="fs-2"><?php echo $unread_messages_count; ?></h3>
                <p class="fs-5" style="color: #f6c23e;">Yeni Mesaj</p>
            </div>
            <i class="fas fa-envelope fs-1 text-gray-300"></i>
        </div>
    </div>
    
    <!-- Dördüncü kart buraya eklenebilir -->
</div>

<!-- Son Gelen Mesajlar Tablosu -->
<div class="card shadow mt-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Son Gelen Mesajlar</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($latest_messages) > 0): ?>
                        <?php foreach ($latest_messages as $message): ?>
                            <tr class="<?php echo !$message['is_read'] ? 'fw-bold' : ''; ?>">
                                <td><?php echo htmlspecialchars($message['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td><?php echo htmlspecialchars($message['phone']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($message['created_at'])); ?></td>
                                <td>
                                    <?php if ($message['is_read']): ?>
                                        <span class="badge bg-success">Okundu</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Okunmadı</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="view_message.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-info" title="Görüntüle"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Henüz gelen mesaj yok.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; // Footer'ı çağır ?>