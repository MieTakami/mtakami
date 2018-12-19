<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <link type="text/css" rel="stylesheet" href="css/common.css">
   <title>ログイン</title>
</head>
<body>
    <header>
        <div class="header-box">
          <a href="./top.php">
            <img class="logo" src="./img/logo/logo.png" alt="LaLa SHOP">
          </a>
        </div>
    </header>
    <div class="content">
        <div class="login">
           <form action="login.php" method="post">
               <div><input type="text" id="user_name" name="user_name" placeholder="ユーザー名"></div>
               <div><input type="password" id="passwd" name="password" placeholder="パスワード"></div>
               <div><input type="submit" value="ログイン"></div>
           </form>
<?php       if ($login_err_flag === TRUE) { ?>
            <p>ユーザー名又はパスワードが違います</p>
<?php } ?>
            <div class="account-create">
                <a href="./register.php">ユーザーの新規作成</a>
            </div>
        </div>
    </div>
</body>
</html>