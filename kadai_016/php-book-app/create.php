<?php
  // データベース接続とSELECTのためのReadクラスを設定
  require_once 'db.php';
  class Create extends DB {
    private $pdo;

    public function __construst() {
      parent::__construst(); //親のコンストラクタの呼び出し
    }

    // submitパラメータの値が存在するときの処理
    public function submit() {
      if(isset($_POST['submit'])) {
        try {
          $this->pdo = new PDO($this->dsn, $this->user, $this->pass);

          // 動的に変わる値をプレースホルダに置き換えたINSERT文を用意
          $sql = '
            INSERT INTO books (book_code, book_name, price, stock_quantity, genre_code) 
            VALUES (:book_code, :book_name, :price, :stock_quantity, :genre_code)
          ';
          $stmt = $this->pdo->prepare($sql);

          // bindValue()メソッドでバインド
          $stmt->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
          $stmt->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
          $stmt->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
          $stmt->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
          $stmt->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);

          // 実行
          $stmt->execute();

          // 追加件数を取得
          $count = $stmt->rowCount();
          $message = "商品を{$count}件登録しました";

          // 書籍一覧ページにリダイレクトさせる
          header("Location: read.php?message={$message}");
        } catch(PDOException $e) {
          exit($e->getMessage());
        }
      }
    }

    // genre_codeを読み込む
    public function genres() {
      try {
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
      
        // SQL文
        $sql = 'SELECT genre_code FROM genres';

        // SQL文の実行
        $stmt = $this->pdo->query($sql);
      
        // 実行結果を配列で取得
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
      } catch(PDOException $e) {
        exit($e->getMessage());
      }
    }
  }

  // Createのインスタンス化とsubmit()の実行とgenres()の実行($genresの抽出)
  $create = new Create;
  $create->submit();
  $genres = $create->genres();

  // header.phpとfooter.phpの読み込み
  require_once 'header.php';
  require_once 'footer.php';

  // ヘッダー部分の呼び出し
  header_html();
?>

<!-- HTML部分 -->
  <article class="registration">
    <h1>書籍登録</h1>
    <div class="back">
      <a href="read.php" class="btn">&lt; 戻る</a>
    </div>
    <form action="create.php" method="post" class="registration-form">
      <div>
        <label for="book_code">書籍コード</label>
        <input type="number" id="product_code" name="book_code" min="0" max="100000000" required>

        <label for="book_name">書籍名</label>
        <input type="text" id="product_name" name="book_name" maxlength="50" required>

        <label for="price">単価</label>
        <input type="number" id="price" name="price" min="0" max="100000000" required>

        <label for="stock_quantity">在庫数</label>
        <input type="number" id="stock_quantity" name="stock_quantity" min="0" max="100000000" required>

        <label for="genre_code">ジャンルコード</label>
        <select id="vendor_code" name="genre_code" required>
          <option disable selected value>選択してください</option>
          <?php
            // 配列の中身を順番に取り出し、セレクトボックスの選択肢として出力する
            foreach($genres as $genre) {
              echo "<option value='{$genre}'>{$genre}</option>";
            }
          ?>
        </select>
      </div>
      <button type="submit" class="submit-btn" name="submit" value="create">登録</button>
    </form>
  </article>
<!-- HTML部分 -->

<?php footer_html(); ?>