<?php

ini_set('display_errors', 'on');
define('BASEPATH', __DIR__);

session_start();

require_once BASEPATH . '/vendor/autoload.php';
require_once BASEPATH . '/core/init.php';

if(isset($_GET['page'])):
switch($_GET['page']) {
  case 'tags':
    require_once BASEPATH . '/views/tags.php';
    break;
  case 'tag-create':
    require_once BASEPATH . '/views/tags/tag-add.php';
    break;
  case 'tag-edit':
    require_once BASEPATH . '/views/tags/tag-edit.php';
    break;
  case 'article-create':
    require_once BASEPATH . '/views/articles/add.php';
    break;
}

endif;

unset($_SESSION['flash_message'], $_SESSION['flash_input']);