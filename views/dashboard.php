<?php
defined('BASEPATH') or die('No Script Kiddies Please!');

require_once BASEPATH . '/views/layouts/header.php';

$title = 'Login';

if(is_method('POST')) {
  $db = new Core\DB\Connection;
  $query = $db->getConn()->prepare("SELECT * FROM users WHERE username = ?");
  $query->bindValue(1, $_POST['username']);
  $query->execute();
  $user = $query->fetch(PDO::FETCH_OBJ);

  if(!$user) {
    set_flash_message('warning', 'Kata pengguna tidak ditemukan');
    redirect('/?page=login');
  }

  if(password_verify($_POST['password'], $user->password)) {
    $_SESSION['user'] = $user;
    redirect('/?page=dashboard');
  } else {
    set_flash_message('warning', 'Kata sandi yang anda masukan salah.');
    redirect('/?page=login');
  }
}

?>

<section class="content-wrapper">
  <div class="row">
    <div class="col-6">
      <div class="box-item">
        <span class="counter">23545</span>
        <h3 class="title">Pengunjung</h3>
        <a href="#">Selengkapnya</a>
      </div>
    </div>
    <div class="col-6">
      <div class="box-item">
        <span class="counter">23545</span>
        <h3 class="title">Label</h3>
        <a href="<?= base_url('/?page=tags') ?>">Selengkapnya</a>
      </div>
    </div>
    <div class="col-6">
      <div class="box-item">
        <span class="counter">23545</span>
        <h3 class="title">Artikel</h3>
        <a href="<?= base_url('/?page=articles') ?>">Selengkapnya</a>
      </div>
    </div>
    <div class="col-6">
      <div class="box-item">
        <span class="counter">23545</span>
        <h3 class="title">Halaman</h3>
        <a href="#">Selengkapnya</a>
      </div>
    </div>
  </div>
</section>

<?php
require_once BASEPATH . '/views/layouts/footer.php';
?>