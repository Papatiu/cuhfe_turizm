<?php
// admin/yazi_kaydet.php
require_once 'includes/db.php';
require_once 'includes/functions.php'; // Slug oluşturma fonksiyonumuzu dahil ediyoruz

// Güvenlik kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al
    $post_id = $_POST['post_id'] ?? null;
    $title = trim($_POST['title']);
    $content = $_POST['content']; // CKEditor'dan geldiği için trim yapmıyoruz
    $category_id = $_POST['category_id'] ?: null;
    $status = $_POST['status'];
    $author_id = $_SESSION['admin_id'];

    // Slug oluştur (eğer başlık boşsa generateSlug fonksiyonu rastgele bir şey üretecek)
    $slug = generateSlug($title);
    
    // Veritabanında bu slug'dan var mı diye kontrol et (düzenleme hariç)
    $check_sql = "SELECT id FROM posts WHERE slug = ? AND id != ?";
    $stmt_check = $db->prepare($check_sql);
    $stmt_check->execute([$slug, $post_id ?? 0]);
    if ($stmt_check->rowCount() > 0) {
        // Eğer varsa, sonuna rastgele bir sayı ekle
        $slug = $slug . '-' . time();
    }


    $image_name = $_POST['current_image'] ?? 'default_blog.jpg';

    // Resim Yükleme İşlemi
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $upload_dir = '../images/blog/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $file_info = pathinfo($_FILES['featured_image']['name']);
        $file_ext = strtolower($file_info['extension']);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            $image_name = uniqid('post_', true) . '.' . $file_ext;
            if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_dir . $image_name)) {
                $_SESSION['error_message'] = "Resim yüklenirken bir hata oluştu.";
                header("Location: blog.php"); exit;
            }
        } else {
            $_SESSION['error_message'] = "Geçersiz resim formatı.";
            header("Location: blog.php"); exit;
        }
    }

    try {
        if ($post_id) {
            // GÜNCELLEME
            $sql = "UPDATE posts SET title=?, slug=?, content=?, featured_image=?, category_id=?, status=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$title, $slug, $content, $image_name, $category_id, $status, $post_id]);
            $_SESSION['success_message'] = "Yazı başarıyla güncellendi.";
        } else {
            // YENİ KAYIT
            $sql = "INSERT INTO posts (title, slug, content, featured_image, category_id, author_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$title, $slug, $content, $image_name, $category_id, $author_id, $status]);
            $_SESSION['success_message'] = "Yeni yazı başarıyla eklendi.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
    }
    
    header("Location: blog.php");
    exit;
}
?>