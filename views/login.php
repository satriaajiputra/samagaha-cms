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
  <?php require_once BASEPATH . '/views/partials/_alert.php' ?>
  <form action method="post">
    <div class="form-section">
      <label>Nama Pengguna</label>
      <input type="text" name="username" class="form-el">
    </div>
    <div class="form-section">
      <label>Kata Sandi</label>
      <input type="password" name="password" class="form-el">
    </div>
    <div class="form-section">
      <button class="btn btn-primary">Masuk</button>
    </div>
  </form>
</section>

<?php
require_once BASEPATH . '/views/layouts/footer.php';
?>