<?php
session_start();
include "partials/_message.php";

if (isset($_GET['kitap_id'])) {
    $kitap_id = $_GET['kitap_id'];

    //burda dizide $kitap_id aranır ve indeksi key e atanır.
    if (($key = array_search($kitap_id, $_SESSION['sepet'])) !== false) {
        //burda da dizideki key indisi diziden çıkarılır.
        unset($_SESSION['sepet'][$key]);
    }

    header('Location: sepet.php');
}
?>
