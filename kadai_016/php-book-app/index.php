<?php
  // header.phpの呼び出しと共通部分のecho
  require_once 'header.php';
  require_once 'footer.php';
  header_html();
?>
    <article class="home">
      <h1>書籍管理アプリ</h1>
      <p>『PHPとデータベースを連携しよう』成果物</p>
      <a href="read.php" class="btn">書籍一覧</a>
    </article>
<?php footer_html(); ?>