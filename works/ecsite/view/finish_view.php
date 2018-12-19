<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>購入完了ページ</title>
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
          <a href="./cart.php" class="cart">
              <img class="cart_icon" src="./img/logo/cart.png" alt="カート">
          </a>
          <p class="menu">ユーザー名：<?php print entity_str($user_name); ?></p>
      </div>
    </div>
  </header>
  <div class="content">
<?php if(count($err_msg) !== 0 ){
           foreach($err_msg as $value){ ?>
           <p class="err-msg"><?php print entity_str($value); ?></p>
<?php       } 
       } ?>
    <div class="finish-msg">ご購入ありがとうございました。</div>
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
                  <span class="cart-item-price">¥<?php print entity_str($value['price']); ?></span>
                  <span class="finish-item-price"><?php print entity_str($value['amount']); ?></span>
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
  </div>
</body>
</html>
