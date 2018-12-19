<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>商品一覧ページ</title>
  <link type="text/css" rel="stylesheet" href="css/common.css">
</head>
<body>
  <header>
    <div class="header-box">
      <a href="./top.php">
        <img class="logo" src="./img/logo/logo.png" alt="LaLaSHOP">
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
<?php      foreach($message as $value){?> 
           <p><?php print entity_str($value); ?></p>
<?php      } 

           foreach($err_msg as $value){ ?>
           <p><?php print entity_str($value); ?></p>
            
<?php       }  ?>
    <ul class="item-list">
<?php
    foreach($cart_data as $value){ ?>
      <li class="item_list_box">
        <div class="item">
          <form method="post">
            <img class="item-img" src="./img/<?php print entity_str($value['img']); ?>" >
            <div class="item-info clearfix">
              <span class="item-name"><?php print entity_str($value['name']);?></span>
              <span class="item-price">¥<?php print entity_str($value['price']); ?></span>
            </div>
<?php       if($value['stock'] > 0){ ?>
                <input class="cart-btn" type="submit" value="カートに入れる">
                <input type="hidden" name="item_id" value="<?php print entity_str($value['id']); ?>">
                <input type="hidden" name="sql_kind" value="insert_cart">
<?php
            }else{ ?>
                <span class="sold-out">売り切れ</span>
<?php
            } ?>
            
          </form>
        </div>
      </li>
<?php
    }
?>
    </ul>
  </div>
</body>
</html>