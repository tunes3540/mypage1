<?php
include_once("../functions_connect.php");
$updated = '';
foreach ($_REQUEST as $param_name => $param_val) {
		if($param_name == "PHPSESSID"){continue;}
    $$param_name = $param_val;
		} 
$myfile = fopen("../functions.inc.php", "r") or die("Unable to open file!");
$contents = fgets($myfile);
$contents_array = explode(";",$contents);

$user_array = explode("\"",$contents_array[0]);
$pw_array = explode("\"",$contents_array[1]);
$database_array = explode("\"",$contents_array[2]);

fclose($myfile);


$ip=$_SERVER['REMOTE_ADDR'];
$txt = print_r($_REQUEST, true)." ".date('m/d/Y H:i')." ipaddress=".$ip;
$myfile = file_put_contents('new_config_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

if($database_array[1] == ''){$database_exists = 'n';}
$db = connect_pdo();
if(!$db){$message="<div style='text-align:left;'>Database ".$database_array[1]." does not exist <br>OR<br> the user or password is incorrect.</div>";
	$database_exists = 'n';
	}
else{$message="";$database_exists = 'y';}
?>

<div align="right" style="width:400px;padding:10px;border:1px solid black;">
    <?php if($updated == 'yes'){?><div style="width:100px;background:#00cc99;margin-right:140px;text-align:center;padding:5px;">Updated</div><?php } ?>
    <?php if($updated == 'no'){?><div style="width:100px;background:#00cc99;margin-right:140px;text-align:center;padding:5px;">Not Updated</div><?php } ?>
	<?=$message;?>
    <h1>Mypage Configuration</h1>
    <form method="post" action="make_config.php">
		    mysql user name <input type="text" name="user" value="<?=$user_array[1];?>"><br><br>
		    mysql password <input type="text" name="pw" value="<?=$pw_array[1];?>"><br><br>
		    mysql database name <input type="text" name="database" value="<?=$database_array[1];?>"><br><br>
		    <input type="submit" value="Enter">
    </form>

<?php

if($database_exists == 'y'){
		$sql = "SHOW TABLES LIKE 'blocks'";
		    $stmt = $db->query($sql);
			  $rows = $stmt->fetchAll();
			  
			  if(count($rows) == 0){
			  	?>
			  <div>Database needs setup <a href="load_database.php">Click </a>to setup</div>
			  	<?php
			  }
		   
		?>
		
		<br><br>
		<a href="../index.php?page=1">Go to Mypage</a>
		</div>
		<?php
	}
