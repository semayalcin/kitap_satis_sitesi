<?php
    $baglanti = mysqli_connect("localhost", "root", "", "db");

    if (!$baglanti) {
        die("Bağlantı hatası: " . mysqli_connect_error());
    }
    
    $sorgu_kullanicilar = "SHOW TABLES LIKE 'kullanicilar'";
    $tabloKontrol_kullanicilar = mysqli_query($baglanti, $sorgu_kullanicilar);

    if (mysqli_num_rows($tabloKontrol_kullanicilar) == 0) {
        $sql = "CREATE TABLE kullanicilar (
            id INT(11) PRIMARY KEY AUTO_INCREMENT,
            kullaniciAdi VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            dogumT DATE,
            cinsiyet VARCHAR(50),
            sifre VARCHAR(255) NOT NULL,
            type VARCHAR(50) NOT NULL,
            kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP
        )";   
        mysqli_query($baglanti, $sql);

    }

    $sorgu_turler = "SHOW TABLES LIKE 'turler'";
    $tabloKontrol_turler = mysqli_query($baglanti, $sorgu_turler);

    if (mysqli_num_rows($tabloKontrol_turler) == 0) {
        $sql = "CREATE TABLE turler (
            tur_id INT(11) PRIMARY KEY AUTO_INCREMENT,
            tur VARCHAR(100) NOT NULL,
            resim VARCHAR(100) NOT NULL
        )";   
        mysqli_query($baglanti, $sql);
    }

    $sorgu_kitaplar = "SHOW TABLES LIKE 'kitaplar'";
    $tabloKontrol_kitaplar = mysqli_query($baglanti, $sorgu_kitaplar);

    if (mysqli_num_rows($tabloKontrol_kitaplar) == 0) {
        $sql = "CREATE TABLE kitaplar (
            kitap_id INT(11) PRIMARY KEY AUTO_INCREMENT,
            kitap_adi VARCHAR(100) NOT NULL,
            kitap_yazari VARCHAR(100) NOT NULL,
            yayinevi VARCHAR(100) NOT NULL,
            fiyat DECIMAL(50,2) NOT NULL,
            kitap_resim VARCHAR(100) NOT NULL,
            stok INT(11) NOT NULL DEFAULT 0
        )";   
        mysqli_query($baglanti, $sql);
    }

    $sorgu_kitap_tur = "SHOW TABLES LIKE 'kitap_tur'";
    $tabloKontrol_kitap_tur = mysqli_query($baglanti, $sorgu_kitap_tur);

    if (mysqli_num_rows($tabloKontrol_kitap_tur) == 0) {
        $sql = "CREATE TABLE kitap_tur (
            id INT(11) PRIMARY KEY AUTO_INCREMENT,
            kitap_id INT(11) NOT NULL,
            tur_id INT(11) NOT NULL,
            FOREIGN KEY (kitap_id) REFERENCES kitaplar(kitap_id),
            FOREIGN KEY (tur_id) REFERENCES turler(tur_id)
        )";   
        mysqli_query($baglanti, $sql);
    }

    $sorgu_adresler = "SHOW TABLES LIKE 'adresler'";
    $tabloKontrol_adresler = mysqli_query($baglanti, $sorgu_adresler);
  
    if (mysqli_num_rows($tabloKontrol_adresler) == 0) {
        $sql = "CREATE TABLE adresler (
            adres_id INT(11) NOT NULL AUTO_INCREMENT,
            id INT(11) NOT NULL,
            adres TEXT NOT NULL,
            kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (adres_id),
            FOREIGN KEY (id) REFERENCES kullanicilar(id)
        )";   
        mysqli_query($baglanti, $sql);
    }

    $sorgu_odemeler = "SHOW TABLES LIKE 'odemeler'";
    $tabloKontrol_odemeler = mysqli_query($baglanti, $sorgu_odemeler);
  
    if (mysqli_num_rows($tabloKontrol_odemeler) == 0) {
        $sql = "CREATE TABLE odemeler (
            odeme_id INT(11) NOT NULL AUTO_INCREMENT,
            id INT(11) NOT NULL,
            odeme_secenek VARCHAR(50) NOT NULL,
            kart_uzerindeki_isim VARCHAR(100) NOT NULL,
            kart_numarası INT(11) NOT NULL,
            son_kullanma_tarihi DATE,
            kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (odeme_id),
            FOREIGN KEY (id) REFERENCES kullanicilar(id)
        )";   
        mysqli_query($baglanti, $sql);
    }
