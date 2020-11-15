<?php
include_once("functions_connect.php");


$db = connect_pdo();

$sql = file_get_contents('mypage1.sql');

$qr = $db->exec($sql);
header("Location: new_config.php"); 
exit;
?>