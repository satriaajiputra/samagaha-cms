<?php

class Connection {
  private $_conn = null;
  protected $driver, $host, $dbname, $username, $password;

  public function __construct()
  {
    $this->driver   = config('database.type');
    $this->host     = config('database.host');
    $this->dbname   = config('database.dbname');
    $this->username = config('database.username');
    $this->password = config('database.password');

    try {
      $this->_conn = new PDO(
        sprintf("%s: host=%s;dbname=%s",
          $this->driver, $this->host, $this->dbname
        ),
        $this->username, $this->password
      );
    } catch(Exception $error) {
      die($error->getMessage());
    }
  }

  public function getConn()
  {
    return $this->_conn;
  }
}