<html>
<head>
	<title>My Smugmug retrieve</title>

<?php

require_once( "phpsmug/phpSmug.php" );

try {
	
	
	$db = connect();
	$sql = "SELECT * from smugmug where id = 1";
	$result = mysql_query ($sql, $db);
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $last_update = $row['gallery_id'];
  $today = date("Y-m-d");
  //-- update the listing in the database
  if ($today != $last_update){
      $sql = "DELETE from smugmug";
      $result = mysql_query ($sql, $db);
      $sql = "INSERT into smugmug SET gallery_id = '$today', id = '1'";
      $result = mysql_query ($sql, $db);
      
      $f = new phpSmug( "Qp3x7BZ7HDGWtqfzKgXB8r9826MdJrhB", "AppName=mypage" );
    	$f->login();	
    	
    	$albums = $f->albums_get( 'NickName=claytonfelt' );	
    	?><pre><?php //print_r($albums);?></pre>
    	<?php
    	
 
      foreach($albums as $album){
        $gallery_id = $album['id'];
        $gallery_key = $album['Key'];
        $folder = $album['Category']['Name'];
        $title = $album['Title'];
    	   $sql = "INSERT into smugmug set gallery_id = '$gallery_id', gallery_key = '$gallery_key', folder= '$folder', title = '$title'";
    	   $result = mysql_query ($sql, $db);
    	}
	}
  //-- list folders
  if(!isset($_GET['folder'])){
      $sql = "SELECT distinct folder from smugmug order by folder";
      $result = mysql_query ($sql, $db);
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
          ?>
          <a href="?folder=<?=$row[folder];?>"><?=$row[folder];?></a><br>
          <?
      }
  }
  //-- list gallerys
  if(isset($_GET['folder'])){
      $folder = $_GET['folder'];
      ?><a href="smugmug_show_images.php">Back to top</a><?
      ?><h1><?=$folder;?></h1><?
      $sql = "SELECT * from smugmug where folder = '$folder'";
      $result = mysql_query ($sql, $db);
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
          ?>
          <a href="?gallery_id=<?=$row['gallery_id'];?>&gallery_key=<?=$row['gallery_key'];?>&folder=<?=$folder;?>"><?=$row[title];?></a><br>
          <?
      }
  
  }

	
	?><pre><? //print_r($unique_arr);?></pre><?
	
	if (isset($_GET['gallery_id'])){
	   $gallery_id = $_GET['gallery_id'];
	   $gallery_key = $_GET['gallery_key'];
	   $sql = "SELECT * from smugmug where gallery_key = '$gallery_key'";
     $result = mysql_query ($sql, $db);
     $row = mysql_fetch_array($result, MYSQL_ASSOC);
     
	   
	   ?><h2><?=$row[title];?></h2><?
    	?><pre><? //print_r($_GET);?></pre><?
    	// Get list of public images and other useful information
    
    	
    	?><pre><? //print_r($image);?></pre><?
    	$f = new phpSmug( "APIKey=hLIFIsmrKd7lITN7j22SNggj4ITyFl1s", "AppName=SmugMug Embed" );
      $f->login();
    	$images = $f->images_get( "AlbumID=$gallery_id", "AlbumKey=$gallery_key", "Heavy=1" );
    	$images = ( $f->APIVer == "1.2.2" ) ? $images['Images'] : $images;
    	// Display the thumbnails and link to the medium image for each image
    	foreach ( $images as $image ) {
    		
        echo '<a href="'.$image['OriginalURL'].'"><img style="margin:2px 2px;" src="'.$image['ThumbURL'].'" title="'.$image['Caption'].'" alt="'.$image['id'].'" /></a>';
    		
    	}
	
	
	}
	
	
	
}
catch ( Exception $e ) {
	echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
}

include_once("functions.inc.php");
//---------------------------------------- Connect -------------------------------------
function connect(){
            include ("functions.inc.php");
            $db = mysql_connect("localhost", $sql_user, $sql_pw);
            mysql_select_db($database,$db);
            return ($db);
} 
?>
	</div>
</body>
</html>
