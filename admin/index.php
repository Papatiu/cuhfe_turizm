<?php
// admin/index.php
session_start();

// Eğer kullanıcı zaten giriş yapmışsa, onu dashboard'a yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}
require_once 'includes/db.php'; // Hata mesajı sonrası db gerekebilir diye alta aldık
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuhfe Turizm - Admin Girişi</title>
    <!-- Gerekli Stil Dosyaları -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="login-body">

    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-logo">
                <img src="../images/logo.png" alt="Cuhfe Turizm Logo">
                <h3 class="mb-4">Yönetim Paneli Girişi</h3>
            </div>
            
            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger p-2 text-center small">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>

            <form action="login_check.php" method="POST">
                <!-- Bootstrap 5'in modern 'floating labels' yapısını kullanıyoruz -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı Adı" required>
                    <label for="username"><i class="fas fa-user me-2"></i>Kullanıcı Adı</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Şifre" required>
                    <label for="password"><i class="fas fa-lock me-2"></i>Şifre</label>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Giriş Yap <i class="fas fa-sign-in-alt ms-2"></i></button>
                </div>
            </form>
        </div>
        <div class="login-footer">
            © <?php echo date("Y"); ?> Cuhfe Turizm. Tüm Hakları Saklıdır.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>