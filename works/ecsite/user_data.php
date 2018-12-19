<?php

require_once 'conf/const.php';
require_once 'model/function.php';

$user_data = array();

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
    //ユーザー一覧取得
    $sql = "SELECT user_name, created_date FROM user_table";
    $user_data = get_as_array($link, $sql);

}

include_once './view/user_view.php';