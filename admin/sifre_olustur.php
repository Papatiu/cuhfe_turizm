<?php
// sifre_olustur.php

// Şifrelenecek olan basit metin şifre
$plain_password = '123456';

// PHP'nin güvenli password_hash fonksiyonunu kullanarak şifreyi hash'le
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Oluşturulan hash'lenmiş şifreyi ekrana yazdır
echo "Şifreniz: " . $plain_password . "<br>";
echo "Veritabanına Kaydedilecek Hash: <br>";
echo '<textarea rows="4" cols="80" onclick="this.select()">' . $hashed_password . '</textarea>';

?>