<?php
// admin/blog.php
$page_title = "Blog Yönetimi";
require_once 'includes/header.php';

// Yazıları, kategori ve yazar bilgileriyle birlikte çek (JOIN kullanarak)
$stmt = $db->query("
    SELECT posts.*, categories.name as category_name, admins.full_name as author_name 
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.id 
    LEFT JOIN admins ON posts.author_id = admins.id 
    ORDER BY posts.created_at DESC
");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="m-0">Blog Yazıları</h2>
    <a href="yazi_ekle.php" class="btn btn-success"><i class="fas fa-plus me-2"></i> Yeni Yazı Ekle</a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Başlık</th>
                        <th>Kategori</th>
                        <th>Yazar</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($posts) > 0): ?>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['category_name'] ?? 'Kategorisiz'); ?></td>
                                <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                                <td><?php echo date('d.m.Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo ($post['status'] == 'published') ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="yazi_duzenle.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                                    <a href="yazi_sil.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" title="Sil" onclick="return confirm('Bu yazıyı silmek istediğinizden emin misiniz?');"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Henüz blog yazısı eklenmemiş.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>