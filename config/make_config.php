<?php
if($_POST['user']=='' || $_POST['pw']=='' || $_POST['database']==''){
    header("location:new_config.php?updated=no");
    exit;
}
$out = '<? $sql_user = "'.$_POST['user'].'";';
$out .= '$sql_pw = "'.$_POST['pw'].'";';
$out .= '$database = "'.$_POST['database'].'";';

$myfile = fopen("../functions.inc.php", "w") or die("Unable to open file!");
fwrite($myfile, $out);
fclose($myfile);
header("location:new_config.php?updated=yes");
exit;
?>