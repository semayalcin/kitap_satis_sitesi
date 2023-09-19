<?php

    $current_page = $_SERVER['REQUEST_URI'];

    session_start();

    function loggedIn(){
        return(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true);
        
    }

    function getTurler(){
        include "veritabani.php";
        
        $query = "SELECT * FROM turler";
        $sonuc = mysqli_query($baglanti, $query);
        mysqli_close($baglanti);
        return $sonuc;
    }

    function getKitaplarbyTur($tur) {
        include "veritabani.php";
    
        $query = "SELECT kitaplar.* FROM kitaplar
                  INNER JOIN kitap_tur ON kitaplar.kitap_id = kitap_tur.kitap_id
                  WHERE kitap_tur.tur_id = ?";
        
        $stmt = mysqli_prepare($baglanti, $query);
        mysqli_stmt_bind_param($stmt, "i", $tur);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        $kitaplar = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        return $kitaplar;
    }

    function getTur_adi($tur){
        include "veritabani.php";

        $sorgu = "SELECT tur FROM turler WHERE tur_id = ?";
    
        if ($stmt = mysqli_prepare($baglanti, $sorgu)) {
            mysqli_stmt_bind_param($stmt, "i", $tur);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $tur_adi);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
    
        } else {
            echo "Sorgu hatası: " . mysqli_error($baglanti);
        }
        
        return $tur_adi;
        mysqli_close($baglanti);

    }
    
    function getKitap($kitap_id){
        include "veritabani.php";

        $query = "SELECT * FROM kitaplar WHERE kitap_id = ?";
    
        $stmt = mysqli_prepare($baglanti, $query);
        mysqli_stmt_bind_param($stmt, "i", $kitap_id);
    
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
      
        $kitap = mysqli_fetch_assoc($result);
    
        return $kitap; 

        mysqli_close($baglanti);

    }

    

?>