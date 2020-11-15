<?php
// CONVERT ALL POST AND GET DATA
foreach ($_REQUEST as $param_name => $param_val) {
    if($param_name == "PHPSESSID"){continue;}
$$param_name = $param_val;
}
include_once("functions_connect.php");
$db = connect_pdo();
    
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//-- test if the database is setup by checking for type=page on page 1 --
$sql = "SELECT * FROM $type where  id= '$id' ";
$stmt = $db->query($sql);
$rows = $stmt->fetchAll();
$row = $rows[0];
?>
<h1><?=$row['blog_title'];?></h1>
<p><?=$row['content'];?></p>

