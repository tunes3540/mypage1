<?php  ob_start(); session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', '1');
include("functions.php");
include("functions_connect.php");

$height = '';
$style_type = '';
$block_title = '';
$background = '';
$border = '';
$id = '';
$blog_id = '';
$question = '';
$blog_and_block = '';
foreach ($_REQUEST as $param_name => $param_val) {
		if($param_name == "PHPSESSID"){continue;}
    $$param_name = $param_val;
} 
      
switch ($action){

//------------------------------------------------------------- Finish site config --------------------------------------
case ("finish_site_config"); 
        
        $password = $_POST['password'];    
        $page = $_SESSION['page'];
        $db = connect_pdo();
        $sql_page = "SELECT * FROM blocks where  id = 1";
        $stmt = $db->query($sql_page);
	    	$rows_page = $stmt->fetchAll();
        $row_page = $rows_page[0];
        
        $page_array = unserialize($row_page['content']);
                              
        
        $page_array['password'] = $password;
        //print_r($page_array);
        $page_sql = serialize($page_array);
        
        echo "page_sql=".$page_sql;
        
        
        $sql_links = "UPDATE blocks SET content = :content WHERE  id = :id ";
        $this_id = 1;
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $page_sql, 
				        ':id'    => $this_id
				    ) 
				); 
      
        $page = $_SESSION['page']; 
        header("Location: index.php?page=$page"); 
        break;

//----------------------------- Link Style -------------------------------------------------------------------------
    case ("link_style");
        echo "link_style=".$link_style."<br>";
        echo "id=".$id."<br>";
        $db = connect_pdo();
        $sql_links = "UPDATE blocks SET style_type = :style_type WHERE id = :id ";
        $stmt = $db->prepare($sql); 
				$result = $stmt->execute( 
				    array( 
				        ':style_type'   => $style_type, 
				        ':id'    => $id
				    ) 
				); 
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page"); 
        break;
    
//----------------------------- Finish Enter Links ----------------------------------------------------------      
     case ("finish_enter_links");      
        $db = connect_pdo();
        
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $stmt = $db->query($sql_links);
	    	$rows = $stmt->fetchAll();
	    	$row_links = $rows[0];
        
        $links_array = unserialize($row_links['content']);
                              
        $links_array['link'][] = $link;
        $links_array['words'][] = $words;
        $links_array['new_tab'][] = $new_tab;
        
        //print_r($links_array);
        $links = serialize($links_array);
        
        $sql_links = "UPDATE blocks SET content = :content WHERE id = :id  ";
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $links, 
				        ':id'    => $block_id
				    ) 
				);
        $page = $_SESSION['page'];
        
        header("Location: index.php?page=$page"); 
        break;
//----------------------------------------- Make site link box ---------------------------------------------------
		case ("make_site_link");
				$db = connect_pdo();
				$sql = "SELECT MAX(page) from blocks";
				$stmt = $db->query($sql);
				$rows = $stmt->fetchAll();
				$max_page = $rows[0][0];
				
				
				for ($x=1;$x<=$max_page;$x++){
					$sql = "SELECT * from blocks where page = '$x' and type = 'page'";
					$stmt = $db->query($sql);
          $rows = $stmt->fetchAll();
          $row = $rows[0];
          $page_info = unserialize($row['content']);
          $page_title = $page_info['page_title'];
          
          $links_array['link'][$x-1] = "index.php?page=$x";
	        $links_array['words'][$x-1] = $page_title;
	        $links_array['new_tab'][$x-1] = "off";
          
				}
				$block_parameter = "site_nav";
				$links = serialize($links_array);
				
				
				$sql = "UPDATE blocks set content = :content, parameter1=:parameter1 where id=:id";
				$stmt = $db->prepare($sql); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $links, 
				        ':parameter1' => $block_parameter,
				        ':id'    => $id
				    ) 
				); 
			
				header("Location: index.php?page=$page");
				
		exit;
		break;
//-------------------------------------- Move Link Up -------------------------------------------------------------
    case ("move_link_up");
        $page = $_SESSION[page];
        $db = connect();
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $result_links = mysql_query ($sql_links, $db);
        $row_links = mysql_fetch_array($result_links, MYSQL_ASSOC);
        $links_array = unserialize($row_links[content]);
        $last_position = count($links_array[link]);
        if($id != 0){
            $current_link = $links_array['link'][$id];
            $current_words = $links_array['words'][$id];
            $above_link = $links_array['link'][$id-1];
            $above_words = $links_array['words'][$id-1];
            array_splice($links_array['link'],$id-1,1,$current_link);
            array_splice($links_array['words'],$id-1,1,$current_words);
            array_splice($links_array['link'],$id,1,$above_link);
            array_splice($links_array['words'],$id,1,$above_words);
        }
        $links = serialize($links_array);
        $sql_links = "UPDATE blocks SET content = '$links' WHERE id = '$block_id'  ";
        $result_links = mysql_query ($sql_links, $db); 
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
    break;
    
//----------------------------------- Move Link Down ------------------------------------------------------------------
    case ("move_link_down");
        $page = $_SESSION[page];
        $db = connect();
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $result_links = mysql_query ($sql_links, $db);
        $row_links = mysql_fetch_array($result_links, MYSQL_ASSOC);
        $links_array = unserialize($row_links[content]);
        $last_position = count($links_array[link]);
        if($id != $last_position){
            $current_link = $links_array['link'][$id];
            $current_words = $links_array['words'][$id];
            $below_link = $links_array['link'][$id+1];
            $below_words = $links_array['words'][$id+1];
            array_splice($links_array['link'],$id+1,1,$current_link);
            array_splice($links_array['words'],$id+1,1,$current_words);
            array_splice($links_array['link'],$id,1,$below_link);
            array_splice($links_array['words'],$id,1,$below_words);
        }
        $links = serialize($links_array);
        $sql_links = "UPDATE blocks SET content = '$links' WHERE id = '$block_id'  ";
        $result_links = mysql_query ($sql_links, $db); 
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
        break;
