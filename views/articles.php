<?php
defined('BASEPATH') or die('No Script Kiddies Please!');

use Core\Models\PageModel;

//setting page
$title = 'Daftar Artikel';
$description = 'Manajemen artikel kamu disini, dari mulai menambah, mengubah, hingga menghapus';

//check if post for delete
if(is_method('POST')) {
  $model = new PageModel;
  $model->destroy($_POST['id']);
}

//articles
$model = new PageModel;
$articles = $model->paginate('article');

require_once BASEPATH . '/views/layouts/header.php';

?>

<section class="content-wrapper">
  <?php require_once BASEPATH . '/views/partials/_alert.php' ?>
  <a href="<?= base_url('/?page=article-create') ?>" class="btn">Tambah Artikel</a>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Judul</th>
          <th>Label</th>
          <th>Dilihat</th>
          <th width="170px">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($articles as $index => $row): ?>
        <tr>
          <td class="text-center"><?= ++$index ?></td>
          <td><?= $row->title ?></td>
          <td><?= $row->labels ?></td>
          <td><?= !$row->views ? 0 : $row->views ?></td>
          <td class="text-center">
            <a href="<?= base_url('/?page=article-edit&id='.$row->id) ?>" class="btn btn-sm">Sunting</a>
            <form action method="post" style="display: inline">
              <input type="hidden" name="id" value="<?= $row->id ?>">
              <button class="btn btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- <div class="d-flex justify-center">
    <ul class="pagination">
      <li><a href="#">&laquo;</a></li>
      <li class="active"><a href="#">1</a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">...</a></li>
      <li><a href="#">10</a></li>
      <li><a href="#">&raquo;</a></li>
    </ul>
  </div> -->
</section>

<?php
require_once BASEPATH . '/views/layouts/footer.php';
?>