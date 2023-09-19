<nav id="navbar" class="navbar navbar-expand-lg">
  <a href="/proje/anasayfa.php" class="navbar-brand"><img src="/proje/img/resim.jpg" alt="Logo" class="img-fluid rounded-start"></a>
  <ul class="navbar-nav me-auto">
    <li class="nav-item">
      <a class="nav-link <?php if ($current_page == '/proje/anasayfa.php') echo "active"; ?>" href="/proje/anasayfa.php">Anasayfa</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if ($current_page == '/proje/iletisim.php') echo "active"; ?>" href="/proje/iletisim.php">İletişim</a>
    </li>
    <?php if (loggedIn() && isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "admin") : ?>
      <li class="nav-item">
        <a class="nav-link <?php if ($current_page == '/proje/kitap_ekle.php') echo "active"; ?>" href="/proje/kitap_ekle.php" id="kitap_ekle">Kitap Ekle</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($current_page == '/proje/tur_ekle.php') echo "active"; ?>" href="/proje/tur_ekle.php" id="tur_ekle">Tür Ekle</a>
      </li>
    <?php endif; ?>
  </ul>

  <ul class="navbar-nav me-2">
    <?php if (loggedIn()) : ?>
      <li class="nav-item">
        <a class="nav-link <?php if ($current_page == '/proje/sepet.php') echo "active"; ?>" id="sepet" href="/proje/sepet.php">
          <img src="/proje/img/sepet.jpg" alt="Sepet Logo" class="img-fluid">
          Sepet
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Merhaba, <?php echo $_SESSION["kullaniciAdi"]; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <a class="dropdown-item" href="#">Profilim</a>
          <a class="dropdown-item" href="#">Ayarlar</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/proje/cikis.php">Çıkış</a>
        </div>
      </li>
    <?php else : ?>
      <li class="nav-item">
        <a class="nav-link <?php if ($current_page == '/proje/giris.php') echo "active"; ?>" href="/proje/giris.php">Giriş Yap</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($current_page == '/proje/kayit.php') echo "active"; ?>" href="/proje/kayit.php">Kayıt Ol</a>
      </li>
    <?php endif; ?>
  </ul>
</nav>