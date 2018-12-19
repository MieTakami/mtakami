<?php
$login_err_flag = FALSE;
$user_name ='';
$password = '';
$user_data = '';
$user_id = '';

require_once 'conf/const.php';
require_once 'model/function.php';
// セッション開始
session_start();

if(isset($_SESSION['user_id'])){
    header('Location: top.php');
    exit;
}

if (get_request_method() === 'POST') {
   // POST値取得
    $user_name  = get_post_data('user_name');  // ユーザー名
    $password = get_post_data('password'); // パスワード

    // DB接続
    $link = get_db_connect();
    
    // SQL生成
    $sql = "SELECT id,user_name, password FROM user_table WHERE user_name = '{$user_name}' AND password = '{$password}'";
    $user_data = get_as_array($link, $sql);
    
    if(empty($user_data) === TRUE){
        $login_err_flag = TRUE;
    }else{
        $_SESSION['user_id'] = $user_data[0]['id'];
        if(($user_name === 'admin') && ($password === 'admin')){
            header('Location: admin.php');
            exit;
        }else{
            header('Location: top.php');
            exit;
        }
    }
    // DB切断
    close_db_connect($link);   
}

include_once 'view/login_view.php';