//----------------------------------- Finish edit links --------------------------------------------------------------
    case ("finish_edit_links"); 
        $page = $_SESSION['page'];
        $db = connect_pdo();
        
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $stmt = $db->query($sql_links);
	    	$rows = $stmt->fetchAll();
	    	$row_links = $rows[0];
        
        $links_array = unserialize($row_links['content']);
        $last_position = count($links_array['link']);
        
        if ($question == "Yes"){
                array_splice($links_array['link'],$id,1);
                array_splice($links_array['words'],$id,1);
                array_splice($links_array['new_tab'],$id,1);
        } 
        else if($link_action == 'edit'){
            if(isset($_POST['change_to_title'])){
              array_splice($links_array['words'],$id,1,$_POST['change_to_title']);
              $words = $_POST['change_to_title'];
            }
            else{
            array_splice($links_array['link'],$id,1,$link);
            array_splice($links_array['words'],$id,1,$words);
            array_splice($links_array['new_tab'],$id,1,$new_tab);
            //echo "id =".$id."<br>";
            //exit;
            }
        }
        else if($link_action == 'move_up' && $id != 0){
            $current_link = $links_array['link'][$id];
            $current_words = $links_array['words'][$id];
            $above_link = $links_array['link'][$id-1];
            $above_words = $links_array['words'][$id-1];
            array_splice($links_array['link'],$id-1,1,$current_link);
            array_splice($links_array['words'],$id-1,1,$current_words);
            array_splice($links_array['link'],$id,1,$above_link);
            array_splice($links_array['words'],$id,1,$above_words);
        }
        else if($link_action == 'move_dwn' && $id != $last_position){
            $current_link = $links_array['link'][$id];
            $current_words = $links_array['words'][$id];
            $below_link = $links_array['link'][$id+1];
            $below_words = $links_array['words'][$id+1];
            array_splice($links_array['link'],$id+1,1,$current_link);
            array_splice($links_array['words'],$id+1,1,$current_words);
            array_splice($links_array['link'],$id,1,$below_link);
            array_splice($links_array['words'],$id,1,$below_words);
        }
        print "<pre>";
      print_r($links_array);
      print_r($_POST);
      print "</pre>";
       
        $links = serialize($links_array);
        //$links = mysql_real_escape_string($links);
        $sql_links = "UPDATE blocks SET content = :content WHERE id = :id  ";
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $links, 
				        ':id'    => $block_id
				    ) 
				);
        
        /*
        if($question != "Yes"){    
            //---- search all other links for the same link, and change the words to the new words
            $sql_words = "SELECT * from blocks where type='link' and content like '%$link%'";
            $result_words = mysql_query ($sql_words, $db);
            while($row_words = mysql_fetch_array($result_words, MYSQL_ASSOC)){
                $current_id = $row_words[id];
                $current_links_array = unserialize($row_words[content]);
                $current_links_array_link = $current_links_array['link'];
                //print_r($current_links_array);echo "<br>";
                $place_in_array = array_search($link,$current_links_array_link);
                //echo "block_id=".$current_id." link=".$link." place_in_array=".$place_in_array." words=".$words."<br>";
                array_splice($current_links_array['words'],$place_in_array,1,$words);
                //print_r($current_links_array); echo "<br>";
                $content_this_block = serialize($current_links_array);
                $sql_update_all_links = "UPDATE blocks SET content = '$content_this_block' where id = $current_id";
                echo $sql_update_all_links."<br>";
                $result_all_links = mysql_query($sql_update_all_links,$db);
            }
            //exit;
        }
        */
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
        break;

//----------------------------- Finish Enter download ----------------------------------------------------------      
     case ("finish_enter_download");      
        foreach ($_REQUEST as $param_name => $param_val) {
				if($param_name == "PHPSESSID"){continue;}
		    $$param_name = $param_val;
				} 
        $db = connect_pdo();
        
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $stmt = $db->query($sql_links);
	    	$rows = $stmt->fetchAll();
        
        $row_links = $rows[0];
        
        $links_array = unserialize($row_links['content']);
                              
        $links_array['title'][] = $title;
        $links_array['desc'][] = $desc;
        $file_path = "download/".$_FILES['download_file']['name'];
        $links_array['link'][] = $file_path;
        echo "tmp_name=".$_FILES['download_file']['tmp_name']."<br>";
        move_uploaded_file($_FILES['download_file']['tmp_name'], $file_path);
        chmod($file_path, 0777);
        ?>
        <pre>
        <?php
        print_r($links_array);
        ?>
	      </pre>
	      <?php
        $links = serialize($links_array);
        
        $sql_links = "UPDATE blocks SET content = :content WHERE id = :id ";
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $links, 
				        ':id'    => $block_id
				    ) 
				);
        
        
        $page = $_SESSION['page'];
        
        header("Location: index.php?page=$page"); 
        break;
//----------------------------------- Finish edit download --------------------------------------------------------------
    case ("finish_edit_download"); 
        $page = $_SESSION['page'];
        $db = connect_pdo();
               
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $stmt = $db->query($sql_links);
	    	$rows = $stmt->fetchAll();
	    	$row_links = $rows[0];
        $links_array = unserialize($row_links['content']);
        
        
        
        if ($question == "Yes"){
                 
                unlink($links_array['link'][$id]);
                array_splice($links_array['title'],$id,1);
                array_splice($links_array['desc'],$id,1);
                array_splice($links_array['link'],$id,1);
                
        } 
        else {
            
            array_splice($links_array['title'],$id,1,$title);
            array_splice($links_array['desc'],$id,1,$desc);
            
            
        }
        
        $links = serialize($links_array);
        $sql_links = "UPDATE blocks SET content = :content WHERE id = :id  ";
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $links, 
				        ':id'    => $block_id
				    ) 
				); 
        
        
        $page = $_SESSION['page'];
       
        header("Location: index.php?page=$page");
        break;  
//--------------------------- Finish edit general --------------------------------------------------------------------
   case ("finish_edit_general"); 
        
       
       
        
        $db = connect_pdo("checkout");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = "UPDATE blocks SET content=:content WHERE id=:id";
				$stmt = $db->prepare($sql); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $content, 
				        ':id'    => $id
				    ) 
				); 
        
        header("Location: index.php?page=$page");
        break;
//--------------------------- Finish edit rss --------------------------------------------------------------------
   case ("finish_edit_rss"); 
        
        $db = connect_pdo();
        $sql = "UPDATE blocks SET  content = :content, style_type = :style_type WHERE id =:id";       
        $stmt = $db->prepare($sql); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $content, 
				        ':style_type'    => $num_items,
				        ':id' => $id
				    ) 
				); 
        
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
        break;
//--------------------------- Finish edit include --------------------------------------------------------------------
   case ("finish_edit_include"); 
        
        $db = connect_pdo();
        $sql = "UPDATE blocks SET  content = :content WHERE id =:id";       
        $stmt = $db->prepare($sql); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $content, 
				        ':id' => $id
				    ) 
				); 
        
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
        break;
//------------------------------------------------------------- Finish page --------------------------------------
case ("finish_page"); 
        
        $db = connect_pdo();
        $sql_page = "SELECT * FROM blocks where  page = '$page' AND type = 'page'";
        $stmt = $db->query($sql_page);
	    	$rows = $stmt->fetchAll();
	    	$row_page = $rows[0];
        
        
        $page_array = unserialize($row_page['content']);
                              
        $page_array['background'][1] = $mb_background;
        $page_array['border'][1] = $border;
        //$page_array['mainbox_background'][1] = $mb_background;
        $page_array['page_title'] = $page_title;
        $page_array['password'] = $password;
        
        //print_r($page_array);
        $page_sql = serialize($page_array);
        
        echo "background = ".$mb_background."<br>";
        echo "page = ".$page."<br>";
        echo "page_title = ".$page_title."<br>";
        
        
        
        $type = 'page';
        $sql_links = "UPDATE blocks SET content = :content WHERE  page = :page AND type = :type ";
        
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $page_sql, 
				        ':page'    => $page,
				        ':type' => $type
				    ) 
				);
        
        
        $page = $_SESSION['page']; 
        
        header("Location: index.php?page=$page"); 
        break;
//--------------------------------------------------------- remove password ----------------------------------------------------
case ("remove_password");
        $db = connect_pdo();
        $sql_page = "SELECT * FROM blocks where  page = '$page' AND type = 'page'";
        $stmt = $db->query($sql_page);
	    	$rows = $stmt->fetchAll();
	    	$row_page = $rows[0];
        $page_array = unserialize($row_page['content']);
        //unset ($page_array['password']);
        $page_array['password'] = '';
        ?><pre><?php
        print_r($page_array);
        ?></pre><?php
        $page_sql = serialize($page_array);
        $this_page = 'page';
        $sql_links = "UPDATE blocks SET content = :content WHERE  page = :page AND type = :type";
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $page_sql, 
				        ':page'    => $page,
				        ':type' => $this_page
				    ) 
				);
        $page = $_SESSION['page']; 
   
        header("Location: index.php?page=$page");

      break;
