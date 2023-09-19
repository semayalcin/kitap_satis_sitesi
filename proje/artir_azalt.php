<?php
session_start();

if (isset($_POST['kitap_id']) && isset($_POST['islem'])) {
    $kitap_id = $_POST['kitap_id'];
    $islem = $_POST['islem'];
    $miktar_key = "miktar_" . $kitap_id;

    // Eğer $_SESSION[$miktar_key] tanımlı değilse, 1 olarak başlat
    if (!isset($_SESSION[$miktar_key])) {
        $_SESSION[$miktar_key] = 1;
    }

    $miktar = $_SESSION[$miktar_key];

    if ($islem == "artir") {
        $miktar++;
    } elseif ($islem == "azalt" && $miktar > 1) {
        $miktar--;
    }

    // Güncellenen miktarı oturumda saklayın
    $_SESSION[$miktar_key] = $miktar;

    // İşlem tamamlandıktan sonra sepet.php sayfasına yönlendirin
    header("Location: sepet.php");
}

// session_start();
// include "partials/_message.php";

// if (isset($_GET['kitap_id'])) {
//     $kitap_id = $_GET['kitap_id'];
// }
// if (isset($_POST['islem'])) {
//     $islem = $_POST['islem'];
// }


// if (isset($kitap_id) && isset($islem)) {
//     $miktar_key = "miktar_" . $kitap_id;
//     $miktar = isset($_SESSION[$miktar_key]) ? $_SESSION[$miktar_key] : 1;

//     if ($islem == "artir") {
//         $miktar++;
//     } elseif ($islem == "azalt" && $miktar > 1) {
//         $miktar--;
//     }

//     // Güncellenen miktarı oturumda saklayın
//     $_SESSION[$miktar_key] = $miktar;

//     header("Location: sepet.php");
// }
?>