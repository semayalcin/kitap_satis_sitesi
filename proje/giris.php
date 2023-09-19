<?php
    require "lib/veritabani.php";
    require "partials/_header.php";
    require "lib/fonksiyonlar.php";
    require "partials/_navbar.php";


    if(loggedIn()) {
        header("Location: anasayfa.php");
    }

    $kullaniciAdiErr = $sifreErr = "";
    $kullaniciAdi = $sifre = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(empty($_POST["kullaniciAdi"])) {
            $kullaniciAdiErr = "Kullanıcı adı zorunludur.";
        }else {
            $kullaniciAdi = $_POST["kullaniciAdi"];
        }

        if(empty($_POST["sifre"])) {
            $sifreErr = "Şifre zorunludur.";
        }else {
            $sifre = $_POST["sifre"];
        }

        if(empty($kullaniciAdiErr) && empty($sifreErr)) {
            $sql = "SELECT id, kullaniciAdi, sifre, type FROM kullanicilar WHERE kullaniciAdi=?";
            
            if($stmt = mysqli_prepare($baglanti, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $kullaniciAdi);

                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1) {
                        
                        mysqli_stmt_bind_result($stmt, $id, $kullaniciAdi, $_sifre, $_user_type);
                        if(mysqli_stmt_fetch($stmt)) {
                            if(password_verify($sifre, $_sifre)) {
                                $_SESSION["loggedIn"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["kullaniciAdi"] = $kullaniciAdi;
                                $_SESSION["user_type"] = $_user_type;
                                
                                header('Location: anasayfa.php');

                            }else{
                                $sifreErr = "Şifre yanlış";
                            }
                        }
                    }else{
                        $kullaniciAdiErr = "Kullanıcı adı yanlış";
                    }
                }
            }
            mysqli_stmt_close($stmt);
            mysqli_close($baglanti);
        }
    }
?>

<div class="container my-3">
    <div class="row">
        <div id="iletisim-formu" class="col-12">
            <form action="giris.php" method="post">
                <div class="form-group">
                    <label for="kullaniciAdi">Kullanıcı Adı</label>
                    <input type="text" class="form-control" id="kullaniciAdi" name="kullaniciAdi" value="<?php echo $kullaniciAdi;?>">
                    <div class="text-danger"><?php echo $kullaniciAdiErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="sifre">Şifre</label>
                    <input type="password" class="form-control" id="sifre" name="sifre" value="<?php echo $sifre;?>">
                    <div class="text-danger"><?php echo $sifreErr; ?></div>
                </div>
                <input class="btn btn-primary btn-lg" type="submit" value="Gönder">
            </form>
        </div>
    </div>
</div>

<?php include "partials/_footer.php" ?>