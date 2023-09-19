<?php
require "lib/veritabani.php";
require "partials/_header.php";
require "lib/fonksiyonlar.php";
include "partials/_message.php";
require "partials/_navbar.php";
?>
<div class="row float-left">
    <div class="col-sm-12">
        <div id="banner" class="carousel slide" data-ride="carousel">

            <ol class="carousel-indicators">
                <?php for ($i = 0; $i < 3; $i++) : ?>
                    <li data-target="#banner" data-slide-to="<?php echo $i; ?>" <?php if ($i === 0) echo 'class="active"'; ?>></li>
                <?php endfor; ?>
            </ol>

            <div class="carousel-inner">
                <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <div class="item <?php if ($i === 1) echo 'active'; ?>">
                        <img src="img\resim<?php echo $i; ?>_banner.jpg" alt="">
                    </div>
                <?php endfor; ?>

            </div>

            <a class="left carousel-control" href="#banner" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Önceki</span>
            </a>
            <a class="right carousel-control" href="#banner" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Sonraki</span>
            </a>
        </div>
    </div>
</div>

<div class="container d-flex justify-content-between">
    <?php
    $sonuc = getTurler();
    while ($tur = mysqli_fetch_assoc($sonuc)) : ?>
        <a href="kitaplar.php?tur=<?php echo $tur["tur_id"]; ?>">
            <div class="card m-2 mt-4" id="tur">
                <div class="card-body flex-column">
                    <img src="img/<?php echo $tur["resim"] ?>" alt="<?php echo $tur["tur"] ?>">
                    <p> <?php echo $tur["tur"] ?> </p>
                </div>
            </div>
        </a>
    <?php endwhile; ?>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <h3 id="slogan">Yeni ufuklara yol almak için kitapları seçin!</h3>
        </div>
    </div>
</div>

<div class="container-fluid" id="aciklama_bilgiler">
    <div class="row">
        <div id="aciklama" class="col-sm-12 col-md-6">
            <img src="img\resim.jpg" alt="" class="img-fluid rounded-start">
            <p>Kitaplarla olan bu tutkulu yolculuğumuza okurlara en güzel kitapları ulaştırma heyecanı ile çıktık. Bu
                yolculuğumzuda sen de bizi yalnız bırakmayıp hep bizimleydin.
                Bu sebeple sizlere daha iyi hizmet verebilmek için her gün samimiyetle çalışmaya devam edeceğiz. Keyifli
                okumalar!</p>
        </div>
        <div id="bilgiler" class="col-sm-12 col-md-6">
            <h3>Önemli Bilgiler</h3>
            <ul>
                <li>Hakkımızda</li>
                <li>Teslimat Koşulları</li>
                <li>Satış Sözleşmesi</li>
                <li>Garanti Koşulları</li>
            </ul>
        </div>
    </div>
</div>


<?php include "partials/_footer.php" ?>