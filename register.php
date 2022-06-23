<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>ユーザー登録</title>
	<link rel="stylesheet" href="./css/login.css">
</head>
<body>
<div id="main">
<?php
	// 共通するデータ・関数を定義したPHPファイルを読み込む
	require_once __DIR__ . '/util.php';

	// 受信データを変数に格納
	$ident = $_POST[ 'input_id' ];
	$pass = $_POST[ 'input_pass' ];
	// $name = $_POST[ 'input_name' ];

	// 発生したエラー、例外を特定するコード番号を代入する変数
	$error_code = 0;

	// 受信データの値の有効性チェック
	if ( empty( $ident ) || empty( $pass )){
		// 受信データが有効でない場合のエラーコード(その他のチェックは今回省略)
		$error_code = 100;
	} else {
		try {
			// 入力されたユーザーIDをキーに、データベースからデータを抽出
			$sql = "select * from password where ident = ?";
			$stmt = $pdo->prepare( $sql );
			$stmt->execute( [ $ident ] );
			$result = $stmt->fetch( );

			if ( empty( $result[ 'ident' ] ) ) {
				// empty( )の戻り値がTRUE → データがない → 未登録
				// 受け取ったユーザーID、パスワード、名前でデータベースに登録する
				$sql = "insert into password ( ident, pass, name ) values ( ?, ?, ?)";
				$stmt = $pdo->prepare( $sql );
				$stmt->execute( [$ident, $pass, $name] );
			} else {
				// empty( )の戻り値がFALSE → データがある → 既に登録済み
				$error_code = 200;
			}
		} catch ( Exception $e ) {
			// データベース関連の例外発生
			$error_code = 900;
			// die( );	// エラーメッセージを表示するためここではdie( )しない
		}
		$pdo = null;
	}
	// errro_codeの値に応じたメッセージを表示する
	if ( $error_code == 0 ) {
		// エラーがなく、正しく登録された場合
		echo "<h2>ユーザー登録が完了しました。</h2>";
		echo "<hr><br>";
		echo "<table id='regiTable'>";
		echo "<tr><th>ユーザーID</th><td>" . h ( $ident ) . "</td></tr>";
		echo "<tr><th>パスワード</th><td>" . h ( $pass ) . "</td></tr>";
		echo "<tr><th>お名前</th><td>" . h ( $name ) . "</td></tr>";
		echo "</table><br>";
		echo "<a href='login.html'>ログインページへ</a>";
	} else if ( $error_code == 100 ) {
		echo "<h2>未入力項目があります。</h2>";
		echo "<hr><br>";
		echo "ユーザーID、パスワード、お名前のすべての項目を入力してください。<br><br>";
		echo "<a href='register.html'>新規ユーザー登録へ戻る</a>";
	} else if ( $error_code == 200 ) {
		echo "<h2>ユーザーIDは登録済みです。</h2>";
		echo "<hr><br>";
		echo "入力されたユーザーID(<b>" . $ident . "</b>)は、すでに登録済みです。<br>";
		echo "他のユーザーIDをご利用ください。<br /><br>";
		echo "<a href='register.html'>新規ユーザー登録へ戻る</a>";
	} else if ( $error_code == 900 ) {
		echo "<h2>データベースエラー</h2>";
		echo "<hr><br>";
		echo "データベースでエラーが発生しました。<br>";
		echo "管理者に連絡してください。<br><br>";
		echo "<a href='login.html'>ログインページへ</a>";
	}
?>
<br><br>
<hr>
<p>4組 15番 富垣　翔騎</p>
</div>
</body>
</html>
