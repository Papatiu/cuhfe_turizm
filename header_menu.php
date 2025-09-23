<!-- === ÜST BİLGİ BARI (Dinamik) === -->
<div class="top-bar text-white py-2">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <a href="tel:<?php echo htmlspecialchars($settings['contact_phone'] ?? '+905551234567'); ?>" class="text-white me-3"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($settings['contact_phone'] ?? '+90 555 123 45 67'); ?></a>
            <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@cuhfeturizm.com'); ?>" class="text-white"><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($settings['contact_email'] ?? 'info@cuhfeturizm.com'); ?></a>
        </div>
        <div>
            <a href="<?php echo htmlspecialchars($settings['social_facebook'] ?? '#'); ?>" target="_blank" class="text-white me-2"><i class="fab fa-facebook"></i></a>
            <a href="<?php echo htmlspecialchars($settings['social_instagram'] ?? '#'); ?>" target="_blank" class="text-white me-2"><i class="fab fa-instagram"></i></a>
            <a href="<?php echo htmlspecialchars($settings['social_youtube'] ?? '#'); ?>" target="_blank" class="text-white"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</div>

<!-- === NAVİGASYON MENÜSÜ === -->
<nav class="navbar navbar-expand-lg bg-light sticky-top shadow-sm main-nav">
    <div class="container">
        <!-- Mobil Logo ve Toggle Butonu -->
        <a class="navbar-brand d-lg-none mx-auto" href="index.php"><img src="images/logo.png" alt="Cuhfe Turizm Logo" style="height:50px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavMenu" aria-controls="mainNavMenu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

        <!-- Menü İçeriği -->
        <div class="collapse navbar-collapse" id="mainNavMenu">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); // Aktif menüyü belirlemek için mevcut sayfa adını alalım ?>
            <ul class="navbar-nav w-100 d-flex justify-content-between">
                <!-- Sol Menü -->
                <div class="d-lg-flex">
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Anasayfa</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'hakkimizda.php') ? 'active' : ''; ?>" href="hakkimizda.php">Hakkımızda</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'hac_programlari.php') ? 'active' : ''; ?>" href="hac_programlari.php">Hac Programları</a></li>
                </div>
                
                <!-- Ortadaki Logo (Sadece Masaüstü) -->
                <a class="navbar-brand d-none d-lg-block" href="index.php"><img src="images/logo.png" alt="Cuhfe Turizm Logo" class="main-logo"></a>
                
                <!-- Sağ Menü -->
                <div class="d-lg-flex">
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>" href="blog.php">Blog Sayfamız</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'iptal_iade.php') ? 'active' : ''; ?>" href="iptal_iade.php">İptal Ve İade Koşuılları</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'iletisim.php') ? 'active' : ''; ?>" href="iletisim.php">İletişim</a></li>
                </div>
            </ul>
        </div>
    </div>
</nav>