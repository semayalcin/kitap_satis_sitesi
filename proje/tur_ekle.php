<?php
require "lib/veritabani.php";
require "partials/_header.php";
require "lib/fonksiyonlar.php";
include "partials/_message.php";
require "partials/_navbar.php";

$tur = $turErr = "";
$resim = $resimErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["tur"])) {
        $turErr = "Tür adı zorunludur.";
    } else {
        $tur = $_POST["tur"];
    }

    if (empty($_POST["resim"])) {
        $resimErr = "Resim seçmek zorunludur.";
    } else {
        $resim = $_POST["resim"];
    }

    if (empty($turErr) && empty($resimErr)) {

        // Ekleme işlemi için önce türün veritabında olup olmadığını kontrol ediyoruz.
        $turKontrolSQL = "SELECT tur_id FROM turler WHERE tur = ?";
        if ($kontrolStmt = mysqli_prepare($baglanti, $turKontrolSQL)) {
            
            mysqli_stmt_bind_param($kontrolStmt, "s", $tur);
            mysqli_stmt_execute($kontrolStmt);
            mysqli_stmt_store_result($kontrolStmt);

            if (mysqli_stmt_num_rows($kontrolStmt) > 0) {
                
                $_SESSION["message"] = "Bu tür zaten mevcut.";
                $_SESSION["type"] = "danger";
                $_SESSION["url"] = "tur_ekle.php";
                header('Location: tur_ekle.php');
                
            } else {
                $sql = "INSERT INTO turler(tur, resim) VALUES (?,?)";

                if ($stmt = mysqli_prepare($baglanti, $sql)) {
                    $_tur = $tur;
                    $_resim = $resim;

                    mysqli_stmt_bind_param($stmt, "ss", $_tur, $_resim);

                    if (mysqli_stmt_execute($stmt)) {

                        $_SESSION["message"] = "Tür eklendi.";
                        $_SESSION["type"] = "success";
                        $_SESSION["url"] = "tur_ekle.php";
                        header('Location: tur_ekle.php');
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
        <div class="col-12">
            <a href="tur_sil.php">
                <button type="submit" class="btn btn-danger btn-lg" id="sil_btn">Tür Sil</button>
            </a>
        </div>
        <div id="iletisim-formu" class="col-12">
            <form action="tur_ekle.php" method="post">
                <div class="form-group">
                    <label for="tur">Tür Adı</label>
                    <input type="text" class="form-control" name="tur" value="<?php echo $tur; ?>">
                    <div class="text-danger"><?php echo $turErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="resim">Resim Yükle</label>
                    <input type="file" class="form-control" name="resim">
                    <div class="text-danger"><?php echo $resimErr; ?></div>
                </div>

                <input id="ekle_btn" class="btn btn-primary btn-lg" type="submit" value="Ekle">
            </form>
        </div>
    </div>
</div>

<?php include "partials/_footer.php" ?>