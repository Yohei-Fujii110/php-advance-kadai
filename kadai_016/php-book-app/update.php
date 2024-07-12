<?php
  // データベース接続とSELECTのためのReadクラスを設定
  require_once 'db.php';
  class Update extends DB {
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
            UPDATE books
            SET book_code = :book_code,
            book_name = :book_name,
            price = :price,
            stock_quantity = :stock_quantity,
            genre_code = :genre_code
            WHERE id = :id
          ';
          $stmt = $this->pdo->prepare($sql);

          // bindValue()メソッドでバインド
          $stmt->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
          $stmt->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
          $stmt->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
          $stmt->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
          $stmt->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
          $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

          // 実行
          $stmt->execute();

          // 追加件数を取得
          $count = $stmt->rowCount();
          $message = "商品を{$count}件編集しました";

          // 書籍一覧ページにリダイレクトさせる
          header("Location: read.php?message={$message}");
        } catch(PDOException $e) {
          exit($e->getMessage());
        }
      }
    }


    // genre_codeの取得処理
    public function update() {
      if(isset($_GET['id'])) {
        try {
          $this->pdo = new PDO($this->dsn, $this->user, $this->pass);

          // idカラムの値をプレースホルダに置き換えたSELECT文を用意
          $sql = 'SELECT * FROM books WHERE id = :id';
          $stmt = $this->pdo->prepare($sql);

          // bindValue()メソッドでバインド
          $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

          // 実行
          $stmt->execute();

          // 実行結果を配列で取得
          $book = $stmt->fetch(PDO::FETCH_ASSOC);

          // idパラメータの値と同じidのデータが存在しない場合はエラーメッセージを表示して処理を終了する
          if($book === FALSE) {
            exit('idパラメータの値が不正です。');
          }

          // genresテーブルからgenre_codeを取得するためのSELECT文を用意
          $sql2 = 'SELECT genre_code FROM genres';

          // 実行
          $stmt2 = $this->pdo->query($sql2);

          // 実行結果を配列で取得
          $genres = $stmt2->fetchAll(PDO::FETCH_COLUMN);

          // $bookと$genresを配列として取得
          return ['book' => $book, 'genres' => $genres];
        } catch(PDOException $e) {
          exit($e->getMessage());
        }
      }
    }
  }

  // Updateのインスタンス化とsubmit(), update()の実行と$book, $genresの抽出
  $update = new Update;
  $update->submit();
  $result = $update->update();
  if($result) {
    $book = $result['book'];
    $genres = $result['genres'];
  }

  // header.phpとfooter.phpの読み込み
  require_once 'header.php';
  require_once 'footer.php';

  // ヘッダー部分の呼び出し
  header_html();
?>

<!-- HTML部分 -->
  <article class="registration">
    <h1>書籍編集</h1>
    <div class="back">
      <a href="read.php" class="btn">&lt; 戻る</a>
    </div>
    <form action="update.php?id=<?= $_GET['id']; ?>" method="post" class="registration-form">
      <div>
        <label for="book_code">書籍コード</label>
        <input type="number" id="product_code" name="book_code" value="<?= $book['book_code'] ?>" min="0" max="100000000" required>

        <label for="book_name">書籍名</label>
        <input type="text" id="product_name" name="book_name" value="<?= $book['book_name'] ?>" maxlength="50" required>

        <label for="price">単価</label>
        <input type="number" id="price" name="price" value="<?= $book['price'] ?>" min="0" max="100000000" required>

        <label for="stock_quantity">在庫数</label>
        <input type="number" id="stock_quantity" name="stock_quantity" value="<?= $book['stock_quantity'] ?>" min="0" max="100000000" required>

        <label for="genre_code">ジャンルコード</label>
        <select id="vendor_code" name="genre_code" required>
          <option disable selected value>選択してください</option>
          <?php
            // 配列の中身を順番に取り出し、セレクトボックスの選択肢として出力する
            foreach($genres as $genre) {
              // もし$genre_codeが書籍のジャンルコードと一致していればselected属性を付けて初期値にする
              if($genre === $book['genre_code']) {
                echo "<option value='{$genre}' selected>{$genre}</option>";
              } else {
                echo "<option value='{$genre}'>{$genre}</option>";
              }
            }
          ?>
        </select>
      </div>
      <button type="submit" class="submit-btn" name="submit" value="update">更新</button>
    </form>
  </article>
<!-- HTML部分 -->

<?php footer_html(); ?>