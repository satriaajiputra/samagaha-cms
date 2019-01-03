<?php
namespace Core\Models;

use PDO;
use Core\UploadImage;
use Core\DB\Connection;
use Core\Models\TagMode;
use Rakit\Validation\Validator;

class PageModel extends Connection {
  protected $table = 'pages';

  public function __construct()
  {
    parent::__construct();
  }

  public function findPage($by, $ref)
  {
    $query = $this->getConn()->prepare("SELECT * FROM $this->table WHERE $by = ?");
    $query->bindValue(1, $ref);
    $query->execute();
    return $query->fetch(PDO::FETCH_OBJ);
  }

  public function findPageById($id, $type = 'article')
  {
    $query = $this->getConn()->prepare("SELECT A.*, GROUP_CONCAT(C.tag) AS labels FROM pages AS A LEFT JOIN taggables AS B ON B.page_id = A.id INNER JOIN tags AS C ON C.id = B.tag_id WHERE A.page_type = ? AND A.id = ? GROUP BY A.id");
    $query->bindValue(1, $type);
    $query->bindValue(2, $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_OBJ);
  }

  public function paginate($type = 'article')
  {
    $query = $this->getConn()->prepare("SELECT A.*, GROUP_CONCAT(C.tag) AS labels FROM pages AS A LEFT JOIN taggables AS B ON B.page_id = A.id INNER JOIN tags AS C ON C.id = B.tag_id WHERE A.page_type = ? GROUP BY A.id");
    $query->bindValue(1, $type);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
  }

  public function store($data, $type = 'article')
  {
    $validator = $this->validator->make($data, [
      'title' => 'required|min:3|max:255',
      'slug' => 'required|min:3|max:255',
      'labels' => 'required',
      'thumbnail' => 'uploaded_file:0,10M,png,jpeg,jpg,gif',
      'content' => 'required|min:10'
    ]);

    $validator->validate();

    if($validator->fails()) {
      $errors = $validator->errors();
      store_flash_input();
      set_flash_message('warning', $errors->firstOfAll('<div>:message</div>'));
      redirect('/?page=' . $type . '-create');
    } else {
      $tagModel = new TagModel;

      $input = [
        'title' => $data['title'],
        'slug' => $data['slug'],
        'content' => $data['content'],
        'page_type' => $type,
      ];
      
      if(isset($data['thumbnail']['tmp_name'])) {
        $image = new UploadImage($data['thumbnail'], 'uploads/images/');
        if($image->upload()) {
          $uploadInfo = $image->uploadInfo();
        }
      }

      $input['thumbnail'] = isset($uploadInfo['full_path']) ? $uploadInfo['full_path'] : null;

      // create post
      $query = $this->getConn()->prepare('INSERT INTO pages (title, slug, thumbnail, content, page_type) VALUES (:title,:slug,:thumbnail,:content,:page_type)');
      foreach($input as $index => $row) {
        $query->bindValue(':'.$index, $row);
      }
      
      if(!$query->execute()) {
        store_flash_input();
        set_flash_message('warning', 'Gagal menambahkan artikel.');
        redirect('/?page=' . $type . '-create');
      }

      $pageId = $this->getConn()->lastInsertId();

      if($type == 'article') {
        $labelId = [];

        foreach(explode(',', $data['labels']) as $index => $row) {
          if(trim($row) == "") continue;
          $tag = trim($row);
          $label = $tagModel->findTag($tag);
          if($label) {
            $labelId[] = $label->id;
          } else {
            if($tagModel->insert($tag)) {
              $labelId[] = $this->getConn()->lastInsertId();
            }
          }
        }

        // fetch tag
        foreach($labelId as $row) {
          $query = $this->getConn()->prepare("INSERT INTO taggables (tag_id, page_id) VALUES (?,?)");
          $query->bindValue(1, $row);
          $query->bindValue(2, $pageId);
          $query->execute();
        }
      }

      set_flash_message('success', 'Berhasil mempublikasikan artikel.');
      redirect('/?page=' . $type);
    }
  }

  public function update($id, $data, $type = 'article')
  {
    $validator = $this->validator->make($data, [
      'title' => 'required|min:3|max:255',
      'slug' => 'required|min:3|max:255',
      'labels' => 'required',
      'thumbnail' => 'uploaded_file:0,10M,png,jpeg,jpg,gif',
      'content' => 'required|min:10'
    ]);

    $validator->validate();

    if($validator->fails()) {
      $errors = $validator->errors();
      store_flash_input();
      set_flash_message('warning', $errors->firstOfAll('<div>:message</div>'));
      redirect('/?page=' . $type . '-edit&id='. $id);
    } else {
      $tagModel = new TagModel;
      $page = $this->findPageById($id, $type);

      if(!$page) abort(404);

      $input = [
        'id' => $id,
        'title' => $data['title'],
        'slug' => $data['slug'],
        'content' => $data['content']
      ];
      
      if(file_exists($data['thumbnail']['tmp_name'])) {
        $image = new UploadImage($data['thumbnail'], 'uploads/images/');
        if($image->upload()) {
          if(file_exists($page->thumbnail)) {
            unlink($page->thumbnail);
          }
          $uploadInfo = $image->uploadInfo();
        }
      }

      $input['thumbnail'] = isset($uploadInfo['full_path']) ? $uploadInfo['full_path'] : $page->thumbnail;

      // create post
      $query = $this->getConn()->prepare('UPDATE pages SET title = :title, slug = :slug, thumbnail = :thumbnail, content = :content WHERE id = :id');
      foreach($input as $index => $row) {
        $query->bindValue(':'.$index, $row);
      }
      
      if(!$query->execute()) {
        store_flash_input();
        set_flash_message('warning', 'Gagal menyimpan artikel.');
        redirect('/?page=' . $type . '-edit&id=' . $id);
      }

      if($page->page_type == 'article') {
        $labelId = [];

        $query = $this->getConn()->prepare("DELETE FROM taggables WHERE page_id = ?");
        $query->bindValue(1, $page->id);
        $query->execute();

        foreach(explode(',', $data['labels']) as $index => $row) {
          if(trim($row) == "") continue;
          $tag = trim($row);
          $label = $tagModel->findTag($tag);

          if($label) {
            $labelId[] = $label->id;
          } else {
            if($tagModel->insert($tag)) {
              $labelId[] = $this->getConn()->lastInsertId();
            }
          }
        }

        // fetch tag
        foreach($labelId as $row) {
          $query = $this->getConn()->prepare("INSERT INTO taggables (tag_id, page_id) VALUES (?,?)");
          $query->bindValue(1, $row);
          $query->bindValue(2, $page->id);
          $query->execute();
        }
      }

      set_flash_message('success', 'Berhasil menyimpan artikel.');
      redirect('/?page=' . $type . '-edit&id=' . $id);
    }
  }
}