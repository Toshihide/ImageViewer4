<?php

session_start();

$username = $_SESSION["username"];

$database = 'site1';
$dbUsername = 'ii';
$dbPassword = '';
$dsn = 'mysql:host=localhost;dbname=site1;';

$dbh = new PDO($dsn, $dbUsername, $dbPassword);

// Check duplicate username.
$stmt = $dbh->prepare("SELECT ID, FileName, UserName, Discription, Size, Extension FROM m_items WHERE UserName = '" . $username . "'");
$stmt->execute();

$currentRow = 0;
$rows = $stmt->fetchAll();

$dbh = null;

echo json_encode($rows);

?>
