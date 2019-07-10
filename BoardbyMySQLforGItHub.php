<?php
	//MySQL接続
	$dsn = 'databasename';
	$user = 'username';
	$password = 'password';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//投稿した情報を入れるテーブルの作成
	$sql = "CREATE TABLE IF NOT EXISTS tablename"
	."("
	."id INT AUTO_INCREMENT PRIMARY KEY,"
	."name char(32),"
	."comment TEXT,"
	."time TEXT,"
	."pass TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
	if(isset($_POST["edit"]) == false){
			$editnumber = "";
	}
	$editname = "";
	$editcomment = "";
	
	//投稿フォーム
	if(isset($_POST["name"]) and isset($_POST["comment"])){
		//新規投稿
		if($_POST["editor"] != true){
			$sql = $pdo->prepare("INSERT INTO tablename(name, comment, time, pass) VALUES(:name, :comment, :time, :pass)");
			$sql->bindParam(':name', $name, PDO::PARAM_STR);
			$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql->bindParam(':time', $time, PDO::PARAM_STR);
			$sql->bindParam(':pass', $pass, PDO::PARAM_STR);
			$time = date("Y/m/d H:i:s");
			$name = $_POST["name"];
			$comment = $_POST["comment"];
			$pass = $_POST["password"];
			$sql->execute();
		}
		//編集
		if($_POST["editor"] == true){
			$id = $_POST["editor"];
			$name = $_POST["name"];
			$comment = $_POST["comment"];
			$time = date("Y/m/d H:i:s");
			$pass = $_POST["password"];
			$sql = 'update tablename set name = :name, comment = :comment, time = :time, pass = :pass where id = :id';
			$stmt = $pdo -> prepare($sql);
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt -> bindParam(':time', $time, PDO::PARAM_STR);
			$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> execute();
		}
	}
	
	//削除フォーム
	if(isset($_POST["delete"])){
		$id = $_POST["delete"];
		$sql = 'SELECT * FROM tablename';
		$stmt = $pdo -> query($sql);
		$results = $stmt -> fetchAll();
		foreach($results as $row){
			if($row['id'] == $id){
				if($_POST["deletepassword"] == $row['pass']){
					$sql = 'delete from tablename where id = :id';
					$stmt = $pdo -> prepare($sql);
					$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
					$stmt -> execute();
				}
			}
		}
	}
	
	//編集フォーム
	if(isset($_POST["edit"])){
		$id = $_POST["edit"];
		$sql = 'SELECT * FROM tablename';
		$stmt = $pdo -> query($sql);
		$results = $stmt -> fetchAll();
		foreach($results as $row){
			if($row['id'] == $id){
				if($_POST["editpassword"] == $row['pass']){
						$editnumber = $row['id'];
						$editname = $row['name'];
						$editcomment = $row['comment'];
				}
			}
		}
	}
?>

<html>
	<head>
		<title>BoardbyMySQL</title>
	</head>
	<body>
		
		<h1>入力フォーム</h1><br />
		<form action = "" method = "post">
		<input type = "hidden" name = "editor" value = "<?php echo $editnumber; ?>"/>
		<h2>名前</h2>
		<input type = "text" name = "name" value = "<?php echo $editname; ?>"/>
		<h2> コメント</h2>
		<input type = "text" name = "comment" value = "<?php echo $editcomment; ?>"/>
		<h2>パスワード</h2>
		<input type = "text" name = "password"/>
		<br />
		<input type = "submit" value = "送信" /><br />
		</form>
		<br />
		
		<h1>削除番号指定用フォーム</h1>
		<form action = "" method = "post">
		<h2>削除対象番号</h2>
		<input type = "number" name = "delete" />
		<h2>パスワード</h2>
		<input type = "text" name = "deletepassword"/>
		<br />
		<input type = "submit" value = "削除" />
		</form>
		<br />
		
		<h1>編集番号指定用フォーム</h1>
		<form action = "" method = "post">
		<h2>編集対象番号</h2>
		<input type = "number" name = "edit" />
		<h2>パスワード</h2>
		<input type = "text" name = "editpassword"/>
		<br />
		<input type = "submit" value = "編集" />
		</form>
		
	</body>
</html>