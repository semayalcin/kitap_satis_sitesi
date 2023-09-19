<?php 
    require "lib/veritabani.php";
    require "partials/_header.php";
    require "lib/fonksiyonlar.php";
    require "partials/_navbar.php";

?>

<?php
    $kullaniciAdiErr = $kullaniciAdi = "";
    $emailErr = $email = "";
    $dogumTErr = $dogumT = "";
    $cinsiyetErr = $cinsiyet = "";
    $user_type = $user_typeErr = "";
    $sifreErr = $sifre  = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(empty($_POST["kullaniciAdi"])) {
            $kullaniciAdiErr = "Kullanıcı adı zorunludur.";
        }elseif (strlen($_POST["kullaniciAdi"]) < 5 && strlen($_POST["kullaniciAdi"]) > 15) {
            $kullaniciAdiErr = "Kullanıcı adı 5-15 karakter aralığında olmalıdır.";
        }else{
            $sql = "SELECT id FROM kullanicilar WHERE kullaniciAdi=?";
           
            if ($stmt = mysqli_prepare($baglanti, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $_POST["kullaniciAdi"]);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $kullaniciAdiErr = "Kullanıcı adı alınmış.";
                    }else{
                        $kullaniciAdi = $_POST["kullaniciAdi"];
                    }
                }else{
                    echo mysqli_error($baglanti);
                    echo "Hata";
                }
            }
        }

        if(empty($_POST["email"])) {
            $emailErr = "Email zorunludur.";
        }elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $emailErr = "Email hatalıdır.";
        }else {

            $sql = "SELECT id FROM kullanicilar WHERE email=?";

            if ($stmt = mysqli_prepare($baglanti, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $_POST["email"]);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $emailErr = "Email alınmış.";
                    }else{
                        $email = $_POST["email"];
                    }
                }else{
                    echo mysqli_error($baglanti);
                    echo "Hata.";
                }
            }
        }

        if(empty($_POST["dogumT"])) {
            $dogumTErr = "Doğum tarihi zorunludur.";
        } else {
            $dogumT = $_POST["dogumT"];
        }

        if(empty($_POST["cinsiyet"])) {
            $cinsiyetErr = "Cinsiyet zorunludur.";
        } else {
            $cinsiyet = $_POST["cinsiyet"];
        }

        if(empty($_POST["user_type"])) {
            $user_typeErr = "Kullanıcı tipi zorunludur.";
        } else {
            $user_type = $_POST["user_type"];
        }
        
        if(empty($_POST["sifre"])) {
            $sifreErr = "Şifre zorunludur.";
        } else {
            $sifre = $_POST["sifre"];
        }
        
        if (empty($kullaniciAdiErr) && empty($emailErr) && empty($dogumTErr) && empty($cinsiyetErr) && empty($user_typeErr) && empty($sifreErr)) {
            
            $sql = "INSERT INTO kullanicilar(kullaniciAdi, email, dogumT, cinsiyet, sifre, type) VALUES (?,?,?,?,?,?)";
            if ($stmt = mysqli_prepare($baglanti, $sql)) {
                $_kullaniciAdi = $kullaniciAdi;
                $_email = $email;
                $_dogumT = $dogumT;
                $_cinsiyet = $cinsiyet;
                $_sifre = password_hash($sifre, PASSWORD_DEFAULT);
                $_user_type = $user_type;

                mysqli_stmt_bind_param($stmt, "ssssss", $_kullaniciAdi, $_email, $_dogumT, $_cinsiyet, $_sifre, $_user_type);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["kullaniciAdi"] = $_kullaniciAdi;
                    $_SESSION["user_type"] = $_user_type;
                    
                    header('Location: anasayfa.php');
                }else {
                    echo mysqli_error($baglanti);
                    echo "<br>";
                    echo "Hata.";
                }
            }
        }
    }
?>

<div class="container my-3">
    <div class="row">
        <div id="iletisim-formu" class="col-12">
            <form action="kayit.php" method="post">
                <div class="form-group">
                    <label for="kullaniciAdi">Kullanıcı Adı</label>
                    <input type="text" class="form-control" id="kullaniciAdi" name="kullaniciAdi" value="<?php echo $kullaniciAdi;?>">
                    <div class="text-danger"><?php echo $kullaniciAdiErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>">
                    <div class="text-danger"><?php echo $emailErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="dogumT">Doğum Tarihi</label>
                    <input type="date" class="form-control" id="dogumT" name="dogumT" value="<?php echo $dogumT;?>">
                    <div class="text-danger"><?php echo $dogumTErr; ?></div>
                </div>
                <div class="form-group">
                    <label>Cinsiyet</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="cinsiyet" id="male" value="Erkek" <?php echo $cinsiyet === 'Erkek' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="male">Erkek</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="cinsiyet" id="female" value="Kadın" <?php echo $cinsiyet === 'Kadın' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="female">Kadın</label>
                    </div>
                    <div class="text-danger"><?php echo $cinsiyetErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="user_type">Admin-Kullanıcı</label>
                    <select class="form-control" id="user_type" name="user_type">
                        <option value="kullanici" <?php echo $user_type === 'kullanici' ? 'selected' : ''; ?>>Kullanıcı</option>
                        <option value="admin" <?php echo $user_type === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <div class="text-danger"><?php echo $user_typeErr; ?></div>
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