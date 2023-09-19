<?php
require "lib/veritabani.php";
require "partials/_header.php";
require "lib/fonksiyonlar.php";
include "partials/_message.php";
require "partials/_navbar.php";

if (isset($_GET["tur"])) {
    $tur = $_GET["tur"];
}

if (empty($_POST["secilen_kitaplar"])) {
    $_SESSION["url"] = "kitaplar.php?tur=" . $tur;
    $_SESSION["message"] = "Lütfen silinecek kitapları seçin.";
    $_SESSION["type"] = "danger";
    header("Location: kitaplar.php?tur=" . $tur);
}

if (isset($_POST["sil"])) {
    if (isset($_POST["secilen_kitaplar"]) && is_array($_POST["secilen_kitaplar"])) {

        foreach ($_POST["secilen_kitaplar"] as $kitap_id) {

            $sql1 = "DELETE FROM kitap_tur WHERE kitap_id = $kitap_id";

            if (mysqli_query($baglanti, $sql1)) {

                $sql2 = "DELETE FROM kitaplar WHERE kitap_id = $kitap_id";

                if (mysqli_query($baglanti, $sql2)) {

                    $_SESSION["message"] = "Kitap/lar silindi.";
                    $_SESSION["url"] = "kitaplar.php?tur=" . $tur;
                    $_SESSION["type"] = "success";
                } else {
                    $_SESSION["message"] = "Kitap silme hatası: " . mysqli_error($baglanti);
                    $_SESSION["url"] = "kitaplar.php?tur=" . $tur;
                    $_SESSION["type"] = "danger";
                }

                //burda silinen kitap sepette varsa sepetten de silsin.
                if (isset($_SESSION['sepet'])) {
                    if (($key = array_search($kitap_id, $_SESSION['sepet'])) !== false) {
                        unset($_SESSION['sepet'][$key]);
                    }
                }
            } else {
                $_SESSION["message"] = "Kitap-tür ilişkili kayıtları silme hatası: " . mysqli_error($baglanti);
                $_SESSION["url"] = "kitaplar.php?tur=" . $tur;
                $_SESSION["type"] = "danger";
            }
        }
    }
    header("Location: kitaplar.php?tur=" . $tur);
}
