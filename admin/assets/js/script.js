// admin/assets/js/script.js (MOBİL UYUMLU VERSİYON)

document.addEventListener("DOMContentLoaded", function(event) {
    const menuToggle = document.getElementById("menu-toggle");
    const wrapper = document.getElementById("wrapper");
    const overlay = document.getElementById("sidebar-overlay");

    // Menü açma/kapatma butonu
    if (menuToggle) {
        menuToggle.addEventListener("click", function () {
            wrapper.classList.toggle("toggled");
        });
    }

    // Overlay'e tıklanınca menüyü kapat
    if (overlay) {
        overlay.addEventListener("click", function() {
            wrapper.classList.remove("toggled");
        });
    }
});