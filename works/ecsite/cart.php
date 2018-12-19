<?php
require_once 'conf/const.php';
require_once 'model/function.php';

$user_name = '';
$user_id = '';
$item_id = 0;
$amount = 0;
$date = date('Y-m-d H:i:s');
$cart_list = array();
$amount_total = '';//合計金額
$err_msg = array();
$message = '';
$cart_item_id_list = array();
$cart_item_stock = 0;
$cart_item_stock_list = array();
$item_name = '';
$item_stock_data = array();
$action_url = './cart.php';

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: login.php');
    exit;
}

// コネクション取得
if ($link = get_db_connect()) {

   // 文字コードセット
   mysqli_set_charset($link, 'UTF8');
   
   //ユーザー名取得
   $sql = "SELECT id, user_name FROM user_table WHERE id ={$user_id}";
   $user_data = get_as_array($link, $sql);
   $user_name = $user_data[0]['user_name'];

    if (get_request_method() === 'POST') {
       // POST値取得
        $sql_kind = get_post_data('sql_kind');
        $cart_id = get_post_data('cart_id');
        $amount = get_post_data('amount');
        $item_id = get_post_data('item_id');
   
       if($sql_kind === 'delete_cart'){
           //カートから商品削除
           $sql = "DELETE FROM cart WHERE cart_id = {$cart_id}";
           if(!update_db($link, $sql)){
               $err_msg[] = 'カートから削除出来ませんでした';
           }else{
               $message = 'カートから削除しました。';
           }
       }else if($sql_kind === 'change_cart'){
            //個数が半角英数字でなかったら,もしくは空白ならエラー
            if(((preg_match("/^[0-9]+$/",$amount) !== 1)) || (empty($amount) === TRUE)){
                $err_msg[] = '個数は1以上の半角数字を入力してください';
            }
            //在庫数オーバーの場合エラー
                //該当のアイテムIDの名前と在庫数取得
                $sql = "SELECT s.item_id,i.name, s.stock FROM item_table AS i JOIN item_stock_table AS s 
                ON i.id =s.item_id WHERE s.item_id = {$item_id}";
                $cart_item_stock_list = get_as_array($link, $sql);
                //個数が変更されたアイテムの現在の在庫数
                $cart_item_stock = $cart_item_stock_list[0]['stock'];
                //個数が変更されたアイテムの名前
                $item_name = $cart_item_stock_list[0]['name'];
                
                if($cart_item_stock < $amount){
                    $err_msg[] = $item_name.'は残り'.$cart_item_stock.'個です。';
                }
                
               if(count($err_msg) === 0){
                    //カートから個数変更
                   $sql = "UPDATE cart SET amount = {$amount} ,updated_date = '{$date}' WHERE cart_id = {$cart_id}";
                   if (mysqli_query($link, $sql) === TRUE){
                       $message = '更新しました。';
                    }else{
                        $err_msg[] = '個数変更出来ませんでした';
                    }
               }
       }else if($sql_kind === 'buy_cart'){
           //カートの中身一覧取得
           $sql = "SELECT i.id, i.img, i.name, i.price, i.status,c.amount FROM item_table AS i JOIN cart AS c ON c.item_id = i.id WHERE c.user_id = {$user_id} ";
           $cart_list = get_as_array($link, $sql);
       }
    }
    //カートの中身一覧取得
   $sql = "SELECT i.id, c.cart_id, i.img, i.name, i.price, c.amount FROM item_table AS i JOIN cart AS c ON c.item_id = i.id WHERE c.user_id = {$user_id}";
   $cart_list = get_as_array($link, $sql);
   
   if(count($cart_list) === 0){
       $message = 'カートに商品がありません。';
   }
   //合計金額
   $sql = "SELECT SUM(i.price * c.amount) AS amount_total FROM item_table AS i JOIN cart AS c ON i.id = c.item_id WHERE c.user_id = {$user_id}";
   $amount_total = get_as_array($link, $sql);
}

mysqli_close($link);

include_once './view/cart_view.php'; 
