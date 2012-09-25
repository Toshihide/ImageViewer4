<?php
session_start();

// ログイン済みかどうかの変数チェックを行う
if (!isset($_SESSION["username"])) {

	// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
	$noLoginUrl = "index.html";
	$data["location"] = $noLoginUrl;
	echo json_encode($data);

}

?>