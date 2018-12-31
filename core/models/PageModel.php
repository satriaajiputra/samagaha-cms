<?php
namespace Core\Models;

use PDO;
use Core\DB\Connection;
use Rakit\Validation\Validator;

class PageModel extends Connection {
  protected $table = 'pages';

  public function __construct()
  {
    parent::__construct();
  }

  public function findPage($by, $ref)
  {
    $query = $this->getConn()->prepare("SELECT * FROM $this->table WHERE ? = ?");
    $query->bindParam(1, $by);
    $query->bindValue(2, $ref);
    $query->execute();
    return $query->fetch(PDO::FETCH_OBJ);
  }

  public function store($data)
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
      redirect('/?page=article-create');
    }
  }
}