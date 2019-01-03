<?php
defined('BASEPATH') or die('No Script Kiddies Please!');

use Core\Models\PageModel;
use Core\Models\TagModel;

if(!isset($_GET['id'])) {
  abort(404);
}

//setting page
$id = $_GET['id'];
$title = 'Edit Artikel';
$description = 'Ubah artikel kamu disini.';
$tagModel = new TagModel;
$labels = $tagModel->paginate();
$articleModel = new PageModel;
$article = $articleModel->findPageById($id, 'article');

if(!$article) {
  abort(404);
}

// submit
if(is_method('POST')) {
  $model = new PageModel;
  $result = $model->update($id, $_POST+$_FILES, 'article');
}

require_once BASEPATH . '/views/layouts/header.php';

?>

<section class="content-wrapper">
  <?php require_once BASEPATH . '/views/partials/_alert.php' ?>
  <form action id="form-article" method="post" enctype="multipart/form-data">
    <div class="form-section">
      <label>Judul</label>
      <input type="text" class="form-el" name="title" value="<?= old_input('title') ? old_input('title') : $article->title ?>" />
    </div>
    <div class="form-section">
      <label>Permalink</label>
      <input type="text" name="slug" class="form-el" value="<?= old_input('slug') ? old_input('slug') : $article->slug ?>" />
    </div>
    <div class="form-section">
      <label>Thumbnail</label>
      <input type="file" name="thumbnail" class="form-el" />
    </div>
    <div class="form-section">
      <label>Label</label>
      <textarea class="form-el" name="labels"><?= old_input('labels') ? old_input('labels') : $article->labels . ', ' ?></textarea>
      <span class="help-block">Dipisahkan degnan karakter koma (,)</span>
      <a href="#labels" class="btn" data-toggle="look-label"
        >Lihat Label</a
      >
      <div id="labels" style="display: none">
        <?php foreach($labels as $idx => $row): ?>
          <a href="#" onclick="selectLabel(event, <?= $row->id ?>, true)"><?= $row->tag ?></a><?= (count($labels) > ++$idx) ? ', ' : '' ?>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="form-section">
      <label>Isi Artikel</label>
      <textarea name="content" id="content" class="form-el"><?= old_input('content') ? old_input('content') : $article->content ?></textarea>
    </div>
    <div class="form-section">
      <button class="btn">Publikasikan</button>
      <a href="<?= base_url('/?page=articles') ?>" class="btn">Kembali</a>
    </div>
  </form>
</section>

<div id="label-lists" class="label-lists"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?= base_url('/assets/plugins/summernote/dist/summernote-lite.js') ?>"></script>
<?php
require_once BASEPATH . '/views/articles/script.php';
require_once BASEPATH . '/views/layouts/footer.php';
?>