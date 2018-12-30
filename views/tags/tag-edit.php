<?php
defined('BASEPATH') or die('No Script Kiddies Please!');

require_once(BASEPATH . '/core/models/TagModel.php');
$model = new TagModel;

if(!isset($_GET['id'])) {
  abort(404);
}

//get data label
$label = $model->findTagById($_GET['id']);

if(!$label) {
  abort(404);
}

//setting page
$title = 'Edit Label';
$description = 'Edit label kamu disini.';

// submit
if(is_method('POST')) {
  $model->update($label->id, array(
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
        value="<?= $label->tag ?>"
      />
    </div>
    <div class="form-setion">
      <button class="btn">Simpan</button>
      <a href="<?= base_url('/?page=tags') ?>" class="btn">Kembali</a>
    </div>
  </form>
</section>

<?php
require_once BASEPATH . '/views/layouts/footer.php';
?>