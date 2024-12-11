<?php  
session_start();
$host = "localhost";
$user = "root";
$user_user_password = "";
$dbname = "finals";
$dsn = "mysql:host={$host};dbname={$dbname}";

$pdo = new PDO($dsn,$user,$user_user_password);
$pdo->exec("SET time_zone = '+08:00';");
date_default_timezone_set('Asia/Manila');

?>