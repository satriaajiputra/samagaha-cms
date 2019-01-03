<?php
namespace Core;

class UploadImage {
  protected $allowedExtension = ['jpg','jpeg','png','gif'];
  protected $uploadPath = 'uploads/';
  private $file = null;

  public function __construct($file, $path = false)
  {
    $this->file = $file;
    if($path) $this->uploadPath = $path;
    $this->extension = strtolower(
      pathinfo($this->file['name'], PATHINFO_EXTENSION)
    );

    $this->uploadFilePath = $this->uploadPath . date('Y/m/d') . time() . '_' . md5($this->file['name']) . '.' . $this->extension;
  }

  public function makeDir($path)
  {
    if(!file_exists($path) && !is_dir($path)) {
      return mkdir($path, 0777, true);
    }
    return true;
  }

  public function isImage($file)
  {
    return getimagesize($file);
  }

  public function isAllowed($extension)
  {
    return in_array($extension, $this->allowedExtension);
  }

  public function uploadInfo()
  {
    return [
      'path' => dirname($this->uploadFilePath),
      'name' => basename($this->uploadFilePath),
      'size' => $this->file['size'],
      'extension' => $this->extension,
      'full_path' => $this->uploadFilePath
    ];
  }

  public function upload()
  {
    $readyToUpload = true;
    $message = [];

    if(!$this->isImage($this->file['tmp_name'])) {
      $readyToUpload = false;
      $message[] = 'File yang diunggah bukan berupa gambar';
    }

    if(file_exists($this->uploadFilePath)) {
      $readyToUpload = false;
      $message[] = 'File yang akan di unggah sudah ada dalam server';
    }

    if($this->file['size'] > 10000000) {
      $readyToUpload = false;
      $message[] = 'Maksimal ukuran file adalah 10MB';
    }

    if(!$this->isAllowed($this->extension)) {
      $readyToUpload = false;
      $message[] = 'Ekstensi file yang diizinkan: ' . implode(', ', $this->allowedExtension);
    }

    if(!$this->makeDir(dirname($this->uploadFilePath))) {
      $readyToUpload = false;
      $message[] = 'Gagal membuat folder';
    }

    if($readyToUpload) {
      if(!move_uploaded_file($this->file['tmp_name'], $this->uploadFilePath)) {
        $readyToUpload = false;
        $message[] = 'Gagal mengunggah file';
      } else {
        return true;
      }
    }

    set_flash_message('warning', $message);
    return false;
  }
}