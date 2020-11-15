<?php
include("functions.inc.php");
//---------------------------------------- Connect -------------------------------------
function connect(){
            include ("functions.inc.php");
            $db = mysql_connect("localhost", $sql_user, $sql_pw);
            mysql_select_db($database,$db);
            
            return ($db);
}
// ------------------------------------------------- CONNECT PDO ---------------------------------------------------------
function connect_pdo(){
				include("functions.inc.php");
				if($database==''){return false;}
        $dsn = "mysql:dbname=$database;host=localhost;";
        try {
            $db = new PDO($dsn, $sql_user, $sql_pw);
        } catch(PDOException $e) {return false;
            die('Could not connect to the database:<br/>' . $e);
        }
   
        return $db;
}