//------------------------------------------------------------ Move Block --------------------------------------------------------
case ("move_block"); 
    $db = connect_pdo();
    $sql_current = "SELECT * FROM blocks where  id = '$id' ";
    $stmt = $db->query($sql_current);
  	$rows = $stmt->fetchAll();
  	$row_current = $rows[0];
    $current_xpos = $row_current['xpos'];
    $current_ypos = $row_current['ypos'];
    $current_width = $row_current['width'];
    $current_height = $row_current['height'];
    $current_type = $row_current['type'];
    
    if ($xpos == ""){$xpos = $current_xpos;}
    if ($ypos == ""){$ypos = $current_ypos;}
    if ($width == ""){$width = $current_width;}
    if ($height == ""){$height = $current_height;}
    /*
    if($current_type == 'rss'){
    		if($ypos < 150){$ypos = 140;}
    		
		    $xpos = my_round($xpos,50); $ypos = my_round($ypos,70); $width = my_round($width,49);$height = my_round($height,69);
		}
		*/    
    $sql_block = "UPDATE blocks SET xpos = :xpos, ypos = :ypos, width = :width, height = :height WHERE id =:id";
    $stmt = $db->prepare($sql_block); 
		$result = $stmt->execute( 
		    array( 
		        ':xpos'   => $xpos, 
		        ':ypos'    => $ypos,
		        ':width' => $width,
		        ':height' => $height,
		        ':id' => $id
		    ) 
		);
    $page = $_SESSION['page'];
            
    echo "xpos = ".$xpos."<br>";
    echo "ypos = ".$ypos."<br>";
     echo "width = ".$width."<br>";
    echo "height = ".$height."<br>";
    echo "id = ".$id."<br>";
    echo "page=".$page."<br>";

    header("Location: index.php?page=$page");
    
    break;  
//-------------------------------------------------------------- General Image Upload -----------------------------------------------     
case ("general_image_upload");
    $uploadedFile = $_FILES['image']['tmp_name'];
    $imagepath = "images/".basename($_FILES['image']['name']);
    move_uploaded_file($uploadedFile, $imagepath);
    chmod($imagepath, 0777);
    
    $convert_str = "/usr/bin/convert $imagepath -resize $dimensions $imagepath";
    exec($convert_str);
    $array = getimagesize($imagepath);
            $width = $array[0];
            $height = $array[1];
    
    
    $db = connect();
    $sql_current = "SELECT * FROM blocks where  id = '$id' ";
    $result_current = mysql_query ($sql_current, $db);
    $row_current = mysql_fetch_array($result_current, MYSQL_ASSOC);
    $current_content = $row_current['content']."<div style='text-align: center'><img src='".$imagepath."' width='".$width."' height='".$height."'></div>";
    
    $current_content = addslashes($current_content);
    $sql = "UPDATE blocks SET content = '$current_content' WHERE id ='$id' ";
   
    $result_block = mysql_query ($sql, $db) or die(mysql_error()); 
    exit;
    header("Location: index.php?action=edit_general&id=$id");
      
break;

//------------------- Add Existing Top Picture ---------------------------------------------------------------
case("add_existing_top_picture");
            $db = connect();
            
            echo "existing_file = ".$existing_file."<br>";
            $sql_block = "UPDATE blocks SET content = '$existing_file' WHERE id = '$id'";
            $result_block = mysql_query ($sql_block, $db) or die(mysql_error()); 
            $page=$_SESSION['page'];
            header("Location: index.php?page=$page");


break;
//------------------------------- Finish Top Properties -----------------------------------------------------
case("finish_top_properties");
    $border = $_POST['border'];
    $picture_inc = $_POST['picture_inc'];
    $background = $_POST['background'];
    $id = $_POST['id'];
    $db = connect();
    if ($picture_inc == "no"){
        $sql_block = "UPDATE blocks SET content = '' WHERE id = '$id'";
        $result_block = mysql_query ($sql_block, $db) or die(mysql_error());
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
        }
    else {
        $sql_block = "UPDATE blocks SET border = '$border', background = '$background' WHERE id = '$id'";
        $result_block = mysql_query ($sql_block, $db) or die(mysql_error());
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
    }

break;
//--------------------------------- Finish Mainbox ----------------------------------------------------------
case ("finish_mainbox");      
        $db = connect();
        
                
       
        $sql_links = "UPDATE blocks SET background = '$background', border = '$border' WHERE id = '$id' ";
        $result_links = mysql_query ($sql_links, $db); 
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page"); 
        break;

//------------------------------------------ Image Upload -------------------------------------------------------
case ("image_upload");
    $dimensions = $_POST['dimensions'];
    
    $uploadedFile = $_FILES['image']['tmp_name'];
    $imagepath = "pageimages/".basename($_FILES['image']['name']);
    $imagepath_thumb = "pageimages/thumb_".basename($_FILES['image']['name']);
    $imagepath_original = "pageimages/original_".basename($_FILES['image']['name']);
    move_uploaded_file($uploadedFile, $imagepath_original);
    chmod($imagepath_original, 0777);
    
    
    
    $convert_str = $path_to_convert."convert $imagepath_original -resize 80x60 -quality 100 $imagepath_thumb";
    echo "path_to_convert=".$path_to_convert."<br>";
    echo "convert_str=".$convert_str."<br>";
    exec($convert_str);
    echo "imagepath_thumb = ".$imagepath_thumb."<br>";
    chmod($imagepath_thumb, 0777);
    $convert_str = $path_to_convert."convert $imagepath_original -resize $dimensions -quality 100 $imagepath";
    echo "convert_str=".$convert_str."<br>";
    exec($convert_str);
    chmod($imagepath, 0777);
    
    header("Location: index.php"); 
      
break;
//------------------------------------------ finish_whizzypic_mine_upload -------------------------------------------------------
case ("finish_whizzypic_mine_upload");
    //$real_path = realpath(dirname(__FILE__));
    
    ini_set("memory_limit","10000M");

    $dimensions = $_POST['dimensions'];
    
    $uploadedFile = $_FILES['image']['tmp_name'];
    $imagepath = "pageimages/".basename($_FILES['image']['name']);
    $imagepath_thumb = "pageimages/thumb_".basename($_FILES['image']['name']);
    $imagepath_original = "pageimages/original_".basename($_FILES['image']['name']);
    move_uploaded_file($uploadedFile, $imagepath_original);
    chmod($imagepath_original, 0777);
    
    //-- get the new dimensions from example 320x240
    $dim_array = array();
    $dim_array = explode("x",$dimensions);
    $new_width = $dim_array[0];
    $new_height = $dim_array[1];
    
    list($width, $height) = getimagesize($imagepath_original);
    
    $ratio_orig = $width/$height;
    if ($new_width/$new_height > $ratio_orig) {
       $new_width = $new_height*$ratio_orig;
    } else {
       $new_height = $new_width/$ratio_orig;
    }
       
    $image_p = imagecreatetruecolor($new_width, $new_height);
    $image = imagecreatefromjpeg($imagepath_original);
    $response = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    imagejpeg($image_p, $imagepath,100);
    chmod($imagepath, 0777);
    imagedestroy($image_p);
    imagedestroy($image);
    
    $new_width = 160;
    $new_height = 120;
    if ($new_width/$new_height > $ratio_orig) {
       $new_width = $new_height*$ratio_orig;
    } else {
       $new_height = $new_width/$ratio_orig;
    }
    $image_p = imagecreatetruecolor($new_width, $new_height);
    $image = imagecreatefromjpeg($imagepath_original);
    $response = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    imagejpeg($image_p, $imagepath_thumb,100);
    chmod($imagepath_thumb, 0777);
    imagedestroy($image_p);
    imagedestroy($image);
    
    /*
    $convert_str = $path_to_convert."convert $imagepath_original -resize 80x60 -quality 100 $imagepath_thumb";
    echo "path_to_convert=".$path_to_convert."<br>";
    echo "convert_str=".$convert_str."<br>";
    exec($convert_str);
    echo "imagepath_thumb = ".$imagepath_thumb."<br>";
    chmod($imagepath_thumb, 0777);
    $convert_str = $path_to_convert."convert $imagepath_original -resize $dimensions -quality 100 $imagepath";
    echo "convert_str=".$convert_str."<br>";
    exec($convert_str);
    chmod($imagepath, 0777);
    */
    
  
    header("Location: index.php?existing_picture=$imagepath_thumb"); 
      
