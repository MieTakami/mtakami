<?php

require_once 'conf/const.php';
require_once 'model/function.php';

$item_id = '';
$date = date('Y-m-d H:i:s');
$amount = 0;
$new_amount = 0;
$user_id = '';
$user_name = '';

$err_msg = array();
$message = array();
$cart_data = array();

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: login.php');
    exit;
}

if(get_request_method() === 'POST'){
    $item_id = get_post_data('item_id');
    //空文字チェック
    if(mb_strlen($item_id) === 0){
        $err_msg[] = '商品をカートに追加できませんでした';
    }
    //数値かチェック
    if((preg_match("/^[0-9]+$/",$item_id) !== 1)){
        $err_msg[] = '商品をカートに追加できませんでした';
    }
    
}
// コネクション取得
if ($link = get_db_connect()) {

   // 文字コードセット
   mysqli_set_charset($link, 'UTF8');
   
   //ユーザー名取得
   $sql = "SELECT id, user_name FROM user_table WHERE id ={$user_id}";
   $user_data = get_as_array($link, $sql);
   $user_name = $user_data[0]['user_name'];
   
   if(get_request_method() === 'POST'){
       if(count($err_msg) === 0){
           $sql = "SELECT i.id,  s.item_id, s.stock, i.status
           FROM item_stock_table AS s JOIN item_table AS i ON s.item_id = i.id WHERE i.id = {$item_id}";
           $cart_data = get_as_array($link, $sql);
           if(empty($cart_data)){
               $err_msg[] = '商品をカートに追加できませんでした';
           }else if($cart_data[0]['stock'] < 1){
               $err_msg[] = '在庫がありません';
           }else{
               $item_id = get_post_data('item_id');
               //現在の個数を取得するsql
               $sql = "SELECT amount FROM cart WHERE item_id = {$item_id} AND user_id = {$user_id} ";
               //sql実行
               $result = get_as_array($link, $sql);
               if(empty($result)){
                   $amount = 1;
                   //現在の個数が0ならinsert
                   $sql = "INSERT INTO cart(user_id, `item_id`, `amount`, `created_date`, `updated_date`) 
                   VALUES ({$user_id},{$item_id},{$amount},'{$date}','{$date}')";
                   if(insert_db($link, $sql)){
                       $message[] = '商品をカートに追加しました';
                   }else{
                       $err_msg[] = '商品をカートに追加できませんでした';
                   }
               }else{
                   //現在の個数を取得
                   $amount = (int)($result[0]['amount']);
                   $new_amount = $amount + 1 ;
                   //購入数、更新日を更新するsql
                   $sql = "UPDATE cart SET amount = {$new_amount}, updated_date = '{$date}' WHERE item_id = {$item_id} AND user_id = {$user_id}";
                   //sql実行
                   if(update_db($link, $sql)){
                       $message[] = '商品をカートに追加しました';
                   }else{
                       $err_msg[] = '商品をカートに追加できませんでした';
                   }
               }
           }
       }
   }
   
   //アイテム一覧取得
    $sql = "SELECT item_table.id, item_table.img, item_table.name, item_table.price , item_stock_table.stock
    FROM item_table JOIN item_stock_table ON item_table.id = item_stock_table.item_id WHERE status = 1;";
    $cart_data = get_as_array($link, $sql);
    
mysqli_close($link);     
}
include_once './view/top_view.php'; 