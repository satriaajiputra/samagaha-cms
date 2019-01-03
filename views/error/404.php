<?php
defined('BASEPATH') or die('No Script Kiddies Please!');

require_once BASEPATH . '/views/layouts/header.php';

?>

<section class="content-wrapper">
  <?php require_once BASEPATH . '/views/partials/_alert.php' ?>
  <div class="alert alert-info">
    Error 404. Halaman Tidak Ditemukan!
  </div>
</section>

<?php
require_once BASEPATH . '/views/layouts/footer.php';
?>