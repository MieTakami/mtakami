<?php

require_once 'conf/const.php';
require_once 'model/function.php';

$user_name = '';
$password = '';
$date = date('Y-m-d H:i:s');
$message = '';
$err_msg = array(); // エラーメッセージ

if(get_request_method() === 'POST'){
    $user_name = get_post_data('user_name');
    $password = get_post_data('password');
    //エラーチェック
    //使用可能文字は半角英数字
    if(int_check($user_name) === FALSE){
        $err_msg[] = 'ユーザ名は半角英数字を入力してください';
    }
    if(int_check($password) === FALSE){
        $err_msg[] = 'パスワードは半角英数字を入力してください';
    }
    //文字数は6文字以上
    if(mb_strlen($user_name) < 6){
         $err_msg[] = 'ユーザ名は文字数は6文字以上で入力してください';
    }
    if(mb_strlen($password) < 6){
         $err_msg[] = 'パスワード名は文字数は6文字以上で入力してください';
    }
    if(count($err_msg) === 0){
        if ($link = get_db_connect()) {

       // 文字コードセット
       mysqli_set_charset($link, 'UTF8');
       
       //既に同じ「ユーザ名」が登録されている場合、エラーメッセージを表示
       $sql = "SELECT user_name FROM user_table WHERE user_name = '{$user_name}' ";
            if(count(get_as_array($link, $sql)) !== 0){
                $err_msg[] = '同じユーザー名が既に登録されています';
            }
        }
        
    }
    if(count($err_msg) === 0){
        //新規ユーザーのinsert
        $sql = "INSERT INTO user_table(user_name, password, created_date, updated_date) VALUES ('{$user_name}','{$password}','{$date}','{$date}')";
        if(mysqli_query($link, $sql) === FALSE){
                $err_msg[] = 'ユーザー名が登録できませんでした';
            }else{
                $message = 'ユーザーを登録しました';
            }
        // 結果セットを開放
        //mysqli_free_result($result);
        mysqli_close($link);
    }
}
include_once 'view/register_view.php';
