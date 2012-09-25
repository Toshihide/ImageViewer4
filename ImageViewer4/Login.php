<?php
session_start();

// エラーメッセージを格納する変数を初期化
$data["errorCode"] = 0;
$data["location"] = "";

$database = 'site1';
$host = 'localhost';
$username = 'ii';
$password = '';

$dsn = 'mysql:host=localhost;dbname=site1;';

$dbh = new PDO($dsn, $username, $password);
$stmt = $dbh->prepare("SELECT Name FROM m_users WHERE Name = '" . $_POST["username"] . "' AND Password = '" . $_POST["password"] . "'" );
$stmt->execute();

if ( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
{
	// ログインが成功した証をセッションに保存
	$_SESSION["username"] = $_POST["username"];
	$data["location"] = "indexUser.html";
}
else
{
	$data["errorCode"] = -100;
	$data["location"] = "indexUser.html";
}

$dbh = null;

echo json_encode($data);

?>