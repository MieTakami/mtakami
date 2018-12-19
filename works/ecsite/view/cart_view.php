<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ショッピングカートページ</title>
  <link rel="stylesheet" href="css/reset.css" type="text/css">
  <link type="text/css" rel="stylesheet" href="./css/common.css">
</head>
<body>
  <header>
    <div class="header-box">
      <a href="./top.php">
        <img class="logo" src="./img/logo/logo.png" alt="LaLa SHOP">
      </a>
      <div class="header_manu">
          <a class="menu" href="./logout.php">ログアウト</a>
          <a href="./cart.php" class="cart"></a>
          <p class="menu">ユーザー名：<?php print entity_str($user_name); ?></p>
      </div>
    </div>
  </header>
  <div class="content">
    <h1 class="title">ショッピングカート</h1>
<?php if(count($err_msg) === 0 ){ ?>
           <p><?php print entity_str($message); ?></p>
<?php
       }else{ 
           foreach($err_msg as $value){ ?>
           <p class="err-msg"><?php print entity_str($value); ?></p>
<?php       } 
       } ?>

    <div class="cart-list-title">
      <span class="cart-list-price">価格</span>
      <span class="cart-list-num">数量</span>
    </div>
    <ul class="cart-list">
<?php
    foreach($cart_list as $value){ ?>
        <li>
            <div class="cart-item">
              <img class="cart-item-img" src="./img/<?php print entity_str($value['img']); ?>">
              <span class="cart-item-name"><?php print entity_str($value['name']); ?></span>
              <form class="cart-item-del" action="./cart.php" method="post">
                <input type="submit" value="削除">
                <input type="hidden" name="cart_id" value="<?php print entity_str($value['cart_id']); ?>">
                <input type="hidden" name="sql_kind" value="delete_cart">
              </form>
              <span class="cart-item-price">¥<?php print entity_str($value['price']); ?></span>
              <form class="form_select_amount" id="form_select_amount<?php print entity_str($value['id']); ?>" action="./cart.php" method="post">
                <input type="text" class="cart-item-num2" min="0" name="amount" value="<?php print entity_str($value['amount']); ?>">個&nbsp;<input type="submit" value="変更する">
                <input type="hidden" name="cart_id" value="<?php print entity_str($value['cart_id']); ?>">
                <input type="hidden" name="item_id" value="<?php print entity_str($value['id']); ?>">
                <input type="hidden" name="sql_kind" value="change_cart">
              </form>
            </div>
        </li>
<?php
    }
?>
    </ul>
    <div class="buy-sum-box">
      <span class="buy-sum-title">合計</span>
      <span class="buy-sum-price">
<?php   if($amount_total[0]['amount_total'] > 0){
            print '¥'.entity_str($amount_total[0]['amount_total']);
        }else{
            print '¥0';
        }
?>
      </span>
    </div>
    <div>
      <form action="finish.php"  method="post">
        <input class="buy-btn" type="submit" value="購入する">
        <input type="hidden" name="sql_kind" value="buy_cart">
      </form>
    </div>
  </div>
</body>
</html>