break;
//----------------------------------------- Finish manage picture ----------------------------------------------
case ("finish_manage_picture");
    $picture_thumb = $_POST['existing_picture'];
    $delete = $_POST['delete'];
    $resize = $_POST['resize'];
    $find_thumb = strpos($picture_thumb,"thumb_")+6;
    $picture = "pageimages/".substr($picture_thumb,$find_thumb);
    $picture_original = "pageimages/original_".substr($picture_thumb,$find_thumb);
    $page = $_SESSION['page'];
    echo "find_thumb = ".$find_thumb."<br>";
    echo "picture_thumb = ".$picture_thumb."<br>";
    echo "picture_original = ".$picture_original."<br>";
    echo "picture = ".$picture."<br>";
    if ($delete == "yes"){
        unlink($picture_thumb);
        unlink($picture_original);
        unlink($picture);
        header("Location: index.php?page=$page"); 
      } 
    else{
        $convert_str = $path_to_convert."convert $picture_original -resize $resize -quality 100 $picture";
        exec($convert_str);
        chmod($picture, 0777);
        header("Location: index.php?page=$page");
    }
break;
//------------------------------------------ Delete_top_pic ----------------------------------------------------------
case("delete_top_pic");
    $id = $_GET['id'];
    $page = $_SESSION['page'];
    $existing_file = $_GET['existing_file'];
    $file_array = explode('/',$existing_file);
    
    $thumb_file = $file_array[0]."/thumb_".$file_array[1];
    
    echo "existing_file=".$existing_file." thumb_file=".$thumb_file;
   
    unlink($existing_file);
    unlink($thumb_file);
   
    header("Location: index.php?page=$page");
    exit;

break;
//----------------------------------------- finish_whizzypic_mine_manage ----------------------------------------------
case ("finish_whizzypic_mine_manage");
    
    $picture_thumb = $_POST['existing_picture'];
    $delete = $_POST['delete'];
    $resize = $_POST['resize'];
    $find_thumb = strpos($picture_thumb,"thumb_")+6;
    $picture = "pageimages/".substr($picture_thumb,$find_thumb);
    $picture_original = "pageimages/original_".substr($picture_thumb,$find_thumb);
    $page = $_SESSION['page'];
    echo "find_thumb = ".$find_thumb."<br>";
    echo "picture_thumb = ".$picture_thumb."<br>";
    echo "picture_original = ".$picture_original."<br>";
    echo "picture = ".$picture."<br>";
    if ($delete == "yes"){
        unlink($picture_thumb);
        unlink($picture_original);
        unlink($picture);
        header("Location: index.php?page=$page"); 
      } 
    else{
        ini_set("memory_limit","10000M");
        $dim_array = array();
        $dim_array = explode("x",$resize);
        $new_width = $dim_array[0];
        $new_height = $dim_array[1];
        
        list($width, $height) = getimagesize($picture_original);
        
        $ratio_orig = $width/$height;
        if ($new_width/$new_height > $ratio_orig) {
           $new_width = $new_height*$ratio_orig;
        } else {
           $new_height = $new_width/$ratio_orig;
        }
           
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($picture_original);
        $response = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        
        imagejpeg($image_p, $picture,100);
        chmod($imagepath, 0777);
        imagedestroy($image_p);
        imagedestroy($image);
        /*
        $convert_str = $path_to_convert."convert $picture_original -resize $resize -quality 100 $picture";
        exec($convert_str);
        chmod($picture, 0777);
        */
        
        header("Location: index.php?existing_picture=$picture_thumb");
        exit;
    }
break;
//------------------------------------------ Existing Picture ---------------------------------------------------
case ("existing_picture");
    $type = get_type($id);
    
            if ($type == "picture"){
                $db = connect();
                $result_block = mysql_query ("UPDATE blocks SET content = '$existing_file' where id = '$id'", $db) or die(mysql_error());   
                ?>
                <script language="javascript" type="text/javascript">
                    self.opener.location = index.php;
                </script>
                <?php
                $page = $_SESSION['page'];
                header("Location: index.php?page=$page");
                exit;
            }
            
            $array = getimagesize($existing_file);
            $width = $array[0];
            $height = $array[1];
            $db = connect();
            $sql_current = "SELECT * FROM blocks where  id = '$id' ";
            $result_current = mysql_query ($sql_current, $db);
            $row_current = mysql_fetch_array($result_current, MYSQL_ASSOC);
            $current_content = $row_current[content]."<div style='text-align: center'><img src='".$existing_file."' border='0' width='".$width."' height='".$height."'></div>";
            $current_content = addslashes($current_content);
            $sql = "UPDATE blocks SET content = '$current_content' WHERE id ='$id' ";
            $result_block = mysql_query ($sql, $db) or die(mysql_error()); 
            
            $page = $_SESSION['page'];
            header("Location: index.php?page=$page"); 
            exit;
            break;
          

//--------------------------------------- finish blog edit ----------------------------------------------------------

case ("finish_blog_edit");
   
   
    //$content = str_replace("\'","'",$content);
    //$content = str_replace("'","\'",$content);
    
    $nowdate = date("Y-m-d");
     
    echo "content = ".$content."<br>";
    echo "id = ".$id."<br>";
    echo "blog_id = ".$blog_id."<br>";
    echo "background = ".$background."<br>";
    echo "border = ".$border."<br>";
    echo "type=".$type."<br>";
    echo "question=".$question."<br>";
   
    //$db = connect();
    $db = connect_pdo();$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($question == 'yes'){
    		$sql = "DELETE from $type  WHERE id=:id"; 
  			$stmt = $db->prepare($sql);
				$stmt->bindParam(':id', $blog_id, PDO::PARAM_INT);
				$stmt->execute();
				
    		
       
        include ("make_feed.php?table=$type");
        $page = $_SESSION['page'];
         header("Location: index.php?page=$page");
         exit;
    }
    
    elseif ($blog_id != ''){
        $temp_date = strtotime($date);
        $selected_year = date('Y', $temp_date);
        
			$sql = "UPDATE $type SET content=:content, date=:date, blog_title=:blog_title WHERE id=:id"; 
			$stmt = $db->prepare($sql); 
			$result = $stmt->execute( 
			    array( 
			        ':content'   => $content, 
			        ':date'   => $date,
			        ':blog_title'   => $blog_title,
			        ':id'    => $blog_id
			    ) 
			); 
			
			header("Location: index.php?page=$page&selected_year=$selected_year&blog_id=$blog_id"); 
			exit;
		}
        //$sql_blog = "UPDATE $type SET content = '$content', date = '$date', blog_title = '$blog_title' WHERE id = '$blog_id' ";}
    else {
    		$db = connect_pdo();
        $temp_date = strtotime($nowdate);
        $selected_year = date('Y', $temp_date);
        
        $sql = "INSERT INTO $type (date,blog_title,content) VALUES (:date, :blog_title, :content)";
			  $stmt = $db->prepare($sql) ;
				$stmt->execute(array(
								'date' => $nowdate,
				        'blog_title' => $blog_title,
				        'content' => $content
						));
    
 	 }
    
    //include ("make_feed.php");
    
    $page = $_SESSION['page'];
   
    header("Location: index.php?page=$page&selected_year=$selected_year&blog_id=$blog_id"); 

    exit;
    break;
    
//--------------------------------------- finish enter blog ----------------------------------------------------------

