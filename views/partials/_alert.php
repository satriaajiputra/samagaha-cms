<?php

if(isset($_SESSION['flash_message'])):
  $flashMessage = $_SESSION['flash_message'];
?>

<div class="alert<?= isset($flashMessage['status']) ? ' alert-' . $flashMessage['status'] : '' ?>">
  <?= $flashMessage['message'] ?>
</div>

<?php
endif;