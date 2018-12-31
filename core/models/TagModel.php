<?php
namespace Core\Models;

use PDO;
use Core\DB\Connection;

class TagModel extends Connection {
  protected $table = 'tags';

  public function store(array $data)
  {
    if(!isset($data['tags']) || trim($data['tags']) == '') {
      set_flash_message('warning', 'Pastikan form isian label sudah terisi.');
      redirect('?page=tag-create', 200);
    }

    foreach(explode(',', $data['tags']) as $row) {
      $tag = trim($row);
      if(!$this->findTag($tag)) {
        $query = $this->getConn()->prepare("INSERT INTO $this->table (tag) VALUES (?)");
        $query->bindValue(1, $tag);
        $query->execute();
      }
    }

    set_flash_message('success', 'Berhasil menambahkan label.');
    redirect('?page=tags', 200);
  }

  public function update(int $id, array $data)
  {
    if(!isset($data['tags']) || trim($data['tags']) == '') {
      set_flash_message('warning', 'Pastikan form isian label sudah terisi.');
      redirect('?page=tag-edit&id=' . $id, 200);
    }

    $tag = $this->findTagById($id);
    $otherTag = $this->findTag($data['tags']);

    if($tag) {
      if($tag->tag == $otherTag->tag || !$otherTag) {
        $query = $this->getConn()->prepare("UPDATE $this->table SET tag = ? WHERE id = ?");
        $query->bindValue(1, $data['tags']);
        $query->bindValue(2, $id);
        $query->execute();
      }

      set_flash_message('success', 'Berhasil menyimpan label.');
      redirect('?page=tag-edit&id=' . $id, 200);
    } else {
      abort(404);
    }
  }

  public function destroy(int $id)
  {
    $tag = $this->findTagById($id);
    if(!$tag) abort(404);
    $query = $this->getConn()->prepare("DELETE FROM $this->table WHERE id = ?");
    $query->bindValue(1, $id);
    $query->execute();
    set_flash_message('success', 'Berhasil menghapus label <strong>' . $tag->tag . '</strong>.');
    redirect('/?page=tags', 200);
  }

  public function findTag(string $tag)
  {
    $query = $this->getConn()->prepare("SELECT * FROM $this->table WHERE tag = ?");
    $query->bindValue(1, $tag);
    $query->execute();
    return $query->fetchObject();
  }

  public function findTagById(int $id)
  {
    $query = $this->getConn()->prepare("SELECT * FROM $this->table WHERE id = ?");
    $query->bindValue(1, $id);
    $query->execute();
    return $query->fetchObject();
  }

  public function paginate(int $perpage = 10)
  {
    $data = $this->getConn()->prepare("SELECT A.*, (SELECT count(id) FROM taggables WHERE tag_id = A.id) as used FROM $this->table AS A");
    $data->execute();
    return $data->fetchAll(PDO::FETCH_OBJ);
  }
}