<?php
// データベース接続
class DB {
  protected $dsn;
  protected $user;
  protected $pass;

  public function __construct() {
    $this->dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
    $this->user = 'root';
    $this->pass = '';
  }
}
?>
