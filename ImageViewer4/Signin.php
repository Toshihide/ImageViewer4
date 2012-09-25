<?php
session_start();

$data["errorCode"] = 0;

$username = $_POST["username"];
$password = $_POST["password"];


if ( $username == "" )
{
	$data["errorCode"] = -100;
}
else if ( $password == "" )
{
	$data["errorCode"] = -110;
}
else
{
	$database = 'site1';
	$dbUsername = 'ii';
	$dbPassword = '';
	$dsn = 'mysql:host=localhost;dbname=site1;';

	$dbh = new PDO($dsn, $dbUsername, $dbPassword);

	// Check duplicate username.
	$stmt = $dbh->prepare("SELECT Name FROM m_users where Name = " . $username );
	$stmt->execute();

	if ( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
	{
		$data["errorCode"] = -120;
	 	// Goto index.html
	 	//header("Location: index.html");
		$data["location"] = "index.html";
	}
	else
	{
	 	// Insert user into m_users
	 	$stmt = $dbh->prepare("INSERT INTO m_users (Name, Password, CreationDate, LastAccessedDate) VALUES(:name, :password, :creationDate, :lastAccessedDate )");
	 	$stmt->bindValue(':name', $username, PDO::PARAM_STR );
	 	$stmt->bindValue(':password', $password, PDO::PARAM_STR );
		$stmt->bindValue(':creationDate', date("Y/M/D H/I/S"), PDO::PARAM_STR );
	 	$stmt->bindValue(':lastAccessedDate', date("Y/M/D H/I/S"), PDO::PARAM_STR );
	 	$stmt->execute();

	 	// Goto indexUser.html
	 	//header("Location: indexUser.html");
	 	$data["location"] = "indexUser.html";
	 	$_SESSION["username"] = $username;
	}

	$dbh = null;

	// catch (PDOException $e)
	// {
	// 	$data["errorCode"] = 0;
	// 	// Goto index.html
	// 	header("Location: index.html");
	// 	$dbh = null;
	// }
}

echo json_encode($data);

?>