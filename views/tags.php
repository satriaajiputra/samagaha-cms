<?php
defined('BASEPATH') or die('No Script Kiddies Please!');

use Core\Models\TagModel;

//setting page
$title = 'Daftar Label';
$description = 'Manajemen label situs kamu disini, dari mulai menambah, mengubah, hingga menghapus';

//check if post for delete
if(is_method('POST')) {
  $model = new TagModel;
  $model->destroy($_POST['id']);
}

//labels
require_once BASEPATH . '/core/models/TagModel.php';
$model = new TagModel;
$labels = $model->paginate();

require_once BASEPATH . '/views/layouts/header.php';

?>

<section class="content-wrapper">
  <?php require_once BASEPATH . '/views/partials/_alert.php' ?>
  <a href="<?= base_url('/?page=tag-create') ?>" class="btn">Tambah Label</a>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Label</th>
          <th>Jumlah Artikel</th>
          <th width="170px">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($labels as $index => $row): ?>
        <tr>
          <td class="text-center"><?= ++$index ?></td>
          <td><?= $row->tag ?></td>
          <td><?= $row->used ?></td>
          <td class="text-center">
            <a href="<?= base_url('/?page=tag-edit&id='.$row->id) ?>" class="btn btn-sm">Sunting</a>
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