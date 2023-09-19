<?php
require "lib/veritabani.php";
require "partials/_header.php";
require "lib/fonksiyonlar.php";
include "partials/_message.php";
require "partials/_navbar.php";

$kitap_adi = $kitap_adiErr = "";
$kitap_yazari = $kitap_yazariErr = "";
$yayinevi = $yayineviErr = "";
$turler = $turErr = "";
$fiyat = $fiyatErr = "";
$resim = $resimErr = "";
$stok = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["kitap_adi"])) {
        $kitap_adiErr = "Kitap adı zorunludur.";
    } else {
        $kitap_adi = $_POST["kitap_adi"];
    }

    if (empty($_POST["kitap_yazari"])) {
        $kitap_yazariErr = "Kitap yazarı zorunludur.";
    } else {
        $kitap_yazari = $_POST["kitap_yazari"];
    }

    if (empty($_POST["yayinevi"])) {
        $yayineviErr = "Yayınevi zorunludur.";
    } else {
        $yayinevi = $_POST["yayinevi"];
    }

    if (empty($_POST["turler"])) {
        $turErr = "Tür seçmek zorunludur.";
    } else {
        $turler = $_POST["turler"];
    }

    if (empty($_POST["fiyat"])) {
        $fiyatErr = "Fiyat zorunludur.";
    } else {
        $fiyat = $_POST["fiyat"];
        $fiyat = str_replace(",", ".", $fiyat);
        $fiyat = floatval($fiyat);
    }

    if (empty($_POST["resim"])) {
        $resimErr = "Resim seçmek zorunludur.";
    } else {
        $resim = $_POST["resim"];
    }

    $stok = $_POST["stok"];


    if (empty($kitap_adiErr) && empty($kitap_yazariErr) && empty($yayineviErr) && empty($turErr) && empty($fiyatErr) && empty($resimErr)) {

        // Ekleme işlemi için önce kitabın veritabnında olup olmadığını kontrol ediyoruz.
        $kitapKontrolSQL = "SELECT kitap_id FROM kitaplar WHERE kitap_adi = ? AND kitap_yazari = ? AND yayinevi = ?";

        if ($kontrolStmt = mysqli_prepare($baglanti, $kitapKontrolSQL)) {

            mysqli_stmt_bind_param($kontrolStmt, "sss", $kitap_adi, $kitap_yazari, $yayinevi);
            mysqli_stmt_execute($kontrolStmt);
            mysqli_stmt_store_result($kontrolStmt);

            if (mysqli_stmt_num_rows($kontrolStmt) > 0) {

                $_SESSION["message"] = "Bu kitap zaten mevcut.";
                $_SESSION["type"] = "danger";
                $_SESSION["url"] = "kitap_ekle.php";
                header('Location: kitap_ekle.php');
            } else {
                $sql = "INSERT INTO kitaplar(kitap_adi, kitap_yazari, yayinevi, fiyat, kitap_resim, stok) VALUES (?,?,?,?,?,?)";

                if ($stmt = mysqli_prepare($baglanti, $sql)) {

                    mysqli_stmt_bind_param($stmt, "sssssi", $kitap_adi, $kitap_yazari, $yayinevi, $fiyat, $resim, $stok);

                    if (mysqli_stmt_execute($stmt)) {

                        $kitap_id = mysqli_insert_id($baglanti);

                        //burda kitap-tur tablosuna da ekleme yapıyor.
                        foreach ($turler as $tur_id) {
                            $sql = "INSERT INTO kitap_tur (kitap_id, tur_id) VALUES (?, ?)";
                            $stmt = mysqli_prepare($baglanti, $sql);
                            mysqli_stmt_bind_param($stmt, "ii", $kitap_id, $tur_id);
                            mysqli_stmt_execute($stmt);
                        }

                        $_SESSION["kitap_adi"] = $_kitap_adi;
                        $_SESSION["message"] = "Kitap eklendi.";
                        $_SESSION["type"] = "success";
                        $_SESSION["url"] = "kitap_ekle.php";

                        header('Location: kitap_ekle.php');
                    } else {
                        echo mysqli_error($baglanti);
                        echo "<br>";
                        echo "Hata.";
                    }
                }
            }
        } else {
            
            echo mysqli_error($baglanti);
            echo "<br>";
            echo "Hata.";
        }
    }
}
?>

<div class="container my-3">
    <div class="row">
        <div id="iletisim-formu" class="col-12">
            <form action="kitap_ekle.php" method="post">
                <div class="form-group">
                    <label for="kitap_adi">Kitap Adı</label>
                    <input type="text" class="form-control" name="kitap_adi" value="<?php echo $kitap_adi; ?>">
                    <div class="text-danger"><?php echo $kitap_adiErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="kitap_yazari">Kitabın Yazarı</label>
                    <input type="text" class="form-control" name="kitap_yazari" value="<?php echo $kitap_yazari; ?>">
                    <div class="text-danger"><?php echo $kitap_yazariErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="yayinevi">Yayınevi</label>
                    <input type="text" class="form-control" name="yayinevi" value="<?php echo $yayinevi; ?>">
                    <div class="text-danger"><?php echo $yayineviErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="tur">Kitap Türleri</label>
                    <?php
                    $sonuc = getTurler();
                    while ($tur = mysqli_fetch_assoc($sonuc)) :
                        $seciliMi = isset($_POST['turler']) && in_array($tur["tur_id"], $_POST['turler']) ? 'checked' : '';
                    ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="turler[]" value="<?php echo $tur["tur_id"]; ?>" <?php echo $seciliMi; ?> id="tur-<?php echo $tur["tur_id"]; ?>">
                            <label class="form-check-label" for="tur-<?php echo $tur["tur_id"]; ?>"><?php echo $tur["tur"]; ?></label>
                        </div>
                    <?php endwhile; ?>
                    <div class="text-danger"><?php echo $turErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="fiyat">Fiyat</label>
                    <input type="text" class="form-control" name="fiyat" value="<?php echo $fiyat; ?>">
                    <div class="text-danger"><?php echo $fiyatErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="resim">Resim Yükle</label>
                    <input type="file" class="form-control" name="resim">
                    <div class="text-danger"><?php echo $resimErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" name="stok" min="0" value="<?php echo empty($stok) ? 0 : $stok; ?>">
                </div>
                <input id="ekle_btn" class="btn btn-primary btn-lg" type="submit" value="Ekle">
            </form>
        </div>
    </div>
</div>

<?php include "partials/_footer.php" ?>