case ("finish_enter_blog");
    
    $content = $_POST[content];
    $type = $_POST[type];
    echo "content = ".$content."<br>";
    $nowdate = date("Y-m-d");
    $db = connect_pdo();
    
    $sql = "INSERT INTO $type (date,blog_title,content) VALUES (:date, :blog_title, :content)";
	  $stmt = $db->prepare($sql) ;
		$stmt->execute(array(
						'date' => $nowdate,
		        'blog_title' => $blog_title,
		        'content' => $content
				));
    //include ("make_feed.php");
    $page = $_SESSION['page'];
    header("Location: index.php?page=$page");
    exit;
//--------------------------------------- del blog ----------------------------------------------------------

case ("del_blog");
      $page = $_SESSION['page'];
      
      
      echo "blog_id = ".$blog_id."<br>";
      echo "id = ".$id."<br>";
      $db = connect_pdo();
      $sql_blog = "DELETE from $type where id = :id";
      
      $stmt = $db->prepare($sql_blog);
      $stmt->bindParam(':id', $blog_id, PDO::PARAM_INT);
      $stmt->execute();
      
      
      header("Location: index.php?page=$page");
      
      exit;
   
//------------------ used for testing only ------------------------------
case ("finish_colorpicker");
        echo "color = ".$color."<br>";
        
        break;
//----------------------------------------------------------------------
// ----------------------------------------- finish copy block -------------------------------------------------------------------
case ("finish_copy_block");

    $db = connect_pdo();$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_copy = "SELECT * from blocks where id='$id'";
    $stmt = $db->query($sql_copy);
	  $rows = $stmt->fetchAll();
	  $row_copy = $rows[0];
    
    $type=$row_copy['type'];
    $xpos=$row_copy['xpos'];
    $ypos=$row_copy['ypos'];
    $height=$row_copy['height'];
    $width=$row_copy['width'];
    $style_type=$row_copy['style_type'];
    $background=$row_copy['background'];
    $border=$row_copy['border'];
    $content = $row_copy['content'];
    
    $sql_copy = "SELECT max(block) from blocks where page='$new_page'";
    $stmt = $db->query($sql_copy);
	  $rows = $stmt->fetchAll();
    $row_copy = $rows[0];
    
    $max_block = $row_copy[0];
    $max_block ++;
    
    
    $sql = "INSERT INTO blocks (page,block,type,xpos,ypos,height,width,style_type,background,border,content) VALUES (:page,:block,:type,:xpos,:ypos,:height,:width,:style_type,:background,:border,:content)";
	  $stmt = $db->prepare($sql) ;
		$stmt->execute(array(
						'page' => $new_page,
		        'block' => $max_block,
		        'type' => $type,
		        'xpos' => $xpos,
		        'ypos' => $ypos,
		        'height' => $height,
		        'width' => $width,
		        'style_type' => $style_type,
						'background' => $background,
						'border' => $border,
						'content' => $content
		    ));
    
   
   
    header("Location: index.php?page=$new_page");
    
    
    
      
    





exit;
//--------------------------------------- finish edit block parameters ----------------------------------------------------------

case ("finish_edit_block_parameters");
    
    if($height <> ''){
        $db = connect_pdo();
        $sql_links = "UPDATE blocks SET height = :height Where id = :id";
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':height'   => $height, 
				        ':id'    => $id
				    ) 
				); 
        $page = $_SESSION['page'];
        header("Location: index.php?page=$page");
        exit; 
    }
    
    $db = connect_pdo();
    if($style_type != ''){
      $sql_links = "UPDATE blocks SET background = :background, border = :border, style_type = :style_type,type = :type, title = :title WHERE id = :id ";
    	$stmt = $db->prepare($sql_links); 
			$result = $stmt->execute( 
			    array( 
			        ':background'   => $background, 
			        ':border'    => $border,
			        ':style_type'   => $style_type, 
			        ':type'    => $block_type,
			        ':title'   => $block_title, 
			        ':id'    => $id
			    ) 
			); 
    
    }
    
    else {
      $sql_links = "UPDATE blocks SET background = :background, border = :border, type = :type WHERE id = :id ";
      $stmt = $db->prepare($sql_links); 
			$result = $stmt->execute( 
			    array( 
			        ':background'   => $background, 
			        ':border'    => $border,
			        ':type'    => $block_type,
			        ':id'    => $id
			    ) 
			); 
    }
       
   
    //-- Block title ----
    
    if($block_title != ""){
        $sql_links = "UPDATE blocks SET title = :title WHERE id = :id  ";
        $stmt = $db->prepare($sql_links); 
				$result = $stmt->execute( 
				    array( 
				        ':title'   => $block_title, 
				        ':id'    => $id
				    ) 
				); 
     }
        
    $page = $_SESSION['page'];
    header("Location: index.php?page=$page");
    exit; 
//--------------------------------------- finish download parameters ----------------------------------------------------------

case ("finish_download_parameters");
    $border = $_POST[border];
    $background = $_POST[background];
    $id = $_POST[id];
    $block_type = $_POST[block_type];
    $block_title = $_POST[block_title];
    
    $db = connect();
    $sql_links = "SELECT * FROM blocks where  id = '$id' ";
    $result_links = mysql_query ($sql_links, $db);
    $row_links = mysql_fetch_array($result_links, MYSQL_ASSOC);
    $links_array = unserialize($row_links[content]);
    if ($links_array['block_title'][0]){array_splice($links_array['block_title'],0,1,$block_title);}
    else {$links_array['block_title'][0] = $block_title;}
    $links = serialize($links_array);
    
    echo "id = ".$id."<br>";
    echo "block_title = ".$block_title."<br>";
    echo "background = ".$background."<br>";
    echo "border = ".$border."<br>";
    echo "block_type = ".$block_type."<br>";
    $db = connect();
    $sql_links = "UPDATE blocks SET background = '$background', border = '$border', type = '$block_type', content = '$links' WHERE id = '$id' ";
    $result_links = mysql_query ($sql_links, $db);
    echo "sql_links=".$sql_links."<br>";
    
    $page = $_SESSION['page'];
    header("Location: index.php?page=$page");
    exit; 
    
//--------------------------------------- Add Page ----------------------------------------------------------

