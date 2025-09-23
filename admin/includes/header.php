<?php
// Mevcut sayfanın adını al (örn: dashboard.php)
$current_page = basename($_SERVER['PHP_SELF']);

// admin/includes/header.php (GÜNCELLENMİŞ HALİ)

// Veritabanı ve session kontrolü
require_once 'db.php';

// Güvenlik kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Okunmamış bildirim sayısını al
$unread_notifications_count = $db->query("SELECT COUNT(*) FROM notifications WHERE is_seen = 0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Cuhfe Turizm' : 'Admin Paneli - Cuhfe Turizm'; ?></title>
    <!-- CSS Linkleri -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="admin-body">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar (Sol Menü) -->
        <div class="bg-dark" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-3 text-light fs-4 fw-bold">
                <a href="dashboard.php" class="text-white text-decoration-none">
                    <img src="../images/logo.png" height="40" class="me-2" style="background-color: white; border-radius: 50%; padding: 4px;"> Cuhfe Panel
                </a>
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a href="hac_turlari.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo in_array($current_page, ['hac_turlari.php', 'tur_ekle.php', 'tur_duzenle.php']) && ($_GET['type'] ?? '') == 'hac' ? 'active' : ''; ?>"><i class="fas fa-kaaba me-2"></i> Hac Turları</a>
                <a href="umre_turlari.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo in_array($current_page, ['umre_turlari.php']) || (in_array($current_page, ['tur_ekle.php', 'tur_duzenle.php']) && ($_GET['type'] ?? '') == 'umre') ? 'active' : ''; ?>"><i class="fas fa-moon me-2"></i> Umre Turları</a>
                <a href="musteriler.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo in_array($current_page, ['musteriler.php', 'musteri_ekle.php', 'musteri_duzenle.php']) ? 'active' : ''; ?>"><i class="fas fa-users me-2"></i> Müşteriler</a>
                <a href="gelen_mesajlar.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo in_array($current_page, ['gelen_mesajlar.php', 'mesaj_goruntule.php']) ? 'active' : ''; ?>"><i class="fas fa-envelope-open-text me-2"></i> Gelen Mesajlar</a>
                <a href="blog.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo in_array($current_page, ['blog.php', 'yazi_ekle.php', 'yazi_duzenle.php']) ? 'active' : ''; ?>"><i class="fas fa-blog me-2"></i> Blog/Duyurular</a>                
                <a href="kurumsal_sayfalar.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo ($current_page_base == 'kurumsal_sayfalar.php') ? 'active' : ''; ?>">
                  <i class="fas fa-building me-2"></i> Kurumsal Sayfalar
                </a>
                <a href="ayarlar.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo ($current_page == 'ayarlar.php') ? 'active' : ''; ?>"><i class="fas fa-cog me-2"></i> Ayarlar</a>                                          
                <!-- === YENİ TUR İŞLEMLERİ BÖLÜMÜ === -->
                <div class="list-group-item list-group-item-action bg-transparent text-secondary fw-bold" style="cursor: default; background-color: #1a1d20 !important;">
                    <small>TUR İŞLEMLERİ</small>
                </div>
               <!-- ... (Diğer menü linkleri) ... -->
<a href="tur_musteri_yonetimi.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo ($current_page == 'tur_musteri_yonetimi.php') ? 'active' : ''; ?>"><i class="fas fa-user-tag me-2"></i> Tur Müşteri Yönetimi</a>
<a href="aktif_turlar.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo in_array($current_page, ['aktif_turlar.php', 'tur_detay.php']) ? 'active' : ''; ?>"><i class="fas fa-walking me-2"></i> Aktif Turlar</a>
<!-- Biten turlar sayfası bir sonraki adımda yapılacak -->
<a href="biten_turlar.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo ($current_page == 'biten_turlar.php') ? 'active' : ''; ?>"><i class="fas fa-history me-2"></i> Biten Turlar</a>
            </div>
            <div class="list-group-item list-group-item-action bg-transparent text-secondary fw-bold" style="cursor: default; background-color: #1a1d20 !important;">
                    <small>GALERİ İŞLEMLERİ</small>
                </div>
             <a href="galeri_yonetimi.php" class="list-group-item list-group-item-action bg-transparent text-white <?php echo ($current_page == 'galeri_yonetimi.php') ? 'active' : ''; ?>"><i class="fas fa-user-tag me-2"></i> Galeri Yönetimi</a>
        </div>

        <!-- Sayfa İçeriği Wrapper -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light py-3 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bars fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- Bildirimler Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fs-5"></i>
                                <?php if($unread_notifications_count > 0): ?>
                                    <span class="badge rounded-pill bg-danger position-absolute top-0 start-75 translate-middle" style="font-size: 0.6em; padding: .35em .5em;">
                                        <?php echo $unread_notifications_count; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-2">
                                <li><a class="dropdown-item" href="#">Yeni bir mesaj geldi!</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Tüm bildirimleri gör</a></li>
                            </ul>
                        </li>
                        <!-- Profil Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fs-5 me-1"></i> <?php echo htmlspecialchars($_SESSION['admin_full_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-2">
                                <li><a class="dropdown-item" href="#">Profil Ayarları</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Çıkış Yap</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

<div class="container-fluid p-3 p-lg-4"></div>