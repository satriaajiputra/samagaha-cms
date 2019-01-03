<?php

ini_set('display_errors', 'on');
define('BASEPATH', __DIR__);

session_start();

require_once BASEPATH . '/vendor/autoload.php';
require_once BASEPATH . '/core/init.php';

if(isset($_GET['page'])):
  if(isset($_SESSION['user'])):
    switch($_GET['page']) {
      case 'dashboard':
        require_once BASEPATH . '/views/dashboard.php';
        break;
      case 'tags':
        require_once BASEPATH . '/views/tags.php';
        break;
      case 'tag-create':
        require_once BASEPATH . '/views/tags/tag-add.php';
        break;
      case 'tag-edit':
        require_once BASEPATH . '/views/tags/tag-edit.php';
        break;
      case 'articles':
        require_once BASEPATH . '/views/articles.php';
        break;
      case 'article-create':
        require_once BASEPATH . '/views/articles/add.php';
        break;
      case 'article-edit':
        require_once BASEPATH . '/views/articles/edit.php';
        break;
      case 'logout':
        unset($_SESSION['user']);
        set_flash_message('success', 'Berhasil keluar dari akun.');
        redirect('/?page=login');
        break;
      default:
        abort(404);
        break;
    }
  else:
    switch($_GET['page']) {
      case 'login':
        require_once BASEPATH . '/views/login.php';
        break;
      default:
        abort(404);
        break;
    }
  endif;
elseif(isset($_GET['api'])):
  switch($_GET['api']) {
    case 'articles':
    break;
  }
endif;

unset($_SESSION['flash_message'], $_SESSION['flash_input']);