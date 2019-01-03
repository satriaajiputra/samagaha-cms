<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= isset($title) ? $title . ' - ' : '' ?><?= config('app.name') ?></title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link
      rel="stylesheet"
      href="<?= base_url('/assets/plugins/summernote/dist/summernote-lite.css') ?>"
    />
    <link rel="stylesheet" href="<?= base_url('/assets/css/style.css') ?>" />
  </head>
  <body>
    <div class="container">
      <header>
        <h1><?= isset($title) ? $title : config('app.name') ?></h1>
        <p><?= isset($description) ? $description : 'Manajemen situs kamu disini, dari mulai label, artikel, halaman, dan
          pengaturan.' ?></p>
        <nav>
        <?php if(isset($_SESSION['user'])): ?>
          <ul>
            <li><a href="<?= base_url('/?page=dashboard') ?>">Dasbor</a></li>
            <li><a href="<?= base_url('/?page=tags') ?>">Label</a></li>
            <li><a href="<?= base_url('/?page=articles') ?>">Artikel</a></li>
            <li><a href="<?= base_url('/?page=pages') ?>">Halaman</a></li>
            <li><a href="<?= base_url('/?page=setting') ?>">Pengaturan</a></li>
            <li><a href="<?= base_url('/?page=logout') ?>">Keluar</a></li>
          </ul>
        <?php else: ?>
          <ul>
            <li><a href="<?= base_url('/?page=login') ?>">Masuk Akun</a></li>
          </ul>
        <?php endif; ?>
        </nav>
      </header>