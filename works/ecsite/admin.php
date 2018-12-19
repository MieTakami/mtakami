<?php

require_once 'conf/const.php';
require_once 'model/function.php';

$err_msg = array(); // エラーメッセージ
$stock_list = array();//在庫一覧

$user_id = '';
$new_name = '';//新規追加する商品名
$new_price = '';//新規追加する商品の価格
$new_stock = '';//新規追加する商品の在庫数
$new_img = '';//新規追加する商品の画像ファイル名
$new_status = 0;//公開(1)・非公開(0)

$created_date = '';//作成日
$updated_date = '';//更新日
$item_id = '';//A_I

$updated_stock = '';//在庫数変更
$message = array();

$delete = '';
$request_method = get_request_method();

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
   
   if ($request_method === 'POST') {
       // POST値取得
        $sql_kind = get_post_data('sql_kind');
   
       if($sql_kind === 'insert'){
       //新規商品を追加する為のコード↓
           //入力された値をうけとる
           $new_name = get_post_data('new_name');//商品名
           $new_name = preg_replace('/^[ 　]+/u', '', $new_name);
           $new_name = preg_replace('/[ 　]+$/u', '', $new_name);
           
           $new_price = get_post_data('new_price');//値段
           
           $new_stock = get_post_data('new_stock');//在庫数
           
           $new_img = get_img_name('new_img');//画像名
           
           $new_status = get_post_data('new_status');//ステータス
           
           $delete = get_post_data('delete');//削除

           //エラーチェック
           if(mb_strlen($new_name) === 0){
               $err_msg[] = '商品名を入力してください。';
           }
           if(empty($new_price) === TRUE ){
               $err_msg[] = '値段を入力してください。';
           }else if((preg_match("/^[0-9]+$/",$new_price) !== 1)){
               $err_msg[] = '値段は半角数字を入力してください。';
           }
           if(empty($new_stock) === TRUE){
               $err_msg[] = '個数は1以上の半角数字を入力してください。';
           }else if((preg_match("/^[0-9]+$/",$new_stock) !== 1)){
               $err_msg[] = '個数は半角数字を入力してください。';
           }
           
           if(($new_status !== '0') && ($new_status !== '1')){
               $err_msg[] = 'ステータスが不正です';
           }
           
           if(count($err_msg) === 0){
                //画像のアップロード
               if($_FILES['new_img']['error'] === UPLOAD_ERR_OK){
                   if (($_FILES['new_img']['type'] !== 'image/jpeg') && ($_FILES['new_img']['type'] !== 'image/png')){
                        $err_msg[] = 'JPEGかPNGファイルをアップロードしてください';
                    }else if($_FILES['new_img']['size'] > 10485760){
                        $err_msg[] = 'ファイルは10MB以下にしてください';
                    }else{
                        move_uploaded_file($_FILES['new_img']['tmp_name'], './img/'.$new_img);
                    }
               }else{
                   $err_msg[] = 'ファイルを選択してください';
            }
               
               //更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
               mysqli_autocommit($link, false);
               
               $created_date = date('Y-m-d H:i:s');
               $updated_date = date('Y-m-d H:i:s');
            
               
               // item_table に insertするSQL
               $sql = "INSERT INTO item_table (name, price, img, status, created_date, updated_date)
               VALUES('{$new_name}', {$new_price}, '{$new_img}', {$new_status}, '{$created_date}', '{$updated_date}')";
               // insertを実行する
               if(mysqli_query($link, $sql) === TRUE){
                   // A_Iを取得
                   $item_id = mysqli_insert_id($link);
                   //item_stock_table に insertするSQL
                   $sql = "INSERT INTO item_stock_table (item_id, stock, created_date, updated_date)
                   VALUES({$item_id}, {$new_stock}, '{$created_date}', '{$updated_date}')";
                   // insertを実行する
                    if (mysqli_query($link, $sql) !== TRUE){
                        $err_msg[] = 'stock_table: insertエラー:' . $sql;
                    }
               }else{
                   $err_msg[] = 'item_table: insertエラー:' . $sql;
               }
               // トランザクション成否判定
               if(count($err_msg) === 0){
                   // 処理確定
                   mysqli_commit($link);
                   $message[] = '商品を追加しました。';
               }else{
                   // 処理取消
                   mysqli_rollback($link);
               }
            }
        }else if($sql_kind === 'stock_update'){
            //在庫数の更新
            $updated_stock = get_post_data('updated_stock');
            
            if((preg_match("/^[0-9]+$/",$updated_stock) !== 1)){
               $err_msg[] = '個数は半角数字を入力してください';
            }else if(empty($updated_stock)){
                $err_msg[] = '個数は1以上の半角数字を入力してください';
            }
            if(count($err_msg) === 0){
                //更新日
                $updated_date = date('Y-m-d H:i:s');
                $item_id = $_POST['item_id'];
                //在庫数を更新するsql
                $sql = "UPDATE item_stock_table SET stock = {$updated_stock}, updated_date = '{$updated_date}' WHERE item_id = {$item_id} ";
                // updateを実行する
                if (mysqli_query($link, $sql) === TRUE){
                    //変更後に表示するメッセージ
                    $message[] = '在庫変更成功';
                }else{
                    $err_msg[] = 'stock_table: updateエラー:' . $sql;
                }
            }
            
        }else if($sql_kind === 'update'){
            //公開ステータスの更新処理
            $new_status = get_post_data('new_status');
            $item_id = get_post_data('item_id');
            $updated_date = date('Y-m-d H:i:s');
            
            if((preg_match("/[0-1]{1}/", $new_status) !== 1)){
               $err_msg[] = '不正な値が送信されました';
            }
            if(count($err_msg) === 0){
                //公開ステータスを更新するsql
                $sql = "UPDATE item_table SET status = {$new_status}, updated_date = '{$updated_date}' WHERE id = {$item_id} ";
                 // updateを実行する
                if (mysqli_query($link, $sql) === TRUE){
                    //変更後に表示するメッセージ
                    $message[] = 'ステータス変更成功';
                }else{
                    $err_msg[] = 'item_table: updateエラー:' . $sql;
                }
            }
        }else if($sql_kind === 'delete'){
            //更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
            mysqli_autocommit($link, false);
            
            $delete = get_post_data('delete');
            $item_id = get_post_data('item_id');
            $sql = "DELETE FROM item_table WHERE id = {$item_id}";
             // item_tableからの削除を実行する
            if (mysqli_query($link, $sql) === TRUE){
                // item_stock_tableからの削除処理
                $item_id = get_post_data('item_id');
                // item_stock_tableからの削除を実行する
                $sql = "DELETE FROM item_stock_table WHERE item_id = {$item_id}";
                if(mysqli_query($link, $sql) !== TRUE){
                    $err_msg[] = 'item_stock_table: 削除エラー:' . $sql;
                }
            }else{
                $err_msg[] = 'item_table: 削除エラー:' . $sql;
            }
            // トランザクション成否判定
           if(count($err_msg) === 0){
               // 処理確定
               mysqli_commit($link);
               $message[] = '商品を削除しました。';
           }else{
               // 処理取消
               mysqli_rollback($link);
           }
        }
   }
   
   // 現在の在庫情報を取得するためのSQL
   $sql = 'SELECT item_table.id, item_table.name, item_table.price, item_stock_table.stock, item_table.status, item_table.img
   FROM item_table JOIN item_stock_table ON item_table.id = item_stock_table.item_id;';
   // クエリ実行
   if ($result = mysqli_query($link, $sql)) {
       // 1行ずつ結果を配列で取得します
       while($row = mysqli_fetch_assoc($result)){
           $stock_list[] = $row;
       }
   } else {
       $err_msg[] = 'SQL失敗:' . $sql;
   }
   mysqli_free_result($result);
   mysqli_close($link);
}else {
   $err_msg[] = 'error: ' . mysqli_connect_error();
}
include_once './view/admin_view.php';

