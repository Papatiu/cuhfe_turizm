// admin/assets/js/script.js

document.addEventListener("DOMContentLoaded", function(event) {
    
    // Gerekli DOM elementlerini seçelim
    const menuToggle = document.getElementById("menu-toggle");
    const wrapper = document.getElementById("wrapper");
    const overlay = document.getElementById("sidebar-overlay");

    // Menü açma/kapatma butonu için olay dinleyici
    if (menuToggle) {
        menuToggle.addEventListener("click", function (e) {
            e.preventDefault();
            wrapper.classList.toggle("toggled");
        });
    }

    // Overlay'e tıklandığında menüyü kapatmak için olay dinleyici (Mobilde kullanışlı)
    if (overlay) {
        overlay.addEventListener("click", function() {
            wrapper.classList.remove("toggled");
        });
    }

});