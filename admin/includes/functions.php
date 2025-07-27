<?php
// admin/includes/functions.php

function generateSlug($text) {
    // Türkçe karakterleri değiştir
    $turkish = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
    $english = ['i', 'g', 'u', 's', 'o', 'c', 'I', 'G', 'U', 'S', 'O', 'C'];
    $text = str_replace($turkish, $english, $text);

    // Her şeyi küçük harfe çevir
    $text = strtolower($text);

    // Alfanümerik olmayan her şeyi kaldır (harfler ve rakamlar hariç)
    $text = preg_replace('/[^a-z0-9-]+/', '-', $text);

    // Birden fazla tireyi tek tire yap
    $text = preg_replace('/-+/', '-', $text);

    // Başta ve sonda olabilecek tireleri temizle
    $text = trim($text, '-');
    
    // Eğer metin boşsa, rastgele bir string oluştur
    if (empty($text)) {
        return 'n-a-' . time();
    }
    
    return $text;
}
?>