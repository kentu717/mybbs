<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    <h1>Web掲示板</h1>
    <h2>コメント投稿</h2>
    <form action = "" method = "POST">
      <div>
        <input type = "text" placeholder = "投稿者名" name = "name">
      </div>
      <div>
        <input type = "text" placeholder = "コメント" name = "comment">
        <input type = "submit" value = "投稿">
      </div>
    </form>
    <hr>
    <h2>コメント一覧</h2>

    <?php
  //データベース情報
  $hostname = 'localhost';
  $dbname = '****';
  $user = '****';
  $password = '****';

  //データソース
  $dbs = "mysql:host=$hostname;dbname=$dbname;charset=utf8";

  //データベースに接続
  $pdo = new PDO($dbs, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

  //入力ミスがあるかどうか調べるための変数
  $errmsg = "";

  //入力した投稿者名とコメントを取得
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $comment = trim($_POST['comment']);

  //コメントが入力されているかどうかをチェック
  if($name == "" || $comment == ""){
    $errmsg = "投稿者名またはコメントが入力されていません";

  //エラーメッセージを表示
  echo "<p><font color='red'>$errmsg</font></p>";
  }

  //入力ミスがなければ以下の処理を行う
    if($errmsg == ""){

        //データベース内にテーブルを作成
        $sql = "CREATE TABLE IF NOT EXISTS comment_table"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "cdate datetime"
        .");";
        $stm = $pdo->query($sql);

        
  //データ追加のinsert文を作成
        $sql = "insert into comment_table (name, comment, cdate) value (:name, :comment, now())";

  //insert文の実行を準備
        $stm = $pdo -> prepare($sql);

  //プレースホルダに値を設定
        $stm -> bindParam(':name', $name, PDO::PARAM_STR);
        $stm -> bindParam(':comment', $comment, PDO::PARAM_STR);

  //insert文を実行
        $stm -> execute();
    }
  }

  //テーブルの内容を表示
  $sql = 'SELECT * FROM comment_table order by id DESC';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
  foreach ($results as $row){
  //$rowの中にはテーブルのカラム名が入る
  echo $row['id'].',';
  echo $row['name'].',';
  echo $row['comment'].',';
  echo $row['cdate'].'<br>';
  }
?>
</body>
</html>