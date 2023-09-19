<?php
require "lib/veritabani.php";
require "partials/_header.php";
require "lib/fonksiyonlar.php";
include "partials/_message.php";
require "partials/_navbar.php";

$turler = $turErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty($_POST["turler"])) {
        $turErr = "Tür seçmek zorunludur.";
    }else {
        $turler = $_POST["turler"];
    }

    if(empty($turErr)) {

        foreach ($_POST["turler"] as $tur_id) {
            
            $sql = "SELECT COUNT(*) FROM kitap_tur WHERE tur_id = ?";
            $stmt = mysqli_prepare($baglanti, $sql);
            mysqli_stmt_bind_param($stmt, "i", $tur_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $kitap_sayisi);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($kitap_sayisi > 0) {
                $_SESSION["message"] = "Bu türe ait kayıtlı kitap bulunmaktadır. Silme işlemi yapılamaz.";
                $_SESSION["type"] = "success";
                $_SESSION["url"] = "tur_sil.php";
                header("Location: tur_sil.php");
            } else {
                $sil = "DELETE FROM turler WHERE tur_id = ?";
                $stmt = mysqli_prepare($baglanti, $sil);
                mysqli_stmt_bind_param($stmt, "i", $tur_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $_SESSION["message"] = "Tür/ler silindi.";
                $_SESSION["type"] = "success";
                $_SESSION["url"] = "anasayfa.php";
                header('Location: anasayfa.php');
            }
        }
    }
}
?>

<div class="container my-3">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-5">Silmek istediğiniz türü seçin</h2>
        </div>
        <div id="iletisim-formu" class="col-12">
            <form action="tur_sil.php" method="post">
                <div class="form-group">
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
                <input id="ekle_btn" class="btn btn-danger btn-lg mt-5" type="submit" value="Sil">
            </form>
        </div>
    </div>
</div>

<?php include "partials/_footer.php" ?>