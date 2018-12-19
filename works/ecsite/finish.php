<?php
/*DB同時処理でエラー発生する為、77行目でsleep関数使用*/
require_once 'conf/const.php';
require_once 'model/function.php';

$user_id = '';
$user_name = '';
$amount = 0;
$cart_list = array();
$amount_total = '';//合計金額
$err_msg = array();
$message = '';

$amount_data = array();
$date = date('Y-m-d H:i:s');

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
   
   $sql = "SELECT id, user_name FROM user_table WHERE id ={$user_id}";
   $user_data = get_as_array($link, $sql);
   $user_name = $user_data[0]['user_name'];
   
   //カートの中身一覧取得
   $sql = "SELECT i.id, i.img, i.name, i.price, c.amount FROM item_table AS i JOIN cart AS c ON c.item_id = i.id WHERE c.user_id = {$user_id} ";
   $cart_list = get_as_array($link, $sql);
   
   //合計金額
   $sql = "SELECT SUM(i.price * c.amount) AS amount_total FROM item_table AS i JOIN cart AS c ON i.id = c.item_id WHERE c.user_id = {$user_id}";
   $amount_total = get_as_array($link, $sql);
   
   if(count($cart_list) === 0){
       $err_msg[] = '商品はありません。';
   }else{
       // トランザクション開始(オートコミットをオフ）
       mysqli_autocommit($link, false);
       
       $message = 'ご購入ありがとうございました。';
            //item_stock_tableから在庫数更新
            foreach($cart_list as $data){
                $item_id = $data['id'];//売れたアイテム
                $sold_amount = $data['amount'];//売れた個数
                $item_name = $data['name'];
                
                //item_stock_tableの現在の在庫数
                $sql = "SELECT item_id, stock FROM item_stock_table WHERE item_id = {$item_id}"; 
                $amount_data = get_as_array($link, $sql);
                $stock_table_amount = $amount_data[0]['stock'];
                
                if($sold_amount > $stock_table_amount){
                   $err_msg[] = $item_name.'は残り'.$stock_table_amount.'個です。';
                   break;
                }
                //更新後の在庫数
                $updated_stock = $stock_table_amount - $sold_amount;

                $sql = "UPDATE item_stock_table SET stock = {$updated_stock}, updated_date = '{$date}' WHERE item_id = {$item_id}";
               if (mysqli_query($link, $sql) ) {
                    //cartからデータ削除
                    $sql = "DELETE FROM cart WHERE user_id = {$user_id}";
                    if (mysqli_query($link, $sql) !== TRUE) {
                        $err_msg[] = 'cart : DELETEエラー:' . $sql;
                    }
               }
               sleep(5);//DB同時処理でエラー発生する為、処理遅延実施
            }
            // トランザクション成否判定
           if (count($err_msg) === 0) {
               // 処理確定
               mysqli_commit($link);
           } else {
               // 処理取消
               mysqli_rollback($link);
               include_once './view/cart_view.php';
               exit;
           }
   }
}

mysqli_close($link);

include_once './view/finish_view.php';
