<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim - Cuhfe Turizm</title>
    <meta name="description" content="Cuhfe Turizm ile iletişime geçin. Sorularınız, önerileriniz veya tur talepleriniz için bize ulaşın.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Kendi CSS Dosyamız -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- === PRELOADER (YÜKLENİYOR EKRANI) === -->
     <div id="preloader">
        <div class="loader-container">
            <div class="spinner"></div>
            <img src="images/loading.png" alt="Yükleniyor..." class="loader-image">
        </div>
    </div>

    <!-- === ÜST BİLGİ BARI === -->
    <div class="top-bar text-white py-2">
        <!-- index.php'deki ile aynı -->
        <div class="container d-flex justify-content-between align-items-center">
             <div>
                <a href="tel:+905551234567" class="text-white me-3"><i class="fas fa-phone me-1"></i> +90 555 123 45 67</a>
                <a href="mailto:info@cuhfeturizm.com" class="text-white"><i class="fas fa-envelope me-1"></i> info@cuhfeturizm.com</a>
            </div>
            <div>
                <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- === NAVİGASYON MENÜSÜ === -->
    <nav class="navbar navbar-expand-lg bg-light sticky-top shadow-sm main-nav">
         <div class="container">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Anasayfa</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kurumsal</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Hac Programları</a></li>
                </ul>
            </div>
            <a class="navbar-brand mx-auto" href="index.php"><img src="images/logo.png" alt="Cuhfe Turizm Logo" class="main-logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                 <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Umre Programları</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kudüs Turları</a></li>
                    <!-- İletişim linkini 'active' yapıyoruz -->
                    <li class="nav-item"><a class="nav-link active" href="iletisim.php">İletişim</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- === SAYFA BAŞLIĞI ALANI === -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">İletişime Geçin</h1>
            <p class="page-subtitle">Bize Her Türlü Sorularınızı Sorabilirsiniz.</p>
        </div>
    </header>

    <!-- === İLETİŞİM İÇERİĞİ === -->
    <main class="contact-page-section py-5">
        <div class="container">
            <div class="row">
                <!-- Sol Taraf: Açıklama ve Harita -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="contact-left-panel">
                        <h3 class="panel-title">Bizimle İrtibata Geçin</h3>
                        <p class="lead">Hepimiz hayatın ne kadar zor ve zorlayıcı olabileceğini çok iyi biliyoruz. Farkındayız ki karşılaşabileceğiniz tüm sorunlara her zaman kesin bir çözüm bulamayabilirsiniz. Ancak, size yardımcı olmak için buradayız.</p>
                        
                        <!-- İletişim Bilgi Kutuları -->
                        <div class="row my-4">
                            <div class="col-md-4 contact-info-item">
                                <i class="fas fa-map-marked-alt fa-2x"></i>
                                <h6>Adres</h6>
                                <p>Örnek Mah. Turizm Sk. No:1, İstanbul</p>
                            </div>
                             <div class="col-md-4 contact-info-item">
                                <i class="fas fa-phone-alt fa-2x"></i>
                                <h6>Arayın</h6>
                                <p>+90 212 123 45 67</p>
                            </div>
                             <div class="col-md-4 contact-info-item">
                                <i class="fas fa-envelope-open-text fa-2x"></i>
                                <h6>Email</h6>
                                <p>info@cuhfeturizm.com</p>
                            </div>
                        </div>
                        
                        <!-- Harita -->
                        <div class="map-container shadow">
                           <div id="gmap_canvas"></div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Taraf: İletişim Formu -->
                <div class="col-lg-6">
                    <div class="contact-form-panel p-4 shadow-lg">
                        <h3 class="panel-title">İletişim Bilgileri</h3>
                        <form action="mesaj_gonder.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Ad</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="surname" class="form-label">Soyad</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Adres</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                             <div class="mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                             <div class="mb-3">
                                <label for="message" class="form-label">Mesajınız</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <!-- İsteğin üzerine reCAPTCHA eklenmedi. İstenirse buraya eklenebilir. -->
                            <button type="submit" class="btn btn-primary w-100">Gönder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- === Footer (Alt Kısım) === -->
    <footer class="footer pt-5 pb-4">
        <!-- index.php'deki ile aynı -->
        <div class="container text-md-left">
           <!-- Footer içeriğini anasayfadan kopyalayabilirsin -->
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2024 Cuhfe Turizm | Tüm Hakları Saklıdır.
        </div>
    </footer>

    <!-- === WHATSAPP BUTONU === -->
    <a href="https://wa.me/905551234567?text=Merhaba, sitenizden ulaşıyorum." class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Kendi JS Dosyamız (Harita fonksiyonunu da içeriyor) -->
    <script src="js/script.js"></script>
    
    <!-- GOOGLE MAPS API (KENDİ API ANAHTARINI EKLE!) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
</body>
</html>