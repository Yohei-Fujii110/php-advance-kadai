<?php
  // データベース接続とSELECTのためのReadクラスを設定
  require_once 'db.php';
  class Read extends DB {
    private $pdo;

    public function __construst() {
      parent::__construst(); //親のコンストラクタの呼び出し
    }

    public function books() {
      try {
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
      
        // orderパラメータの値が存在すれば（並び替えボタンを押したとき）、その値を変数$orderに代入する
        if(isset($_GET['order'])) {
          $order = $_GET['order'];
        } else {
          $order = NULL;
        }
        // keywordパラメータの値が存在すれば（商品名を検索したとき）、その値を変数$keywordに代入する
        if(isset($_GET['keyword'])) {
          $keyword = $_GET['keyword'];
        } else {
          $keyword = NULL;
        }

        // SQL文
        if($order === 'desc') {
          $sql = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at DESC';
        } else {
          $sql = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at ASC';
        }

        // SQL文の準備
        $stmt = $this->pdo->prepare($sql);
        $partial_match = "%{$keyword}%";
        $stmt->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

        // 実行
        $stmt->execute();
      
        // 実行結果を配列で取得
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch(PDOException $e) {
        exit($e->getMessage());
      }
    }
  }

  // Readのインスタンス化とbooks()の実行($booksの抽出)
  $read = new Read;
  $books = $read->books();

  // $search_wordと$ordeRの抽出
  if(isset($_GET['keyword'])) {
    $search_word = $_GET['keyword'];
  } else {
    $search_word = NULL;
  }
  if(isset($_GET['order'])) {
    $ordeR = $_GET['order'];
  } else {
    $ordeR = NULL;
  }

  // header.phpとfooter.phpの読み込み
  require_once 'header.php';
  require_once 'footer.php';

  // ヘッダー部分の呼び出し
  header_html();
?>

<!-- HTML部分 -->
<article class="products">
  <h1>書籍一覧</h1>
  <?php
    // 書籍登録・編集・削除後、messageパラメータを受け取っていれば表示
    if(isset($_GET['message'])) {
      echo "<p class='success'>{$_GET['message']}</p>";
    }
  ?>
  <div class="products-ui">
    <div>
      <!-- ここに並べ替えボタンと検索ボックスを作成する -->
      <a href="read.php?order=desc&keyword=<?= $search_word ?>"><img src="images/desc.png" alt="降順に並び替え" class="sort-img"></a>
      <a href="read.php?order=asc&keyword=<?= $search_word ?>"><img src="images/asc.png" alt="昇順に並び替え" class="sort-img"></a>
      <form action="read.php" method="get" class="search-form">
        <input type="hidden" name="order" value="<?= $ordeR ?>">
        <input type="text" class="search-box" placeholder="書籍名で検索" name="keyword" value="<?= $search_word; ?>">
      </form>
    </div>
    <a href="create.php" class="btn">書籍登録</a>
  </div>
  <table class="products-table">
    <tr>
      <th>書籍コード</th>
      <th>書籍名</th>
      <th>単価</th>
      <th>在庫数</th>
      <th>ジャンルコード</th>
      <th>編集</th>
      <th>削除</th>
    </tr>
    <?php
      // 配列の中身を順番に取り出し、表形式で出力
      foreach($books as $book) {
        echo <<<EOM
          <tr>
          <td>{$book['book_code']}</td>
          <td>{$book['book_name']}</td>
          <td>{$book['price']}</td>
          <td>{$book['stock_quantity']}</td>
          <td>{$book['genre_code']}</td>
          <td><a href='update.php?id={$book['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
          <td><a href='delete.php?id={$book['id']}'><img src='images/delete.png' alt='削除' class='delete-icon'></a></td>         
          </tr>
        EOM;
      }
    ?>
  </table>
</article>
<!-- HTML部分 -->

<?php footer_html(); ?>