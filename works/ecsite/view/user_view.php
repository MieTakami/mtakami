<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ユーザ管理ページ</title>
  <link type="text/css" rel="stylesheet" href="./css/common.css">
</head>
<body>
    <main>
      <h1>CodeSHOP 管理ページ</h1>
      <div>
        <a class="nemu" href="./logout.php">ログアウト</a>
        <a href="./admin.php">商品管理ページ</a>
      </div>
      <h2>ユーザ情報一覧</h2>
      <table>
        <tr>
          <th>ユーザID</th>
          <th>登録日</th>
        </tr>
<?php
        foreach($user_data as $value){ ?>
        <tr>
          <td class="name_width"><?php print entity_str($value['user_name']); ?></td>
          <td ><?php print entity_str($value['created_date']); ?></td>
        </tr>
<?php
        } ?>
      </table>
   </main>
</body>
</html>
