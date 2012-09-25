
<?php

// session start.
session_start();

// The posted data, for reference
$data = $_POST['data'];

$database = 'site1';
$host = 'localhost';
$username = 'ii';
$password = '';

$dsn = 'mysql:host=localhost;dbname=site1;';

try{
	$dbh = new PDO($dsn, $username, $password);

	$stmt = $dbh->prepare("INSERT INTO m_items (FileName, UserName, Discription, Data, Size, Extension) VALUES(:filename, :username, :discription, :data, :size, :extension )");
	$stmt->bindValue(':filename', $_POST['filename'], PDO::PARAM_STR );
	$stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR );
	$stmt->bindValue(':discription', $_POST['discription'], PDO::PARAM_STR );
	$stmt->bindValue(':data', file_get_contents($data), PDO::PARAM_LOB );
	$stmt->bindValue(':size', $_POST['size'], PDO::PARAM_INT );
	$stmt->bindValue(':extension', $_POST['extension'], PDO::PARAM_STR );
	$stmt->execute();

}
catch (PDOException $e)
{
	die();
}

$dbh = null;

?>