case ("add_page");
    
    $db = connect_pdo();
    $sql = "SELECT MAX(page) from blocks";
    $stmt = $db->query($sql);
	  $rows = $stmt->fetchAll();
	  $row = $rows[0];
    $max_page = $row[0];
    $new_page = $max_page+1;
    
    //---------- New block type=page, block=0----------
    $sql = "SELECT * from blocks where page = '1' and type='page'";
    $stmt = $db->query($sql);
	  $rows = $stmt->fetchAll();
	  $row = $rows[0];
    $page_array = unserialize($row['content']);
    $background = $page_array['background'][1];
    $mainbox_background = $page_array['mainbox_background'][1];
    $page_title = $page_array['page_title'];
    $page_array['background'][1] = $background;
    $page_array['mainbox_background'][1] = $mainbox_background;
    $page_array['page_title'] = $page_title."2";
    $page_sql = serialize($page_array);
    
    $the_block = '0';$the_type='page';
    $sql = "INSERT INTO blocks (page,block,type,content) VALUES (:page, :block, :type, :content)";
		  $stmt = $db->prepare($sql) ;
			$stmt->execute(array(
							'page' => $new_page,
			        'block' => $the_block,
			        'type' => $the_type,
			        'content' => $page_sql
			));
    
    //------------- New block type=mainbox block=1------------
    $sql = "SELECT * from blocks where page = '1' and type='mainbox'";
    $stmt = $db->query($sql);
	  $rows = $stmt->fetchAll();
	  $row = $rows[0];
    $xpos=$row['xpos']; $ypos=$row['ypos']; $width=$row['width']; $height=$row['height']; $background=$row['background']; $border=$row['border'];$content=$row['content'];
    
    $the_block='1';$the_type='mainbox';
    $sql = "INSERT INTO blocks (page,block,type,xpos,ypos,width,height,background,border,content) VALUES (:page,:block,:type,:xpos,:ypos,:width,:height,:background,:border,:content)";
		  $stmt = $db->prepare($sql) ;
			$stmt->execute(array(
							'page' => $new_page,
			        'block' => $the_block,
			        'type' => $the_type,
			        'xpos' => $xpos,
			        'ypos' => $ypos,
			        'width' => $width,
			        'height' => $height,
			        'background' => $background,
			        'border' => $border,
			        'content' => $page_sql
			));
    
    //------------ New block type=top block=2--------------
    $sql = "SELECT * from blocks where page = '1' and type='top'";
    $stmt = $db->query($sql);
	  $rows = $stmt->fetchAll();
	  $row = $rows[0];
    $xpos=$row['xpos']; $ypos=$row['ypos']; $width=$row['width']; $height=$row['height']; $background=$row['background']; $border=$row['border'];$content=$row['content'];
    
    $the_block='2';$the_type='top';
    $sql = "INSERT INTO blocks (page,block,type,xpos,ypos,width,height,background,border,content) VALUES (:page,:block,:type,:xpos,:ypos,:width,:height,:background,:border,:content)";
		  $stmt = $db->prepare($sql) ;
			$stmt->execute(array(
							'page' => $new_page,
			        'block' => $the_block,
			        'type' => $the_type,
			        'xpos' => $xpos,
			        'ypos' => $ypos,
			        'width' => $width,
			        'height' => $height,
			        'background' => $background,
			        'border' => $border,
			        'content' => $content
			));
    
    //----------- New block type=link block=3------------
    $sql = "SELECT * from blocks where page = '1' and type='link' and parameter1 = 'site_nav' ORDER by id ASC";
    $stmt = $db->query($sql);
	  $rows = $stmt->fetchAll();
	  $row = $rows[0];
	  
    $xpos=$row['xpos']; $ypos=$row['ypos']; $width=$row['width']; $height=$row['height']; $background=$row['background']; $border=$row['border'];$content=$row['content'];
    
   
    $links_array = unserialize($row['content']);
    
    
        $new_link = "index.php?page=".$new_page;
        $new_words = "Page ".$new_page;
        $new_tab = 'off';
        $links_array['link'][] = $new_link;
        $links_array['words'][] = $new_words;
        $links_array['new_tab'][] = $new_tab;
        
   
    
    $links = serialize($links_array);
    
    $the_block='3';$the_type='link';$the_style_type='leftside1';$the_parameter1='site_nav';
    $sql = "INSERT INTO blocks (page,block,type,xpos,ypos,width,height,background,border,content,style_type,parameter1) VALUES (:page,:block,:type,:xpos,:ypos,:width,:height,:background,:border,:content,:style_type,:parameter1)";
		  $stmt = $db->prepare($sql) ;
			$stmt->execute(array(
							'page' => $new_page,
			        'block' => $the_block,
			        'type' => $the_type,
			        'xpos' => $xpos,
			        'ypos' => $ypos,
			        'width' => $width,
			        'height' => $height,
			        'background' => $background,
			        'border' => $border,
			        'content' => $links,
			        'parameter1' => $the_parameter1,
			        'style_type' => $the_style_type
			));
    
    
    
    //---------Update links on other pages to add this page --------
    $count = $new_page-1;
    while ($count >0 ){
       $sql = "SELECT * from blocks where page = '$count' and type='link' and parameter1='site_nav'"; 
       $stmt = $db->query($sql);
			 $rows = $stmt->fetchAll();
			 $row = $rows[0];
       $id = $row['id'];
       $links_array = unserialize($row['content']);
       $links_array['link'][] = "index.php?page=".$new_page;
       $links_array['words'][] = "Page ".$new_page;
       $links_array['new_tab'][] = $new_tab;
       $links = serialize($links_array);
       $sql="UPDATE blocks set content = :content where id = :id";
       $stmt = $db->prepare($sql); 
				$result = $stmt->execute( 
				    array( 
				        ':content'   => $links, 
				        ':id'    => $id
				    ) 
				); 
			
       $count = $count -1;
    }
    
    header("Location: index.php?page=$new_page");
    exit; 

//--------------------------------------- Add block ----------------------------------------------------------

case ("add_block");
    
    $page = $_SESSION['page'];
    
    echo "block_type=".$block_type."<br>";
    $db = connect_pdo(); $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT MAX(block) from blocks where page = '$page'";
    $stmt = $db->query($sql);
	  $rows = $stmt->fetchAll();
	  $row = $rows[0];
    $max_block = $row[0];
    $new_block = $max_block +1;
    if ($block_type == "picture"){$content = "pageimages/no_picture.jpg";}
        else {$content = "";}
    
    if ($block_type == "blog"){
    		
        $sql_blog = "SELECT MAX(type) from blocks where type like 'blog%' ";
        $stmt = $db->query($sql_blog);
			  $rows = $stmt->fetchAll();
			  $row_blog = $rows[0];
        $max_blog = $row_blog[0];
        $new_blog_num = substr($max_blog,4)+1;
        $new_blog_name = "blog".$new_blog_num;
        echo "max_blog = ".$max_blog."<br>";
        echo "new_blog_num = ".$new_blog_num."<br>";
        echo "new_blog_name = ".$new_blog_name."<br>";
        
        $sql = "CREATE TABLE `$new_blog_name` (
					  `id` int(11) NOT NULL,
					  `date` date NOT NULL DEFAULT '0000-00-00',
					  `blog_title` varchar(255) NOT NULL DEFAULT '',
					  `content` text NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";
				
        $stmt = $db->exec($sql); 
        
        $sql = "ALTER TABLE `$new_blog_name`
  				ADD PRIMARY KEY (`id`);";
  			$stmt = $db->exec($sql); 
      	$sql = "ALTER TABLE `$new_blog_name`
  				MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
  			$stmt = $db->exec($sql); 
        
        $block_type = $new_blog_name;
        //-- add index block for this blog
        $style_type = "index";
        $new_block ++;
        
        $width = "200";
        $height = "450";
        $xpos='000';$ypos='300';$background='#cccccc';$border='1px solid black';$title='Blog Index';
        
        $sql = "INSERT INTO blocks (page,block,type,xpos,ypos,width,height,background,border,content,style_type,title) 
        VALUES (:page,:block,:type,:xpos,:ypos,:width,:height,:background,:border,:content,:style_type,:title)";
			  $stmt = $db->prepare($sql) ;
				$stmt->execute(array(
								'page' => $page,
				        'block' => $new_block,
				        'type' => $block_type,
				        'xpos' => $xpos,
				        'ypos' => $ypos,
				        'width' => $width,
				        'height' => $height,
				        'background' => $background,
				        'border' => $border,
				        'content' => $content,
				        'style_type' => $style_type,
				        'title' => $title
				
				    ));
        
        
        $new_block ++; 
    }
    if ($block_type == "link"){
        $style_type = "leftside1";
        $width = "141";
        $height = "270";      
        }
      else{
      $style_type = "";
      $width = "680";
      $height = "450";
      }
    
    
   
   /* $sql_block = "INSERT into blocks SET page = '$page', block = '$new_block', type = '$block_type', xpos='300', ypos='300', width='$width',
     height='$height', background='#cccccc', border= '1px solid black;', content = '$content', style_type = '$style_type' ";
     */
    $xpos='300';$ypos='300';$background='#cccccc';$border='1px solid black';$title='';
    if ($block_type=="rss"){
      $style_type = 3;
      $background = "#FFFFFF";
    }
    $sql = "INSERT INTO blocks (page,block,type,xpos,ypos,width,height,background,border,content,style_type,title) 
        VALUES (:page,:block,:type,:xpos,:ypos,:width,:height,:background,:border,:content,:style_type,:title)";
			  $stmt = $db->prepare($sql) ;
				$stmt->execute(array(
								'page' => $page,
				        'block' => $new_block,
				        'type' => $block_type,
				        'xpos' => $xpos,
				        'ypos' => $ypos,
				        'width' => $width,
				        'height' => $height,
				        'background' => $background,
				        'border' => $border,
				        'content' => $content,
				        'style_type' => $style_type,
				        'title' => $title
				
				    ));
    
    
    
    $page = $_SESSION['page'];
    header("Location: index.php?page=$page");
    exit; 
    
