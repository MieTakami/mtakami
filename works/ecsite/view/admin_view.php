<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link href="css/common.css" rel="stylesheet" type="text/css">
    <title>商品管理ページ</title>
</head>
<body>
    <main>
    <h1>SHOP 管理ページ</h1>
    <div>
        <a class="nemu" href="./logout.php">ログアウト</a>
        <a href="./user_data.php">ユーザー管理ページ</a>
    </div>
<?php if(count($err_msg) === 0 ){ 
           foreach($message as $value){?> 
           <p class="success-msg"><?php print entity_str($value); ?></p>
<?php      } 
       }else{ 
           foreach($err_msg as $value){ ?>
           <p class="err-msg"><?php print entity_str($value); ?></p>
            
<?php       } 
       } ?>
    <h2>商品の登録</h2>
    <section>
        <h2>商品情報の一覧・変更</h2>
        <form method="post" enctype="multipart/form-data">
            <div><label>名前: <input type="text" name="new_name" value=""></label></div>
            <div><label>値段: <input type="text" name="new_price" value=""></label></div>
            <div><label>個数: <input type="text" name="new_stock" value=""></label></div>
            <div><input type="file" name="new_img"></div>
            <div>
                <select name="new_status">
                    <option value="0">非公開</option>
                    <option value="1">公開</option>
                </select>
            </div>
            <input type="hidden" name="sql_kind" value="insert">
            <div><input type="submit" value="■□■□■商品追加■□■□■"></div>
        </form>
    </section>
    <section>
        <h2>商品情報変更</h2>
        <table>
            <caption>商品一覧</caption>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
<?php               foreach($stock_list as $value){ ?>
                        <tr <?php if($value['status'] === '0'){print 'class="status_false"';} ?>" >
                            <td><img class="img_size" src="./img/<?php print entity_str($value['img']); ?>" alt="商品画像"></td>
                            <td class="item_name_width"><?php print entity_str($value['name']); ?></td>
                            <td class="text_align_right"><?php print entity_str($value['price']);?>円</td>
                            <form method="post">
                            <td><input type="text"  class="input_text_width text_align_right" name="updated_stock" value="<?php print entity_str($value['stock']); ?>">
                                <input type="submit" value="変更"></td>
                                <input type="hidden" name="sql_kind" value="stock_update">
                                <input type="hidden" name="item_id" value="<?php print entity_str($value['id']); ?>">
                            </form>
                            
                            
                            <form method="post">
                                <td>
                                    <input type="submit" name="status" value="<?php if($value['status'] === '0'){print '非公開→公開';}else{print '公開→非公開';} ?>">
                                    <input type="hidden" name="item_id" value="<?php print entity_str($value['id']); ?>">
                                    <input type="hidden" name="sql_kind" value="update">
                                    <input type="hidden" name="new_status" value="<?php if($value['status'] === '0'){print 1;}else{print 0;}?>">
                                </td>
                            </form>
                            
                            
                            <form method="post">
                                <td>
                                    <input type="submit" name="delete" value="削除する">
                                    <input type="hidden" name="item_id" value="<?php print entity_str($value['id']); ?>">
                                    <input type="hidden" name="sql_kind" value="delete">
                                </td>
                            </form>
                        </tr>
<?php               }
                    
?>
            </tr>
            
        </table>
    </section>
    </tr>
    </main>
</body>
</html>