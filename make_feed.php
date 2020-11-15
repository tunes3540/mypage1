<?
$table = '';
foreach ($_REQUEST as $param_name => $param_val) {
		if($param_name == "PHPSESSID"){continue;}
    $$param_name = $param_val;
		} 

$str='<?xml version="1.0" encoding="utf-8" ?>'; 
$str.='<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/">';
$str.='<channel>';
$str.='<title>Claytonfelt.net Blog</title>';
$str.='<link>http://www.claytonfelt.net</link>';
$str.='<description>My antics and vacations</description>';
$str.='<pubDate>'.date("D j M Y G:i:s T").'</pubDate>';
$str.='<language>en</language>'; 


    
    $db=connect();
    $sql = "SELECT * FROM `$table`  order by date DESC";
    $result = mysql_query($sql,$db) or die("Couldn't execute query.");
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

$str.='<item>';
$content = strip_tags($row['content']);

$content = str_replace("&nbsp;", " ", $content);
$content = str_replace("&", " ", $content);
$str.='<title>'.$row['blog_title'].'</title>'; 
$str.='<link>http://www.claytonfelt.net/mypage/'.$table.'</link>'; 
$str.='<pubDate>'.$row['date'].'</pubDate>'; 
$str.='<description>'.$content.'</description>';
$str.='</item>';
}
$str.='</channel>';
$str.='</rss>';





    
 $output_file = "my_feed_".$table.".xml";
  $fh = fopen($output_file, 'w') or die("can't open file");
  fwrite($fh, $str);
  fclose($fh);
  chmod($output_file, 0777); 
  


//---------------------------------------- Connect -------------------------------------
function connect1(){
            include("functions.inc.php");
            $db = mysql_connect("localhost", $sql_user, $sql_pw);
            mysql_select_db($database,$db);
            return ($db);
            }

?>