//--------------------------------------- Delete block ----------------------------------------------------------

case ("delete_block");
   
    $page = $_SESSION['page'];
   
    echo "question =".$question."<br>";
    echo "id=".$id."<br>";
    
    
    
    
    if ($question == "yes"){
        $db = connect_pdo();$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT type from blocks where id = '$id'";
        $stmt = $db->query($sql);
	    	$rows = $stmt->fetchAll();
	    	$row_test = $rows[0];
        $type= $row_test['type'];
        echo "type=".$type."<br>";
        echo "blog_and_block=".$blog_and_block."<br>";
        
        $sql_block = "DELETE from blocks where id = :id";
        $stmt = $db->prepare($sql_block);
	      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	      $stmt->execute();
        
        if (stristr($type,'blog') && $blog_and_block == 'yes'){
        	 $sql=$db->prepare("DROP TABLE  `$type` ");
        	 $sql->execute();
        	 $sql = "DELETE from blocks where type = :type";
        	 $stmt = $db->prepare($sql);
        	 $stmt->bindParam(':type', $type, PDO::PARAM_INT);
	      	 $stmt->execute();
        } 
    }
    $page = $_SESSION['page'];

    header("Location: index.php?page=$page");
    exit; 
    
//--------------------------------------- Delete page ----------------------------------------------------------

case ("del_page");
    
    $page = $_SESSION['page'];
        if ($page != '1'){
            $db = connect();
            $sql = "SELECT * from blocks where type = 'link'";
            $result = mysql_query ($sql, $db);
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                $links_array = unserialize($row[content]);
                ?><pre><?php print_r($links_array);?></pre><?php
                $count = count($links_array['link']);
                echo "count=".$count."<br>";
                while($count > 0){
                    //echo $links_array['link'][$count]."<br>";
                    if (strpos($links_array['link'][$count],"page=".$page) !== false){
                            echo $links_array['link'][$count]."<br>";
                            array_splice($links_array['link'],$count,1);
                            array_splice($links_array['words'],$count,1);
                            ?><pre><?php print_r($links_array);?></pre><?php
                            $id = $row['id'];
                            $links = serialize($links_array);
                            $links = mysql_real_escape_string($links);
                            $sql_links = "UPDATE blocks SET content = '$links' WHERE id = '$id'  ";
                            echo "sql_links=".$sql_links."<br>";
                            $result_links = mysql_query ($sql_links, $db); 
                    }
                $count --;
                }    
            
            
            }
           
            $sql = "DELETE from blocks where page = '$page' ";
            $result = mysql_query ($sql, $db);
            $page = $_SESSION['page']-1;
        }
    header("Location: index.php?page=$page");
    exit; 


