<?php 
    include "partials/_header.php";
    require "lib/fonksiyonlar.php";
    include "partials/_navbar.php";
?>

<div class="container-fluid">
    <div class="container-fluid">
        <div class="row float-left">
            <div class="col-sm-12">
                <iframe id="harita" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3011.3198820546995!2d28.906994975837513!3d40.99637127135207!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cabb9d1694a2bf%3A0xc7093f681c41540!2sT.C%20Zeytinburnu%20Belediyesi!5e0!3m2!1str!2str!4v1691411534140!5m2!1str!2str" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div id="adres" class="col-sm-12 col-md-6">
                <h3>Adres</h3>
                <p>Beştelsiz, Belediye Cd. No:5, 34020 Zeytinburnu/İstanbul</p>
            </div>
            <div id="iletisim-formu" class="col-sm-12 col-md-6">
                <h3>İletişim Formu</h3>
                <div class="container mt-5">

                    <form id="contactForm" action="iletisim.php" method="post">
                        <div class="form-group">
                            <label for="firstName">Ad:</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Soyad:</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-posta:</label>
                            <input ty pe="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="dogumT">Doğum Tarihi:</label>
                            <input type="date" class="form-control" id="dogumT" name="dogumT" required>
                        </div>
                        <div class="form-group">
                            <label>Cinsiyet:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cinsiyet" id="male" value="Erkek" required>
                                <label class="form-check-label" for="male">Erkek</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cinsiyet" id="female" value="Kadın">
                                <label class="form-check-label" for="female">Kadın</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Şifre:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <input class="btn btn-primary btn-lg" type="submit" value="Gönder">
                    </form>

                </div>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $firstName = $_POST["firstName"];
                    $lastName = $_POST["lastName"];
                    $email = $_POST["email"];
                    $dogumT = $_POST["dogumT"];
                    $cinsiyet = $_POST["cinsiyet"];
                    $password = $_POST["password"];

                    echo "<h3><br>Bilgiler:</h3>";
                    echo "<p><br>Ad: $firstName </p>";
                    echo "<p>Soyad: $lastName</p>";
                    echo "<p>Email: $email</p>";
                    echo "<p>Doğum Tarihi: $dogumT</p>";
                    echo "<p>Cinsiyet: $cinsiyet</p>";
                    echo "<p>Şifre: $password</p>";
                }
                ?>

            </div>
        </div>
    </div>
</div>

<?php include "partials/_footer.php" ?>