<?php

session_start();

$data["username"] = $_SESSION["username"];

echo json_encode($data);

?>