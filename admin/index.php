<?php
// admin/index.php

// Veritabanı bağlantısını ve session'ı başlat
require_once 'includes/db.php';

// Eğer kullanıcı zaten giriş yapmışsa, onu dashboard'a yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuhfe Turizm - Admin Paneli Giriş</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <!-- Admin Özel CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">

    <div class="login-container">
        <div class="login-box">
            <div class="login-logo text-center mb-4">
                <img src="../images/logo.png" alt="Logo" style="height: 80px;">
                <h3 class="mt-2">Yönetim Paneli</h3>
            </div>
            
            <?php
            // Hata mesajı varsa göster
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']); // Mesajı gösterdikten sonra sil
            }
            ?>

            <form action="login_check.php" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required>
                </div>
                <div class="input-group mb-4">
                     <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Şifre" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>