//-------------------------------------------- Upload video finish -------------------------------------------------
case ("upload_video_finish");
          
				  if(count($_FILES) > 0){
                $uploaddir = "c:/xampp/htdocs/Mypage/videos/";
                $uploaddir = dirname(__FILE__)."/videos/";
                echo "uploaddir=".$uploaddir;
                
                $arrfile = pos($_FILES);
                $arrfile['name'] = stripslashes($arrfile['name']);
                $arrfile['name'] = str_replace (" ", "_", $arrfile['name']);
                $videoname = basename($arrfile['name']);
              	$uploadfile = $uploaddir . basename($arrfile['name']);
              	$now_date = date("Y-m-d");
              	
              	if (move_uploaded_file($arrfile['tmp_name'], $uploadfile)){
              	   chmod($uploadfile, 0777);
              	   $db=connect_pdo();
              	   $the_status = 'uploaded';
                   $sql = "INSERT INTO videos SET status = :status, filename = :filename, date=:date, playlist=:playlist";
                   $stmt = $db->prepare($sql); 
									$result = $stmt->execute( 
									    array( 
									        ':status'    => $the_status,
									        ':filename' => $videoname,
									        ':date' => $now_date,
									        ':playlist' => $playlist
									    ) 
									);
                   
                   
                   
                echo "File is valid, and was successfully uploaded.\n";
              	}
              }
              else{
              	echo 'No files sent. Script is OK!'; //Say to Flash that script exists and can receive files
              }
              $page = $_SESSION['page'];
              header("Location: index.php?page=$page&action=convert_video&videoname=$videoname");
              break;
              
			case ("video_convert");
			      $db=connect_pdo();
			      $now_status = 'uploaded';
			      $new_status = 'complete';
			      $sql = "UPDATE videos SET title = :title, status = :new_status where status = :now_status";
			      $stmt = $db->prepare($sql); 
						$result = $stmt->execute( 
						    array( 
						        ':title'   => $title, 
						        ':now_status'    => $now_status,
						        ':new_status' => $new_status
						    ) 
						);
			      
           
            /*
        		$sql = "SELECT * FROM videos   where status = 'uploaded'";
        		$result = mysql_query ($sql, $db);
        		$row = mysql_fetch_array($result, MYSQL_ASSOC);
        		$videoname = $row['videoname'];
        		
        		
        		//-- Convert video to flv ---
            exec("/usr/bin/ffmpeg -i $videoname -f flv  -ab 48 -s 480x320 -b 896k   $videoname.flv  ");
            //exec("/usr/bin/ffmpeg  -i $videoname -y -b 800k -r 25 -f flv -vcodec flv  -ar 44100 $videoname.flv  ");
            chmod("$videoname.flv", 0777);
        
            //-- Set up variables for ftp upload and jump to upload.php --
            $source_file = $videoname.".flv";
            $destination_file = "/public_html/videos/".$videoname.".flv";
			      
            $sql = "UPDATE videos SET status = 'converted' where  status = 'uploaded'";
            $result = mysql_query ($sql, $db) or die(mysql_error());
            $page = $_SESSION['page'];
            header("Location: index.php?page=$page&action=ftp_video&source_file=$source_file&destination_file=$destination_file&title=$title&videoname=$videoname");
						*/
						header("Location: index.php?page=$page");
			      break;
			       
			case ("video_upload");
			      
			      $db=connect();
			      //-- FTP it up to shagdaddymusic.cdfmarketing.com/videos --
            $ftp_server = "ftp.cdfmarketing.com";
            $ftp_user_name = "tunes354";
            $ftp_user_pass = "bear3540";
            $conn_id = ftp_connect($ftp_server); 
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
            if ((!$conn_id) || (!$login_result)) { 
                    echo "FTP connection has failed!";
                    echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
                    exit; 
                } else {
                    echo "<br>Connected to $ftp_server, for user $ftp_user_name";
                }
            echo "destination_file=".$destination_file."<br>";
            echo "source_file=".$source_file."<br>";
            
            // IMPORTANT!!! turn passive mode on
            ftp_pasv ( $conn_id, true );
            
            $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 
            if (!$upload) { 
                    echo "FTP upload has failed!";
                } else {
                    echo "Uploaded $source_file to $ftp_server as $destination_file";
                }
            ftp_close($conn_id); 
        
            //-- Insert link in songs/video database ---
            $file_link = "http://www.cdfmarketing.com/videos/".$source_file;
            $nowdate = date("Y-m-d");
             
           	$db=connect();
            $sql = "UPDATE videos SET filename = '$file_link', title = '$title', date = '$nowdate', status = 'complete' where status = 'converted'";
          	$result = mysql_query ($sql, $db) or die(mysql_error());
        
            //-- Delete old files on local server --
            unlink($videoname);
            unlink($source_file);
            
            //-- Jump to video page on shagdaddymusic.com --
            $page = $_SESSION['page'];
            header("Location: index.php?page=$page");
			
			       break;
  
      case ("video_data_edit");
            $page=$_SESSION['page'];
            $db=connect_pdo();
            $sql = "UPDATE videos SET title = :title, date = :date, playlist=:playlist where id = :id";
            $stmt = $db->prepare($sql); 
						$result = $stmt->execute( 
						    array( 
						        ':title'    => $title,
						        ':date' => $date,
						        ':playlist' => $new_playlist,
						        ':id' => $id
						    ) 
						); 
          	
            header("Location: index.php?page=$page&action=edit_video_data&playlist=$new_playlist");
            exit;
      break;
      
      case("video_delete");
      		$db = connect_pdo();
      		$sql = "SELECT * from videos where id = '$id'";
      		$stmt = $db->query($sql);
	    		$rows = $stmt->fetchAll();
	    		$row = $rows[0];
	    		$filename = "videos/".$row['filename'];
	    		unlink($filename);
	    		echo "filename=".$filename;
	    		
      		$sql = "DELETE FROM videos WHERE id =  :id";
          $stmt = $db->prepare($sql);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute(); 
          header("Location: index.php?page=$page");
          exit;
      		
      break;
      
      case("video_width_enter");
      			$db=connect_pdo();
            $sql = "UPDATE blocks SET style_type = :style_type, parameter1 = :playlist where id = :id";
            $stmt = $db->prepare($sql); 
						$result = $stmt->execute( 
						    array( 
						        ':style_type'   => $video_width, 
						        ':playlist' => $playlist,
						        ':id' => $id
						    ) 
						); 
      header("Location: index.php?page=$page");
      break;
      
      case("playlist_title");
      			$db=connect_pdo();
            $sql = "UPDATE videos SET playlist_title = :playlist_title where playlist = :playlist";
            $stmt = $db->prepare($sql); 
						$result = $stmt->execute( 
						    array( 
						        ':playlist_title'   => $playlist_title, 
						        ':playlist' => $playlist
						    ) 
						); 
      		header("Location: index.php?page=$page&action=edit_video_data&playlist=$playlist");
      break;
      
      case("smugmug_username");
      			$db=connect_pdo();
      			$the_id = '1';
      			$sql = "SELECT * from smugmug where id = '1'";
      			$stmt = $db->query($sql);
	    			$rows = $stmt->fetchAll();
	    			if($rows){
		      			$sql = "UPDATE smugmug SET gallery_key = :gallery_key where id = :id";
		            $stmt = $db->prepare($sql); 
								$result = $stmt->execute( 
								    array( 
								        ':gallery_key'   => $smugmug_username, 
								        ':id' => $the_id
								    ) 
								);
						}
						else {
							$the_date = '2000-01-01';
							$sql = "INSERT INTO smugmug (id,gallery_id,gallery_key) VALUES (:id,:gallery_id,:gallery_key)";
						  $stmt = $db->prepare($sql) ;
							$stmt->execute(array(
											'id' => $the_id,
											'gallery_id' => $the_date,
											'gallery_key' => $smugmug_username
							
							    ));
							
						}
					
      header("Location: blog_edit.php?type=$type&blogid=$blogid&page=$page");
      break;
      
      
      case("copy_to_database");
      			echo "copy_to_database";
      			$found_blog = 'n';
      			$db=connect_pdo();
      			$sql = "SELECT * from blocks where id = '$id'";
      			echo $sql;
      			$stmt = $db->query($sql);
	    			$rows = $stmt->fetchAll();
	    			$row = $rows[0];
	    			$from_content = $row['content'];$from_type=$row['type'];$from_xpos=$row['xpos'];$from_ypos=$row['ypos'];
	    			$from_width=$row['width'];$from_height=$row['height'];$from_title=$row['title'];$from_style_type=$row['style_type'];
	    			$from_background=$row['background'];$from_border=$row['border'];$from_parameter1=$row['parameter1'];
	    			
	    			
	    			
	    			$dsn1 = "mysql:dbname=$dbase_name;host=localhost;";
        try {
            $db1 = new PDO($dsn1, $dbase_user, $dbase_pw);
        } catch(PDOException $e) {return false;
            die('Could not connect to the database:<br/>' . $e);
        } 
      			$sql = "SELECT * from blocks where page = '$page_number' order by block DESC";
      			$stmt = $db1->query($sql);
	    			$rows = $stmt->fetchAll();
	    			$row = $rows[0];
	    			$highest_block = $row['block'];
	    			$highest_block ++;
	    			
	    			$sql = "INSERT INTO blocks (page,block,type,xpos,ypos,width,height,title,style_type,background,border,parameter1,content) VALUES
	    			 (:page,:block,:type,:xpos,:ypos,:width,:height,:title,:style_type,:background,:border,:parameter1,:content)";
						  $stmt = $db1->prepare($sql) ;
							$stmt->execute(array(
											'page' => $page_number,
											'block' => $highest_block,
											'type' => $from_type,
											'xpos' => $from_xpos,
											'ypos' => $from_ypos,
											'width' => $from_width,
											'height' => $from_height,
											'title' => $from_title,
											'style_type' => $from_style_type,
											'background' => $from_background,
											'border' => $from_border,
											'parameter1' => $from_parameter1,
											'content' => $from_content
							
							    ));
							    
					 if (stristr ($from_type,"blog")){
					 			$sql = "SELECT * from blocks where page = '$page_number' and type = '$from_type' and style_type = 'index'";
		      			$stmt = $db1->query($sql);
			    			$rows = $stmt->fetchAll();
			    			$row = $rows[0];
			    			$highest_block ++;
			    			
			    			$from_content = $row['content'];$from_type=$row['type'];$from_xpos=$row['xpos'];$from_ypos=$row['ypos'];
			    			$from_width=$row['width'];$from_height=$row['height'];$from_title=$row['title'];$from_style_type=$row['style_type'];
			    			$from_background=$row['background'];$from_border=$row['border'];$from_parameter1=$row['parameter1'];
					 			
					 			$sql = "INSERT INTO blocks (page,block,type,xpos,ypos,width,height,title,style_type,background,border,parameter1,content) VALUES
	    			 (:page,:block,:type,:xpos,:ypos,:width,:height,:title,:style_type,:background,:border,:parameter1,:content)";
						  $stmt = $db1->prepare($sql) ;
							$stmt->execute(array(
											'page' => $page_number,
											'block' => $highest_block,
											'type' => $from_type,
											'xpos' => $from_xpos,
											'ypos' => $from_ypos,
											'width' => $from_width,
											'height' => $from_height,
											'title' => $from_title,
											'style_type' => $from_style_type,
											'background' => $from_background,
											'border' => $from_border,
											'parameter1' => $from_parameter1,
											'content' => $from_content
							
							    ));
					 	
						}
      		header("Location: index.php?page=$page");
      break;
  
  }      
 function roundUpToAny($n,$x=5) {
    return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
}
function my_round($number,$x=50) {
    $rounded = round( $number / $x ) * $x;
    return $rounded;
}

