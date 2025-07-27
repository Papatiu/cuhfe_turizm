<?php
// admin/musteri_ekle.php
$page_title = "Yeni Müşteri Ekle";
require_once 'includes/header.php';
?>

<h2 class="mb-4">Yeni Müşteri Kayıt Formu</h2>

<div class="card shadow">
    <div class="card-body">
        <!-- İleride bu formu musteri_kaydet.php dosyasına göndereceğiz -->
        <form action="musteri_kaydet.php" method="POST" enctype="multipart/form-data">
            
            <div class="row g-3">
                <div class="col-md-9">
                    <h5 class="border-bottom pb-2 mb-3">Kimlik Bilgileri</h5>
                    <div class="row g-3">
                         <div class="col-md-4">
                            <label for="tc_no" class="form-label">TC Kimlik No</label>
                            <input type="text" class="form-control" id="tc_no" name="tc_no" required>
                        </div>
                        <div class="col-md-4">
                            <label for="serial_no" class="form-label">Seri No</label>
                            <input type="text" class="form-control" id="serial_no" name="serial_no">
                        </div>
                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Uyruğu</label>
                            <input type="text" class="form-control" id="nationality" name="nationality" value="T.C.">
                        </div>

                         <div class="col-md-6">
                            <label for="first_name" class="form-label">Adı</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                         <div class="col-md-6">
                            <label for="last_name" class="form-label">Soyadı</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="father_name" class="form-label">Baba Adı</label>
                            <input type="text" class="form-control" id="father_name" name="father_name">
                        </div>
                        <div class="col-md-6">
                            <label for="mother_name" class="form-label">Anne Adı</label>
                            <input type="text" class="form-control" id="mother_name" name="mother_name">
                        </div>

                        <div class="col-md-6">
                            <label for="birth_place" class="form-label">Doğum Yeri</label>
                            <input type="text" class="form-control" id="birth_place" name="birth_place">
                        </div>
                        <div class="col-md-6">
                            <label for="birth_date" class="form-label">Doğum Tarihi</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                     <h5 class="border-bottom pb-2 mb-3">Profil Resmi</h5>
                     <!-- Resim önizleme alanı -->
                     <img src="../images/customers/default_user.png" id="photo-preview" class="img-thumbnail mb-3" alt="Profil Resmi">
                     <label for="photo" class="form-label">Resim Yükle</label>
                     <input class="form-control" type="file" id="photo" name="photo" onchange="previewImage(event)">
                </div>

                <div class="col-12"><hr class="my-4"></div>

                <div class="col-md-6">
                     <h5 class="border-bottom pb-2 mb-3">Diğer Kişisel Bilgiler</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                             <label for="gender" class="form-label">Cinsiyet</label>
                            <select id="gender" name="gender" class="form-select">
                                <option selected>Seçiniz...</option>
                                <option value="Erkek">Erkek</option>
                                <option value="Kadın">Kadın</option>
                            </select>
                        </div>
                         <div class="col-md-4">
                             <label for="marital_status" class="form-label">Medeni Hali</label>
                            <select id="marital_status" name="marital_status" class="form-select">
                                 <option selected>Seçiniz...</option>
                                 <option value="Bekar">Bekar</option>
                                 <option value="Evli">Evli</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="religion" class="form-label">Dini</label>
                            <input type="text" class="form-control" id="religion" name="religion" value="İslam">
                        </div>
                    </div>
                </div>

                 <div class="col-md-6">
                     <h5 class="border-bottom pb-2 mb-3">Pasaport Bilgileri (Opsiyonel)</h5>
                     <div class="row g-3">
                         <div class="col-md-6">
                             <label for="passport_no" class="form-label">Pasaport No</label>
                             <input type="text" class="form-control" id="passport_no" name="passport_no">
                         </div>
                         <div class="col-md-6">
                             <label for="passport_expiry" class="form-label">Geçerlilik Tarihi</label>
                             <input type="date" class="form-control" id="passport_expiry" name="passport_expiry">
                         </div>
                     </div>
                 </div>

                <div class="col-12"><hr class="my-4"></div>
                
                 <div class="col-md-12">
                     <h5 class="border-bottom pb-2 mb-3">İletişim Bilgileri</h5>
                     <div class="row g-3">
                         <div class="col-md-4">
                             <label for="phone" class="form-label">Telefon</label>
                             <input type="tel" class="form-control" id="phone" name="phone" required>
                         </div>
                         <div class="col-md-4">
                             <label for="email" class="form-label">E-Posta</label>
                             <input type="email" class="form-control" id="email" name="email">
                         </div>
                         <div class="col-md-4">
                             <label for="address" class="form-label">Adres</label>
                             <input type="text" class="form-control" id="address" name="address">
                         </div>
                     </div>
                 </div>

                <div class="col-12 text-end mt-4">
                    <a href="musteri_kaydet.php" class="btn btn-secondary">İptal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Müşteriyi Kaydet</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
<!-- Resim önizleme için script -->
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('photo-preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>