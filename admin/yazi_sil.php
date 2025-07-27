<?php
// admin/yazi_sil.php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php"); exit;
}

$post_id = $_GET['id'];

try {
    $stmt_find = $db->prepare("SELECT featured_image FROM posts WHERE id = ?");
    $stmt_find->execute([$post_id]);
    $post = $stmt_find->fetch(PDO::FETCH_ASSOC);

    if($post) {
        $stmt_delete = $db->prepare("DELETE FROM posts WHERE id = ?");
        $stmt_delete->execute([$post_id]);
        
        $image_path = '../images/blog/' . $post['featured_image'];
        if ($post['featured_image'] && $post['featured_image'] != 'default_blog.jpg' && file_exists($image_path)) {
            unlink($image_path);
        }
        
        $_SESSION['success_message'] = "Yazı başarıyla silindi.";
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Silme işlemi sırasında bir hata oluştu: " . $e->getMessage();
}

header("Location: blog.php");
exit;
?>