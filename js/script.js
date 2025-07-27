// Sayfa yüklendiğinde çalışacak kodlar
document.addEventListener("DOMContentLoaded", function() {

    // Sayaç Animasyonu
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // Animasyon hızı

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;

                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
                observer.unobserve(counter); // Animasyon bir kere çalışsın
            }
        });
    }, {
        threshold: 0.5 // Elementin %50'si göründüğünde başlasın
    });

    counters.forEach(counter => {
        observer.observe(counter);
    });

});
// === GOOGLE MAPS FONKSİYONU ===
// Bu fonksiyon, HTML'deki Google Maps script'i tarafından otomatik olarak çağrılır.
function initMap() {
    // Ofisinizin koordinatları (Örnek: Üsküdar, İstanbul)
    // Kendi ofisinizin enlem ve boylamını Google Haritalar'dan bulup buraya yazın.
    const myLatLng = { lat: 41.0279, lng: 29.0205 };

    // Harita seçenekleri
    const mapOptions = {
        zoom: 16, // Yakınlaştırma seviyesi
        center: myLatLng,
        mapTypeId: 'roadmap', // Harita tipi (roadmap, satellite, hybrid, terrain)
        styles: [ // Burası haritanın renklerini sadeleştirmek için opsiyoneldir.
            {
                "featureType": "poi.business",
                "stylers": [{ "visibility": "off" }]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text",
                "stylers": [{ "visibility": "off" }]
            }
        ]
    };
    
    // Haritayı oluştur ve HTML'deki 'gmap_canvas' id'li div'e yerleştir
    const map = new google.maps.Map(document.getElementById("gmap_canvas"), mapOptions);

    // Özel Marker (İkon olarak kendi logonuzu kullanıyoruz)
    const marker = new google.maps.Marker({
        position: myLatLng, // Marker'ın konumu
        map: map, // Hangi haritaya ekleneceği
        title: 'Cuhfe Turizm Ofisi', // Üzerine gelince çıkacak yazı
        icon: {
            url: 'images/loading.png', // Logo dosyasının yolu
            scaledSize: new google.maps.Size(60, 60) // Logonun haritadaki boyutu (genişlik, yükseklik)
        }
    });
}
// === PRELOADER KODU ===
// Sayfadaki tüm kaynaklar (resimler vb.) yüklendiğinde çalışır
window.onload = function() {
    const preloader = document.getElementById('preloader');
    
    // Yükleyiciyi "preloader-hidden" sınıfını ekleyerek gizle.
    // CSS'teki transition efekti sayesinde bu işlem yavaşça olacak.
    if (preloader) {
        // Minimum 500 milisaniye bekle, çok hızlı yüklenirse bile görünsün.
        setTimeout(() => {
             preloader.classList.add('preloader-hidden');
        }, 500);
    }
};