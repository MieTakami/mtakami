<?php
// パラメータ取得
$request_param = $_POST;
 
// お問い合わせ日時
$request_datetime = date("Y年m月d日 H時i分s秒");
 
//自動返信メール
$mailto = $request_param['mail'];
$to = 'tookyoo@icloud.com'; //メールを受け付けるメールアドレスを入力
$mailfrom = "From:mmoeymk@gmail.com"; //自動送信メールを送信するメールアドレスを入力
$subject = "お問い合わせ有難うございます。";
 
$content = "";
$content .= $request_param['name']. "様\r\n";
$content .= "お問い合わせ有難うございます。\r\n";
$content .= "お問い合わせ内容は下記通りでございます。\r\n";
$content .= "=================================\r\n";
$content .= "お名前	      " . htmlspecialchars($request_param['name'])."\r\n";
$content .= "メールアドレス   " . htmlspecialchars($request_param['mail'])."\r\n";
$content .= "内容   " . htmlspecialchars($request_param['content'])."\r\n";
$content .= "お問い合わせ日時   " . $request_datetime."\r\n";
$content .= "=================================\r\n";
 
//管理者確認用メール
$subject2 = "お問い合わせがありました。";
$content2 = "";
$content2 .= "お問い合わせがありました。\r\n";
$content2 .= "お問い合わせ内容は下記通りです。\r\n";
$content2 .= "=================================\r\n";
$content2 .= "お名前	      " . htmlspecialchars($request_param['name'])."\r\n";
$content2 .= "メールアドレス   " . htmlspecialchars($request_param['email'])."\r\n";
$content2 .= "内容   " . htmlspecialchars($request_param['content'])."\r\n";
$content2 .= "お問い合わせ日時   " . $request_datetime."\r\n";
$content2 .= "================================="."\r\n";
 
mb_language("ja");
mb_internal_encoding("UTF-8");
//mail 送信
if($request_param['token'] === '1234567'){
if(mb_send_mail($to, $subject2, $content2, $mailfrom)){
    mb_send_mail($mailto,$subject,$content,$mailfrom);
    ?>
    <script>
        window.location = 'メールを送信した後に表示されるページのurl';
    </script>
    <?php
} else {
    header('Content-Type: text/html; charset=UTF-8');
  echo "メールの送信に失敗しました";
};
} else {
echo "メールの送信に失敗しました（トークンエラー）";
}