 <footer class="footer-section">
        <div class="container">
            <div class="row">
                <!-- Sütun 1: Şirket Bilgisi -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="index.php"><img src="images/logo.png" alt="Cuhfe Turizm Logo"></a>
                        </div>
                        <p class="footer-text mt-3">
                            <?php echo htmlspecialchars($settings['footer_text'] ?? 'Huzur ve güvenle çıktığınız bu kutlu yolda, manevi rehberiniz olmak için buradayız.'); ?>
                        </p>
                        <div class="footer-social-icons mt-3">
                            <a href="<?php echo htmlspecialchars($settings['social_facebook'] ?? '#'); ?>"
                                target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="<?php echo htmlspecialchars($settings['social_instagram'] ?? '#'); ?>"
                                target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="<?php echo htmlspecialchars($settings['social_youtube'] ?? '#'); ?>"
                                target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Sütun 2: Hızlı Linkler -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4 class="widget-title">Sayfalar</h4>
                        <ul class="list-unstyled footer-links">
                        <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Anasayfa</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'hakkimizda.php') ? 'active' : ''; ?>" href="hakkimizda.php">Hakkımızda</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'hac_programlari.php') ? 'active' : ''; ?>" href="hac_programlari.php">Hac Programları</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>" href="blog.php">Blog Sayfamız</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'iptal_iade.php') ? 'active' : ''; ?>" href="iptal_iade.php">İptal Ve İade Koşuılları</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo ($currentPage == 'iletisim.php') ? 'active' : ''; ?>" href="iletisim.php">İletişim</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Sütun 3: Turlarımız -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4 class="widget-title">Turlarımız</h4>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#">Ekonomik Umre</a></li>
                            <li><a href="#">Lüks Umre</a></li>
                            <li><a href="#">Ramazan Umresi</a></li>
                            <li><a href="#">2025 Hac Programı</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Sütun 4: İletişim Bilgileri -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4 class="widget-title">İletişim</h4>
                        <ul class="list-unstyled footer-contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <p><?php echo htmlspecialchars($settings['contact_address'] ?? 'Adres bilgisi girilmemiş.'); ?>
                                </p>
                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <a
                                    href="tel:<?php echo htmlspecialchars($settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone'] ?? 'Telefon bilgisi girilmemiş.'); ?></a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a
                                    href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email'] ?? 'Email bilgisi girilmemiş.'); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Copyright Bölümü -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-text">
                        <p>© <?php echo date("Y"); ?> Cuhfe Turizm. Tüm Hakları Saklıdır.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>