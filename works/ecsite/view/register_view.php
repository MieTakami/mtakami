<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ユーザ登録ページ</title>
  <link type="text/css" rel="stylesheet" href="./css/common.css">
</head>
<body>
  <header>
    <div class="header-box">
        <div class="header-box">
          <a href="./top.php">
            <img class="logo" src="./img/logo/logo.png" alt="LaLa SHOP">
          </a>
        </div>
  </header>
  <div class="content">
    <div class="register">
<?php
      foreach($err_msg as $value){ ?>
          <p><?php print entity_str($value); ?></P>
<?php
      }
      if(count($message) !== 0){ ?>
          <p><?php print entity_str($message); ?></p>
<?php
      }
?>
      <form method="post" action="./register.php">
        <div>ユーザー名：<input type="text" name="user_name" placeholder="ユーザー名"></div>
        <div>パスワード：<input type="password" name="password" placeholder="パスワード">
        <div><input type="submit" value="ユーザーを新規作成する">
      </form>
      <div class="login-link"><a href="./login.php">ログインページに移動する</a></div>
    </div>
  </div>
</body>
</html>