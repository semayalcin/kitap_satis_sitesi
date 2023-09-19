<?php
require "lib/veritabani.php";
require "partials/_header.php";
require "lib/fonksiyonlar.php";
require "partials/_message.php";
require "partials/_navbar.php";

if (isset($_SESSION['sepet'])) {
    $sepet = $_SESSION['sepet'];
} else {
    $sepet = array();
}

$miktar = 1;
$sepet_toplam = 0;
$teslimat_adres = $teslimat_adresErr = "";
$kart_uzerindeki_isim = $kart_uzerindeki_isimErr = "";
$kart_numarasi = $kart_numarasiErr = "";
$son_kullanma_tarihi = $son_kullanma_tarihiErr = "";
$kullanici_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["teslimat_adres"])) {
        $teslimat_adresErr = "Adres zorunludur.";
    } else {
        $teslimat_adres = $_POST["teslimat_adres"];

        if (!empty($teslimat_adres) && isset($_SESSION['id'])) {

            $sql = "INSERT INTO adresler (id, adres) VALUES (?, ?)";
            if ($stmt = mysqli_prepare($baglanti, $sql)) {
                mysqli_stmt_bind_param($stmt, "is", $kullanici_id, $teslimat_adres);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION["message"] = "Adres kaydedildi.";
                    $_SESSION["type"] = "success";
                    $_SESSION["url"] = "sepet.php";
                    header("Location: sepet.php");
                } else {
                    $_SESSION["message"] = "Adres kaydedilemedi.";
                    $_SESSION["type"] = "error";
                    $_SESSION["url"] = "sepet.php";
                    header("Location: sepet.php");
                }
            }
        }
    }

    if (!empty($_POST["odeme_secenek"])) {
        $odeme_secenek = $_POST["odeme_secenek"];

        if ($odeme_secenek == "kart_odeme") {

            if (empty($_POST["kart_uzerindeki_isim"])) {
                $kart_uzerindeki_isimErr = "Kartın üzerindeki ismi girmek zorunludur.";
            } else {
                $kart_uzerindeki_isim = $_POST["kart_uzerindeki_isim"];
            }

            if (empty($_POST["kart_numarasi"])) {
                $kart_numarasiErr = "Kart numarası zorunludur.";
            } else {
                $kart_numarasi = $_POST["kart_numarasi"];
            }

            if (empty($_POST["son_kullanma_tarihi"])) {
                $son_kullanma_tarihiErr = "Son kullanma tarihi zorunludur.";
            } else {
                $son_kullanma_tarihi = $_POST["son_kullanma_tarihi"];
            }

            if (empty($kart_uzerindeki_isimErr) && empty($kart_numarasiErr) && empty($son_kullanma_tarihiErr)) {
                $kart_uzerindeki_isim = $_POST["kart_uzerindeki_isim"];
                $kart_numarasi = $_POST["kart_numarasi"];
                $son_kullanma_tarihi = $_POST["son_kullanma_tarihi"];

                $sql = "INSERT INTO odemeler (id, odeme_secenek, kart_uzerindeki_isim, kart_numarası, son_kullanma_tarihi) VALUES (?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($baglanti, $sql)) {
                    mysqli_stmt_bind_param($stmt, "issss", $kullanici_id, $odeme_secenek, $kart_uzerindeki_isim, $kart_numarasi, $son_kullanma_tarihi);
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION["message"] = "Kart bilgileri kaydedildi.";
                        $_SESSION["type"] = "success";
                        $_SESSION["url"] = "sepet.php";
                        header("Location: sepet.php");
                    } else {
                        $_SESSION["message"] = "Kart bilgileri kaydedilemedi.";
                        $_SESSION["type"] = "danger";
                        $_SESSION["url"] = "sepet.php";
                        header("Location: sepet.php");
                    }
                }
            }
        } else if ($odeme_secenek == "kapida_odeme") {
            $odeme_secenek = "kapida_odeme";

            $sql = "INSERT INTO odemeler (id, odeme_secenek) VALUES (?, ?)";
            if ($stmt = mysqli_prepare($baglanti, $sql)) {

                mysqli_stmt_bind_param($stmt, "is", $kullanici_id, $odeme_secenek);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION["message"] = "Kapıda ödeme  seçeneği kaydedildi.";
                    $_SESSION["type"] = "success";
                    $_SESSION["url"] = "sepet.php";
                    header("Location: sepet.php");
                } else {
                    $_SESSION["message"] = "Kapıda ödeme  seçeneği kaydedildi.";
                    $_SESSION["type"] = "danger";
                    $_SESSION["url"] = "sepet.php";
                    header("Location: sepet.php");
                }
            }
        }
    } else {
        $_SESSION["message"] = "Lütfen bir ödeme seçeneği seçin.";
        $_SESSION["type"] = "danger";
        $_SESSION["url"] = "sepet.php";
        header("Location: sepet.php");
    }
}


