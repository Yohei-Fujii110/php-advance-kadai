<?php
  // データベース接続とSELECTのためのReadクラスを設定
  require_once 'db.php';
  class Delete extends DB {
    private $pdo;

    public function __construst() {
      parent::__construst(); //親のコンストラクタの呼び出し
    }


    // delete処理
    public function delete() {
      if(isset($_GET['id'])) {
        try {
          $this->pdo = new PDO($this->dsn, $this->user, $this->pass);

          // idカラムの値をプレースホルダに置き換えたSELECT文を用意
          $sql = 'DELETE FROM books WHERE id = :id';
          $stmt = $this->pdo->prepare($sql);

          // bindValue()メソッドでバインド
          $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

          // 実行
          $stmt->execute();

          // idパラメータの値と同じidのデータが存在しない場合はエラーメッセージを表示して処理を終了する
          if($book === FALSE) {
            exit('idパラメータの値が不正です。');
          }

          // 削除件数を取得
          $count = $stmt->rowCount();
          $message = "商品を{$count}件削除しました";

          // 書籍一覧ページにリダイレクトさせる
          header("Location: read.php?message={$message}");

        } catch(PDOException $e) {
          exit($e->getMessage());
        }
      }
    }
  }

  // Deleteのインスタンス化とdelete()の実行
  $delete = new Delete;
  $delete->delete();
?>