<?php
  function header_html() {
    echo <<< EOM
      <!DOCTYPE html>
      <html lang="ja">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>書籍管理アプリ</title>
        <link rel="stylesheet" href="css/style.css">

        <!-- Google Fontsの読み込み -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
      </head>

      <body>
        <header>
          <nav>
            <a href="index.php">書籍管理アプリ</a>
          </nav>
        </header>
        <main>
    EOM;
  }
?>