?>

<div class="container my-3">
    <?php if (empty($sepet)) : ?>
        <p class="uyari">Sepete kitap eklenmemiş.</p>
    <?php else : ?>
        <div class="row">
            <div class="col-5">
                <div class="row">
                    <div class="col-12 baslik">
                        <p>Ürünleriniz</p>
                    </div>
                    <?php foreach ($sepet as $kitap_id) : ?>
                        <?php $kitap = getKitap($kitap_id); ?>
                        <div class="card mb-3">
                            <div class="row">
                                <div class="col-4">
                                    <img src="img/<?php echo $kitap["kitap_resim"] ?>" alt="" class="card-img-top resim">
                                </div>
                                <div class="col-5">
                                    <div class="card-body">
                                        <p id="kitap_adi"><?php echo $kitap["kitap_adi"] ?></p>
                                        <p id="kitap_yazari"><?php echo $kitap["kitap_yazari"] ?></p>
                                        <p id="yayinevi"><?php echo $kitap["yayinevi"] ?></p>
                                    </div>
                                    <div class="d-flex flex-row  kitap_miktar">
                                        <form action="artir_azalt.php?kitap_id=<?php echo $kitap_id; ?>" method="post">
                                            <input type="hidden" name="kitap_id" value="<?php echo $kitap_id; ?>">
                                            <input type="hidden" name="islem" value="azalt">
                                            <button type="submit" class="btn btn-secondary">-</button>
                                        </form>

                                        <span class="m-2" id="miktar-<?php echo $kitap_id; ?>">
                                            <?php
                                            $miktar_key = "miktar_" . $kitap_id;
                                            echo isset($_SESSION[$miktar_key]) ? $_SESSION[$miktar_key] : 1;
                                            ?>
                                        </span>

                                        <form action="artir_azalt.php?kitap_id=<?php echo $kitap_id; ?>" method="post">
                                            <input type="hidden" name="kitap_id" value="<?php echo $kitap_id; ?>">
                                            <input type="hidden" name="islem" value="artir">
                                            <button type="submit" class="btn btn-primary">+</button>
                                        </form>
                                    </div>
                                    <p id="fiyat">
                                        <?php
                                        $toplam_fiyat = $kitap["fiyat"];
                                        if (isset($_SESSION[$miktar_key])) {
                                            $toplam_fiyat *= $_SESSION[$miktar_key];
                                        }
                                        echo $toplam_fiyat . " TL";
                                        $sepet_toplam += $toplam_fiyat;
                                        ?>
                                    </p>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex kaldir">
                                        <a href="sepetten_kaldir.php?kitap_id=<?php echo $kitap_id; ?>" class="btn btn-danger btn-lg" id="font">Kaldır</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-7">
                <div class="row row_right">
                    <div class="col-6 sepet_toplam">Sepet Toplamı</div>
                    <div class="col-6 sepet_fiyat">
                        <?php echo $sepet_toplam; ?> TL
                    </div>
                </div>

                <!-- Açılabilir Sekmeler -->
                <div class="accordion mt-4" id="accordionExample">
                    <!-- Adres Sekmesi -->
                    <div class="card">
                        <div class="card-header" id="adresHeading">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#adresCollapse" aria-expanded="true" aria-controls="adresCollapse">
                                    <p class="menu">Adres</p>
                                </button>
                            </h2>
                        </div>

                        <div id="adresCollapse" class="collapse" aria-labelledby="adresHeading" data-parent="#accordionExample">
                            <div class="card-body">
                                <form method="POST" action="sepet.php">
                                    <div class="form-group adres_form">
                                        <label for="teslimat_adres">Adres</label>
                                        <textarea class="form-control" id="teslimat_adres" name="teslimat_adres" rows="4" value="<?php echo $teslimat_adres; ?>"></textarea>
                                        <div class="text-danger"><?php echo $teslimat_adresErr; ?></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3" id="adres_odeme">Adresi Kaydet</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Ödeme Sekmesi -->
                    <div class="card">
                        <div class="card-header" id="odemeHeading">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#odemeCollapse" aria-expanded="false" aria-controls="odemeCollapse">
                                    <p class="menu">Ödeme</p>
                                </button>
                            </h2>
                        </div>
                        <div id="odemeCollapse" class="collapse" aria-labelledby="odemeHeading" data-parent="#accordionExample">
                            <div class="card-body">
                                <!-- Ödeme Bilgileri Formu -->
                                <form method="POST" action="sepet.php">
                                    <div class="form-group odeme_form">
                                        <label for="odeme_secenekleri">Ödeme Seçeneği:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="kapida_odeme" name="odeme_secenek" value="kapida_odeme">
                                            <label class="form-check-label" for="kapida_odeme">
                                                Kapıda Ödeme
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="kart_odeme" name="odeme_secenek" value="kart_odeme">
                                            <label class="form-check-label" for="kart_odeme">
                                                Kart ile Ödeme
                                            </label>
                                        </div>
                                    </div>

                                    <div id="kartBilgileriFormu" class="kart_form">
                                        <div class="form-group">
                                            <label for="kart_uzerindeki_isim">Kartın Üzerindeki İsim:</label>
                                            <input type="text" class="form-control" id="kart_uzerindeki_isim" name="kart_uzerindeki_isim" value="<?php echo $kart_uzerindeki_isim; ?>">
                                            <div class="text-danger"><?php echo $kart_uzerindeki_isimErr; ?></div>

                                        </div>
                                        <div class="form-group">
                                            <label for="kart_numarasi">Kart Numarası:</label>
                                            <input type="text" class="form-control" id="kart_numarasi" name="kart_numarasi" value="<?php echo $kart_numarasi; ?>">
                                            <div class="text-danger"><?php echo $kart_numarasiErr; ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="son_kullanma_tarihi">Son Kullanma Tarihi:</label>
                                            <input type="date" class="form-control" id="son_kullanma_tarihi" name="son_kullanma_tarihi" value="<?php echo $son_kullanma_tarihi; ?>">
                                            <div class="text-danger"><?php echo $son_kullanma_tarihiErr; ?></div>
                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-success mt-3" id="adres_odeme">Kaydet</button>
                                </form>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var kartOdemeSecenek = document.getElementById('kart_odeme');
                                        var kapidaOdemeSecenek = document.getElementById('kapida_odeme');
                                        var kartBilgileriFormu = document.getElementById('kartBilgileriFormu');

                                        function guncelleFormGorunurlugu() {
                                            if (kartOdemeSecenek.checked) {
                                                kartBilgileriFormu.style.display = 'block';
                                            } else {
                                                kartBilgileriFormu.style.display = 'none';
                                            }
                                        }

                                        kartOdemeSecenek.addEventListener('change', guncelleFormGorunurlugu);
                                        kapidaOdemeSecenek.addEventListener('change', guncelleFormGorunurlugu);

                                        guncelleFormGorunurlugu();
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include "partials/_footer.php" ?>