<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>好きなお酒なんですか</title>
    </head>
    <body>
        好きなお酒教えてください！
        <br>
     <!--投稿用フォーム-->
     投稿
     <form action=""method="post">
            名前:<input type="text" name="name"><br>
            好きなお酒:<input type="text" name="comment"><br>
            パスワード:<input type="password" name="password">
            <input type="submit" name="submit">
        </form>
        <br>
        編集
        <!--編集用フォーム-->
        <form action=""method="post">
            名前:<input type="number" name="editnumber" plaveholder="番号を選択"><br>
            変更後の好きなお酒:<input type="text" name="editcomment"><br>
            パスワード:<input type="password" name="editpassword">
            <input type="submit" name="edit" value="編集">
        </form>
        <br>
        削除
        <!--削除用フォーム-->
        <form action=""method="POST">
            投稿番号:<input type="number" name="deletenumber" placeholder="番号を選択">
            パスワード:<input type="password" name="deletepassword">
            <input type="submit" name="delete" value="削除">
        </form>
        <!--処理を先に記入しないと、フォームに編集元の内容を埋め込めないため-->
        <?php
        //送信処理
        $dsn = 'データベース名';
	    $user = 'ユーザー名';
        $password = 'パスワード名';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    $sql = "CREATE TABLE IF NOT EXISTS テーブル名"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
        . "comment TEXT"
        . "password TEXT"
        . "date TEXT"
	    .");";
	    $stmt = $pdo->query($sql);
        if(isset($_POST["submit"])){
            //新規投稿処理
            //empty関数は中身が0の場合trueを返す
            //A&&BでAとBがともに真の場合に真
            if(isset($_POST["name"],$_POST["comment"],$_POST["password"])){
                $name=$_POST["name"];
                $comment=$_POST["comment"];
                $date=date("y/m/d h:i:s");
                $password=$_POST["password"];
                $dsn = 'データベース名';
	            $user = 'ユーザー名';
                $password = 'パスワード名';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                $sql = $pdo->prepare("INSERT INTO posts(name,comment,password,date) VALUES(:name,:comment,:password,:date)");
                $sql->bindParam(':name',$_POST["name"],PDO::PARAM_STR);
                $sql->bindParam(':comment',$_POST["comment"],PDO::PARAM_STR);
                $sql->bindParam(':password',$_POST["password"],PDO::PARAM_STR);
                $sql->bindParam(':date',$date,PDO::PARAM_STR);
                $sql->execute();
            }
        }
        if(isset($_POST["edit"])){
            if(isset($_POST["editnumber"],$_POST["editpassword"],$_POST["editcomment"])){
                $dsn = 'データベース名';
                $user = 'ユーザー名';
                $password = 'パスワード名';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                $editnumber=$_POST["editnumber"];
                $editcomment=$_POST["editcomment"];
                $editpassword=$_POST["editpassword"];
                $date=date("y/m/d h:i:s");
                //まずは編集対象を選ぶ
                $sql="SELECT * FROM テーブル名 WHERE id=$editnumber";
                $stmt=$pdo-query($sql);
                $result=$stmt->fetch();
                if($result["password"]==$_POST["editpassword"]){
                    //選んだ編集対象を編集
                    $sql="UPDATE * FROM テーブル名 SET comment=:comment,date=:date WHERE id =:id";
                    $stmt=$pdo->prepare($sql);
                    $edits=array(":id"=>$editnumber,":comment"=>$editcomment,":date"=>$date);
                    $stmt->execute($edits);
                }

            }
            
        }
        if(isset($_POST["delete"])){
            if(iiset($_POST["deletenumber"],$_POST["deletepassword"])){
                $dsn = 'データベース名';
                $user = 'ユーザー名';
                $password = 'パスワード';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                $deletenumber=$_POST["deletenumber"];
                $deletepassword=$_POST["deletepassword"];
                //消すもの選択
                $sql="SELECT * FROM テーブル名 WHERE id=$deletenumber";
                $stmt=$pdo->query($sql);
                $result=$stmt->fetch();
                if($result["password"]==$_POST["deletepassword"]){
                    //選んだ編集対象を消す
                    $sql="DELETE * FROM テーブル名 WHERE id =:id";
                    $stmt=$pdo->prepare($sql);
                    $deletes=array(":id"=>$deletenumber);
                    $stmt->execute($deletes);
                }
            }
        }
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //処理したデータ出してくる
        $sql="SELECT id , name , comment , date FROM テーブル名 ORDER BY date DESC";
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchALL;
        foreach($results as $row){
            echo $row["id"].",";
            echo $row["name"].",";
            echo $row["comment"].",";
            echo $row["date"]."<br>";
            echo "<hr>";

        }
        ?>
            </body>
</html>