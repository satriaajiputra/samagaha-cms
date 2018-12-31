<?php
defined('BASEPATH') or die('No Script Kiddies Please!');

use Core\Models\TagModel;

//setting page
$title = 'Tambah Label';
$description = 'Tambahkan label untuk situs kamu disini.';

// submit
if(is_method('POST')) {
  $model = new TagModel;
  $model->store(array(
    'tags' => $_POST['label']
  ));
}

require_once BASEPATH . '/views/layouts/header.php';

?>

<section class="content-wrapper">
  <?php require_once BASEPATH . '/views/partials/_alert.php' ?>
  <form action method="post">
    <div class="form-section">
      <label>Label</label>
      <input
        class="form-el"
        type="text"
        name="label"
        placeholder="label a, label b, ..."
      />
      <span class="help-block">Dipisahkan dengan karakter koma (,)</span>
    </div>
    <div class="form-setion">
      <button class="btn">Tambahkan</button>
      <a href="<?= base_url('/?page=tags') ?>" class="btn">Kembali</a>
    </div>
  </form>
</section>

<?php
require_once BASEPATH . '/views/layouts/footer.php';
?>