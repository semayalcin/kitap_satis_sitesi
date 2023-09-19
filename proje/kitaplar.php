<?php
require "lib/veritabani.php";
require "partials/_header.php";
require "lib/fonksiyonlar.php";
require "partials/_message.php";
require "partials/_navbar.php";

if (isset($_GET["tur"])) {
    $tur_id = $_GET["tur"];
    $kitaplar = getKitaplarbyTur($tur_id);

    $tur_adi = getTur_adi($tur_id);
}
?>

<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 baslik">
            <ul class="nav nav-pills">
                <?php foreach (getTurler() as $tur) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if (isset($_GET["tur"]) && $_GET["tur"] == $tur["tur_id"]) echo "active"; ?>" href="kitaplar.php?tur=<?php echo $tur["tur_id"]; ?>">
                            <?php echo $tur["tur"]; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <!-- <?php if (isset($tur_adi)) : ?>
                <p><?php echo $tur_adi; ?></p>
            <?php endif; ?> -->
        </div>

        <?php if (empty($kitaplar)) : ?>
            <p class="uyari">Kayıtlı kitap bulunamadı.</p>
        <?php else : ?>
            <div class="row">
                <?php if (loggedIn() && $_SESSION["user_type"] == "admin") : ?>
                    <form method="post" action="kitap_sil.php?tur=<?php echo $tur_id; ?>">
                    <?php endif; ?>
                    <?php foreach ($kitaplar as $kitap) : ?>
                        <div class="col-md-2 mb-3 mt-5">
                            <div class="card d-flex flex-column" id="kitap">
                                <?php if (loggedIn() && $_SESSION["user_type"] == "admin") : ?>
                                    <input type="checkbox" name="secilen_kitaplar[]" value="<?php echo $kitap['kitap_id']; ?>">
                                <?php endif; ?>
                                <div class="d-flex justify-content-center">
                                    <img src="img/<?php echo $kitap["kitap_resim"] ?>" alt="" class="card-img-top resim">
                                </div>
                                <div class="card-body" id="govde">
                                    <p id="kitap_adi"><?php echo $kitap["kitap_adi"] ?></p>
                                    <p><?php echo $kitap["kitap_yazari"] ?></p>
                                    <p><?php echo $kitap["yayinevi"] ?></p>
                                    <p id="fiyat"><?php echo $kitap["fiyat"] . " TL" ?></p>

                                </div>
                                <?php if ($kitap['stok'] == 0) : ?>
                                    <p class="btn btn-danger" id="sil_btn">Stokta Yok</p>
                                <?php else : ?>
                                    <a href="<?php
                                                if (loggedIn()) {
                                                    echo "sepete_ekle.php?tur=" . $tur_id . "&kitap_id=" . $kitap['kitap_id'];
                                                } else {
                                                    echo "giris.php";
                                                }
                                                ?>" class="btn btn-primary mb-3" id="sepet_buton">Sepete Ekle</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (loggedIn() && $_SESSION["user_type"] == "admin") : ?>
                        <input type="submit" class="btn btn-danger btn-lg" name="sil" id="sil_btn" value="Seçilen Kitapları Sil">
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include "partials/_footer.php" ?>