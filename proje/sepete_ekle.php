<?php
session_start();
include "partials/_message.php";

if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = array();
}

if (isset($_GET['kitap_id'])) {
    $kitap_id = $_GET['kitap_id'];
    $tur = $_GET['tur'];

    if (!in_array($kitap_id, $_SESSION['sepet'])) {
        $_SESSION['sepet'][] = $kitap_id;

        $_SESSION["message"] = "Kitap sepete eklendi.";
        $_SESSION["type"] = "success";
        $_SESSION["url"] = "kitaplar.php?tur=".$tur;

        header('Location: kitaplar.php?tur='.$tur);
    } else {
        $_SESSION["message"] = "Kitap sepette zaten var.";
        $_SESSION["type"] = "danger";
        $_SESSION["url"] = "kitaplar.php?tur=".$tur;

        header('Location: kitaplar.php?tur='.$tur);
    }
}
?>
