<?php
$file = 'comment.txt';
$name = '';//名前
$comment = '';//ひとこと
$errors = array();

//入力されたコメントをcomment.txtへ書き込む
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //入力された名前を$nameへ代入
    if(isset($_POST['name']) === TRUE){
        $name = trim($_POST['name']);
    }
    if(isset($_POST['comment']) === TRUE){
    //入力されたコメントを$commentへ代入
    $comment = trim($_POST['comment']);
    }
    if(empty($name) === TRUE){
        $errors[] = '名前を入力してください';
    }else if((mb_strlen($name) > 20) === TRUE){
        $errors[] = '名前は20文字以内で入力してください';
    }
    if(empty($comment) === TRUE){
        $errors[] = 'ひとことを入力してください';
    }else if((mb_strlen($comment) > 100) === TRUE){
        $errors[] = 'ひとことは100文字以内で入力してください';
    }
    if(count($errors) === 0){
            //comment.txtへ書き込み
        if(($fp = fopen($file,'a')) !== FALSE){
            if(fwrite($fp,$name.':'.$comment.date("-Y-d-m H:i:s")."\n") === FALSE){
                $errors[] = 'ファイル書き込み失敗';
            }
            fclose($fp);
        }

    }
}

$data = array();
if(is_readable($file) === TRUE){
    if(($fp = fopen($file,'r')) !== FALSE){
        while(($line = fgets($fp)) !== FALSE){
            //最新の入力内容を配列の先頭に追加
            $tmp = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
            array_unshift($data,$tmp);
        }
        fclose($fp);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <title>ひとこと掲示板</title>
   <link rel="stylesheet" href="reset.css">
　　<link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>PHP/ひとこと掲示板</h1>
        </header>
    <div id = "wrapper">
       <ul><?php foreach($errors as $value){ ?>
            <li><?php print $value; ?></li>
       <?php } ?>
       </ul>
       <form method="post">
           <label>名前：</label><input type="text" name="name">
           <label>ひとこと：</label><input type="text" class="text" name="comment">
           <input type="submit" class="square_btn" value="送信">
       </form>
       <ul>
           <?php foreach($data as $value){?>
                <li><?php print $value ?></li>
           <?php }?>
        </ul>
    </div>
</body>
</html>