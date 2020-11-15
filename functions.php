<?php  
//------------------------------------------- GET TYPE ----------------------------------------
function get_type($id){
        $db = connect();
        $sql_page = "SELECT * FROM blocks where  id = '$id' ";
        $result_page = mysql_query ($sql_page, $db);
        $row_page = mysql_fetch_array($result_page, MYSQL_ASSOC);
        $type = $row_page[type];
        return($type);
}

//------------------------------------------------ Enter LInks ----------------------------

function enter_links($block_id){
        $page = $_SESSION['page'];
        ?>
      
        <div  id="editbox" style="left: 30px; width: 290px; height: 200px;padding-right:10px;" align="right" >
            <h4 style="text-align:center;margin:0 0 5px 0;">Add A New Link</h4>
            <form name="form1"  method="post" action="mypage_finish.php?action=finish_enter_links">
            
            Enter words to display for link<img src="images/spacer.gif" width="35" height=1"><br>
            <input type="text" name="words" size="30" autocomplete="off"><br>
            Enter Link address (URL)<img src="images/spacer.gif" width="65" height=1"><br>
            <input type="text" name="link" size="30" autocomplete="off"><br>
            Open link in new Tab/Window <input type="checkbox" name="new_tab"><br><br>
            <input type="submit" value="Enter">
            <input type="hidden" name="block_id" value="<?=$block_id;?>">
            </form>
            <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
        </div>
        
    <?php }
//------------------------------------------ Finish enter links ------------------------------
    
    function finish_enter_links($page,$link,$words){
        $sql_links = "SELECT * FROM blocks where  page = '$page' and type = 'links'";
        $result_links = mysql_query ($sql_links, $db);
        $row_links = mysql_fetch_array($result_links, MYSQL_ASSOC);
        $links_array = unseraialize($row[content]);
        $links_array['link'][]  = 'newpag2';
        $links_array['words'][] =   '$words';
        print_r($links_array);
        $links = serialize($links_array);
        $sql_links = "UPDATE blocks SET content = '$links' WHERE type='$link'";
        $result_links = mysql_query ($sql_links, $db);           
        exit;
    }
//---------------------------------------------------- Edit Links ----------------------------

    function edit_links($id,$block_id){
        $page = $_SESSION['page'];
        $db = connect_pdo("checkout");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $stmt = $db->query($sql_links);
	    	$rows = $stmt->fetchAll();
	    	$row_links = $rows[0];
        $links_array = unserialize($row_links['content']);
        $current_link = $links_array['link'][$id];
        $current_words = $links_array['words'][$id];
        $current_new_tab = $links_array['new_tab'][$id];
        if(strpos($current_link,"?page=") !== false){
            $link_array = explode('=',$current_link);
            $max_page = $link_array[1];
            $sql_title = "SELECT content from blocks where page = '$max_page' and type = 'page' Limit 1";
            $stmt = $db->query($sql_title);
	    			$rows = $stmt->fetchAll();
	    			$row_title = $rows[0];
            $content_title = unserialize($row_title[0]);
            $page_title = $content_title['page_title'];
        }
        ?>
        <div id="editbox" align="right" style="padding-right:7px;">
           <h4 style="text-align:center;margin:0 0 5px 0;">Edit Link</h4>
            <form name="form1"  method="post" action="mypage_finish.php?action=finish_edit_links" >
            Move element left or right<img src="images/spacer.gif" width="25" height=1"><br>
            <input type="submit" value="Move Left" onClick="document.form1.link_action.value='move_up';">
            <input type="submit" value="Move Right" onClick="document.form1.link_action.value='move_dwn';"><img src="images/spacer.gif" width="25" height=1">
            <br><br>
            
            Edit words to display for link<img src="images/spacer.gif" width="45" height=1"><br>
            <?php if(isset($page_title)){?>Change words to page title <input type="submit" name="change_to_title" value="<?=$page_title;?>"><img src="images/spacer.gif" width="80" height=1"><br><?php } ?>
            <input type="text" size="30" name="words" value="<?=$current_words;?>" autocomplete='off'><br>
            Edit Link address<img src="images/spacer.gif" width="120" height=1"><br>
            
            <input type="text" size="30" name="link" value="<?=$current_link;?>" autocomplete='off'><br>
            Open link in new Tab/Window <input type="checkbox" name="new_tab" <?php if ($current_new_tab == 'on'){?>checked='checked' <?php } ?>><img src="images/spacer.gif" width=20 height=1"><br><br>
            <input type="hidden" name="id" value="<?=$id;?>">
            <input type="hidden" name="block_id" value="<?=$block_id;?>">
            <input type="hidden" name="link_action" value="edit">
            <input type="submit" value="Enter">
            </form>
            <div style="height: 60px; border: 2px solid black;padding: 5px;margin: 5px;text-align: center;">
                <form name="del_form"  method="post" action="mypage_finish.php?action=finish_edit_links">
                    Delete This Link<br>
                    <select name="question">
                        <option value="No" selected>No</option>
                        <option value="Yes">Yes</option>
                    </select>
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="block_id" value="<?=$block_id;?>">
                    <input type="submit" value="Delete" style="background: red;">
                </form>
            </div>
            <span class="edit_box" style="top: 2; right: 2;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
        
        </div>
            
       <?php }

//------------------------------------------------ Enter download ----------------------------

function enter_download($block_id){
        $page = $_SESSION['page'];
        ini_set('post_max_size','100M');
        ini_set('memory_limit','100M');
        ini_set('upload_max_filesize','100M');
        ?>
        <div  id="editbox" style="top: 50; left: 130; width: 260; height: 330; padding-right: 10px;" align="right" >
            <div style="font-weight:bold; text-align:center;">Add A File</div>
            
            <form name="form1"  action="mypage_finish.php?action=finish_enter_download" method="post" enctype="multipart/form-data">
                <input type="hidden" class="hidden" name="max_file_size" value="2000000000">
                 <br>
                <input type="file" size="25" name="download_file" onChange="enter_title();"><br>      
               
                Enter title<img src="images/spacer.gif" width=140 height=1"><br>
                <input type="text" name="title" style="width:230px;"><br>
                Enter description&nbsp;<img src="images/spacer.gif" width=100 height=1"><br>
                <textarea  name="desc" rows="10" cols="30"></textarea><br><br>
                
                
                <input type="submit" value="Upload"><img src="images/spacer.gif" width=30 height=1">
                <input type="hidden" name="block_id" value="<?=$block_id;?>">
            </form>
            <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
        </div>
    <?php } ?>
    <script language="javascript" type="text/javascript">
        function enter_title(){
            filename = document.form1.download_file.value;
            console.log(filename);
            document.form1.title.value = filename.split("\\").pop();
            document.form1.download_file.style.display = "none";
        }
    
    </script>
    <?php

//---------------------------------------------------- Edit download ----------------------------

    function edit_download($id,$block_id){
        $page = $_SESSION['page'];
        $db = connect_pdo();
        $sql_links = "SELECT * FROM blocks where  id = '$block_id' ";
        $stmt = $db->query($sql_links);
	    	$rows = $stmt->fetchAll();
	    	$row_links = $rows[0];
        $links_array = unserialize($row_links['content']);
        $current_title = $links_array['title'][$id];
        $current_desc = $links_array['desc'][$id];
        $current_link = $links_array['link'][$id];
        
        
        ?>
        <div id="editbox" style="top: 50; left: 130; width: 300; height: 380; padding-right: 1em;" align="right">
            <br>
            <form name="form1"  method="post" action="mypage_finish.php?action=finish_edit_download">
               
                Title<img src="images/spacer.gif" width=230 height=1"><br>
                <input type="text" size="34" name="title" value="<?=$current_title;?>"><br>
                Edit description&nbsp;<img src="images/spacer.gif" width=150 height=1"><br>
                <textarea  name="desc" rows="10" cols="35"><?=$current_desc;?></textarea><br>
                
                <input type="hidden" name="id" value="<?=$id;?>">
                <input type="hidden" name="block_id" value="<?=$block_id;?>">
                <br>
                <input type="submit" value="Update">
            </form>
            <div style="position: absolute; bottom: 5; width: 150px; height: 60px; border: 2px solid black;padding: 5px;margin: 5px;text-align: center;">
                <form name="del_form"  method="post" action="mypage_finish.php?action=finish_edit_download">
                    Delete This Entry
                    <select name="question">
                        <option value="No" selected>No</option>
                        <option value="Yes">Yes</option>
                    </select>
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="block_id" value="<?=$block_id;?>">
                    <input type="submit" value="Delete" style="background: red;">
                </form>
            </div>
            <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
        
        </div>
            
       <?php }

//----------------------------------------  Edit General -------------------------------
       
       function edit_general_not_needed($id,$original_image){
            $page = $_SESSION['page'];
            $db = connect();
            $sql_block = "SELECT * FROM blocks where  id = '$id'";
            $result_block = mysql_query ($sql_block, $db);
            $row_block = mysql_fetch_array($result_block, MYSQL_ASSOC);
            $current_content = $row_block[content];
            $background = $row_block[background];
            $border = $row_block[border];
            
            ?>
            <div  id="edit_content">
               
                <div style="background-color: lightblue; border: 3px solid blue; width: 560; padding-top: 5px; ">
                  <form  name="edit_page"  method="post" action="mypage_finish.php?action=finish_edit_general">
                      <textarea  rows="25" cols="65" name="content"><?=$current_content;?></textarea>
                      <input style="position: relative; top: -1.7em; right: 1em;" type="image" align=right src="images/update.gif">
                </div>
                
                <div style="position: relative; top: 5; background-color: lightblue; border: 3px solid blue; width: 560; padding-top: 5px; ">
                  <div align="center" class="large_bold">Page Block Properties</div>
                  <img src="spacer.gif" width="20">
                  Background Color
                  <img onclick="popup_colorpicker()" src="images/colorbox.gif" width="15">
                  <input style="background-color: #fee3ad;" type="text" size="10" name="background" value="<?=$background;?>">
                  <img src="spacer.gif" width="20">
                  Border
                  <select name="border" style="background-color: #fee3ad;">
                  <option value="<?=$border;?>" selected><?=$border;?></option>
                  <option value="none;">No Border</option>
                  <option value="1px solid black;">Thin Black</option>
                  <option value="2px solid black;">Medium Black</option>
                  <option value="4px solid black;">Thick Black</option>
                  </select>
                  
                  <input type="hidden" name="id" value="<?=$id;?>">
                  <img src="spacer.gif"  width="10">
                  <input type="image" align=ABSBOTTOM src="images/update.gif"><img src="spacer.gif"  width="30">
                </form>
                </div>
                <?php photos($id,$original_image); ?>  
                <span class="edit_box" style="top: 0; right: 0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
        </div>  
      <?php }
 
//------------------------------------------------ Edit Top ------------------------------------
    
     function edit_top($file,$cropped){?>
            <div align="center"  id="edit_content" style="height: 300;"><?php
        if($cropped == "yes"){
            $db = connect();
            echo "file = ".$file."<br>";
            $sql_block = "UPDATE blocks SET content = '$file' WHERE  type='top'";
            $result_block = mysql_query ($sql_block, $db); 
            
            header("Location: index.php");
        
        
        }    
        
        
        if (!isset($_FILES) || empty($_FILES)) { 
       
        
        ?><br>
        <form action="" method="post" enctype="multipart/form-data"> 
        <input type="hidden" class="hidden" name="max_file_size" value="9000000" >
        Select your image to upload<br>  
        <input type="file" name="image"><br> <input type="submit" value="upload"><br> 
        </form>
        <div class="large_bold" align="center">Or choose and existing photo from your images folder</div>
        <div style="background-color: lightblue; border: 3px solid blue; width: 560;" > 
                       
            <div style="height: 150; width: 550; overflow: auto;"> 
                <table>
                <?php
                
                //------------------- Select images from images folder ---------
                $counter = 1;
                $dirpath = "images";
                $dh = opendir($dirpath);
                while (false !== ($file = readdir($dh))) {
                $filepath=$dirpath."/".$file;
                if (!is_dir("$dirpath/$file")) {
                          $array = getimagesize($filepath);
                          if ( (($array[0] > 790) AND ($array[0] < 810))  AND  (($array[1] > 145) AND ($array[1] < 170)) ) {
                              $counter ++;
                              if (($counter % 2) == 0){?> <tr><?php }
                              ?>
                              <td>
                              <a href="mypage_finish.php?action=add_existing_top_picture&existing_file=<?=$filepath;?>">
                              <img src="<?=$dirpath."/".$file; ?>" width="200">
                              </a>
                              </td>
                              <?php
                              }
                }
                }
                closedir($dh); 
                ?> 
                </table>
            </div>
        <!------------------------------------------------------------>
        </div>
        <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>">[CLOSE BOX]</a></span>
        </div> 
        <?php 
    } else {  
        $filename = "top_".$_FILES['image']['name']; 
        //echo "filename =". $filename."<br>"; 
        if (move_uploaded_file($_FILES['image']['tmp_name'], $filename)) {
          chmod($filename, 0777); 
          $convert_str = "convert $filename -resize 800x $filename";
           exec($convert_str);
            header("Location: crop.php?file=$filename");  
        } else {  
            echo 'There was an error uploading that file.';  
            print_r($_FILES);  
        }  
    }  
            }
            
            
//------------------------------------------- Edit Page -------------------------------------
          
  function edit_page($page){
            $db = connect_pdo();
            $sql_block = "SELECT * FROM blocks where  type = 'page' AND page = '$page'";
            $stmt = $db->query($sql_block);
	    			$rows = $stmt->fetchAll();
	    			$row_block = $rows[0];
            
            $id = $row_block['id'];
            $page_array = unserialize($row_block['content']);
                             
            $current_background = $page_array['background'][1];
            $current_mainbox_background = $page_array['mainbox_background'][1];
            $current_page_title = $page_array['page_title'];
            $current_password = $page_array['password'];
            
            ?>
             
          
          
            
            <script type="text/javascript">
                  jQuery(document).ready(function(){
                      jQuery("#background").spectrum({
                      showAlpha: true,
                      showInput: true,
                      
                      move: function(color){
                          jQuery("body").css("background-color", color.toRgbString())
                      },
                      change: function(color) {
                          jQuery("#background").val(color.toRgbString());
                          this.form.submit();
                      },
                      clickoutFiresChange: true
                    });
                  });
                </script>
            <div id="editbox" style="left: 80px;padding-top:4px;">  
            <h3 style="margin:0 0 5px 0;">Page #<?=$page;?></h3>
          
                <form name="edit_block"  method="post" action="mypage_finish.php?action=finish_page" style="margin-bottom:3px;">
                    Title for page<br>
                    <input type="text" name="page_title" value="<?=$current_page_title;?>" autocomplete='off'> <input type="submit" value="Enter">
                    <br><br>
                    
                    
										Background Color<br>
                    <input type="text" id="background" name="mb_background" value="<?=$current_background;?>">
                    <button type="button" onClick="document.edit_block.background.value='none'; document.edit_block.submit();">No Background</button>
                    <div id="picker_box" style="height: 270;position: absolute; left: 200; top: 50; border: 1px solid black; display: none;background: white;z-index: +5;">
                      </div>
                    
                    <br><br>
                    Password for page<br>
                    <input type="text" name="password" value="<?=$current_password;?>" size="14" autocomplete='off'>
                    <input type="submit" value="Enter">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="page" value="<?=$page;?>">
                </form>
                <a href="mypage_finish.php?action=remove_password&page=<?=$page;?>">Remove Password</a><br><br>
          
                <form name="add_block" method="post" action="mypage_finish.php">
                  Add a block to page <?=$page;?><br>
                  Block type<br>
                  <select name="block_type">
                      <?=get_block_types($type);?>
                  </select>
                  <input type="hidden" name="action" value="add_block" >
                  <input type="submit" value="Add block">
                </form>
              
                
                
                
                <form name="add_page" method="post"  action="mypage_finish.php?action=add_page" >
                  Add a new page to your website<br>
                  <input type="submit" value="Add a new page">
                </form>
                <div style="width: 150px; height: 60px; border: 2px solid black;padding: 5px;margin: 5px;text-align: center;">
                    <form name="del_page" method="post" action="mypage_finish.php?action=del_page" onClick="return deletechecked();">
                      Delete this page<br>
                      <input type="submit" value="Delete this page" style="background: red; ">
                    </form>
                </div>
                
            <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
        </div>
            
       <?php }
            
    
    
//-------------------------------------------- Edit Mainbox --------------------------------------------------------    
function edit_mainbox($page,$original_image){
            $db = connect();
            $sql_block = "SELECT * FROM blocks where  type = 'mainbox' AND page = '$page'";
            $result_block = mysql_query ($sql_block, $db);
            $row_block = mysql_fetch_array($result_block, MYSQL_ASSOC);
            
            $id = $row_block[id];
            $current_background = $row_block[background];
            
            
            ?>
            <div  id="edit_content">
            <br>
            
            <br>    
                <form name="edit_page"  method="post" action="mypage_finish.php?action=finish_mainbox">
                    
                    Select the MainBox Background Color
                    <img onclick="popup_colorpicker()" src="images/colorbox.gif" width="15"><br>
                    <?php //include("colorpicker.html"); ?>
                    
                    <input type="text" name="background" value="<?=$current_background;?>">
                    <br>
                    <input type="submit" value="Enter">
                    <input type="hidden" name="id" value="<?=$id;?>">
                </form>
                    
              <!-------------------------- Bottom section for photos ------------------------------>
         <div style="background-color: lightblue; border: 3px solid blue; width: 560;" >  
              <form style="margin-top: 0px; margin-bottom: 0px;" action="mypage_finish.php?action=mainbox_image_upload" method="post" enctype="multipart/form-data"> 
                  <input type="hidden" class="hidden" name="max_file_size" value="9000000">
                  <img src="spacer.gif" width="20">Select an image to upload<br> 
                  <input type="hidden" name="id" value="<?=$id;?>" > 
                  <img src="spacer.gif" width="20"><input style="background-color: #fee3ad;" type="file" name="image"><br>
                  
                  <img src="spacer.gif" width="20"><input type="submit" value="upload">  
              </form>
              <div class="large_bold" align="center">Or choose a picture from your images folder</div>
        <div style="height: 100; width: 550; overflow: auto;">   
            <table>
            <?php
            //------------------- Select images from images folder ---------
            $counter = -1;
            $dirpath = "images";
            $dh = opendir($dirpath);
            while (false !== ($file = readdir($dh))) {
            $filepath=$dirpath."/".$file;
            if (!is_dir("$dirpath/$file")) {
                      $array = getimagesize($filepath);
                      if ( (($array[0] > 750) AND ($array[0] < 900))  AND  (($array[1] > 400) AND ($array[1] < 800)) ) {
                          $counter ++;
                          if (($counter % 5) == 0){?> <tr><?php }
                          ?>
                          <td>
                          <!--<a href="mypage_finish.php?action=edit_mainbox&existing_file=<?=$filepath;?>&id=<?=$id;?>">-->
                          <a href="index.php?action=edit_mainbox&original_image=<?=$filepath;?>&id=<?=$id;?>">
                          <img src="<?=$dirpath."/".$file;?>" width="100">
                          </a>
                          </td>
                          <?php
                          }
            }
            }
            closedir($dh); 
            ?> 
            </table>
        <!------------------------------------------------------------>
         </div> 
          <?php if ($original_image !=""){
              ?>
              <iframe src="blank.htm" scrolling="no" frameborder="0" 
                          style="position:absolute;width:300px;height:300px;top:400px;left:100px;border:none;display:block;z-index:1;background-color: #ff3300;" >
              </iframe>
          <div align="center" style="position: absolute; left: 100; top: 400; width: 300; height: 300; background-color: lightblue; border: 3px solid blue; z-index: 2;">
                <br><br>
                <div class="large_bold">Choose from the following options for this image and press ENTER</div>
                <br>
                <img src="<?=$original_image;?>" width="100"><br>
                <br>
                <form  name="edit_page_photo"  method="post" action="mypage_finish.php?action=add_existing_mainbox_picture">
                    <select name="image_action">
                    <option value="use">Add this image to block</option>
                    <option value="delete">Delete Image from folder</option>
                    <input type="hidden" name="existing_file" value="<?=$original_image;?>">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="submit" value="enter">
                </form>
                <span class="edit_box" style="top: 0; right: 0;"><a href="index.php?action=edit_general&id=<?=$id;?>"><img src="images/redx1.jpg"></a></span>
        
          
          
          </div>
          <?php } ?> 
        </div>       
                    
                    
                    <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>">[CLOSE BOX]</a></span>
       
                
                
            </div>
            
       <?php }    
    
 //----------------------------- Photos ---------------------------------------
 
function photos($id,$original_image){?>
         <div align="center" class="large_bold">Add an image</div> 
         <div style="background-color: lightblue; border: 3px solid blue; width: 560;" >  
              <form style="margin-top: 0px; margin-bottom: 0px;" action="mypage_finish.php?action=image_upload" method="post" enctype="multipart/form-data"> 
                  <input type="hidden" class="hidden" name="max_file_size" value="9000000">
                  <img src="spacer.gif" width="20">Select an image to upload<br> 
                  <input type="hidden" name="id" value="<?=$id;?>" > 
                  <img src="spacer.gif" width="20">
                  <input style="background-color: #fee3ad;" type="file" name="image"><br>
                  <img src="spacer.gif" width="20">
                  <select name="dimensions">
                      <option value="160x120">160x120(small)</option>
                      <option value="320x240">320x240(medium)</option>
                      <option value="640x480">640x480(large)</option>
                  </select>
                  <img src="spacer.gif" width="20"><input type="submit" value="upload">  
              </form>
              <div class="large_bold" align="center">Or choose a picture from your images folder</div>
        <div style="height: 100; width: 550; overflow: auto;">   
            <table>
            <?php
            
            $counter = -1;
            $dirpath = "images";
            $dh = opendir($dirpath);
            while (false !== ($file = readdir($dh))) {
            $filepath=$dirpath."/".$file;
            if (!is_dir("$dirpath/$file")) {
                      $array = getimagesize($filepath);
                      if ( (($array[0] > 50) AND ($array[0] < 900))  AND  (($array[1] > 50) AND ($array[1] < 900)) ) {
                          $counter ++;
                          if (($counter % 5) == 0){?> <tr><?php }
                          $array = getimagesize($filepath);
                          $width = $array[0];
                          $height = $array[1];
                          ?>
                          <td>
                              <a onclick="window.open('image_action.php?id=<?=$id;?>&original_image=<?=$filepath;?>','mywindow','menubar=1,resizable=1,width=350,height=320')" 
                                  style="text-decoration: none;" >
                                 
                              <img src="<?=$dirpath."/".$file; ?>" width="100"><br>
                              <div align="center" style="width: 100; background-color: lightgrey; border: 1px solid black;">
                                  <?=$width."x".$height;?>
                              </div>
                              </a>
                          </td>
                          <?php
                          }
            }
            }
            closedir($dh); 
            ?> 
            </table>
            
                  <form  name="photo_form"  method="post" action="mypage_finish.php?action=existing_picture">
                              <input type="hidden" name="image_action">
                              <input type="hidden" name="existing_file" >
                              <input type="hidden" name="id" value="<?=$id;?>">
                              
                  </form>
            
         </div> 
        </div>   
    
    
    
    
    <?php }
//------------------------------------------ Picture edit box (manage pictures) ------------------------------------    
function picture_edit_box($existing_picture){?>
    <div style="border: 2px solid black; width: 200; position: absolute; left: 1em; top: 2em;text-align: center;" >  
        <div align="center">
        <div style="font-size: 1.5em; font-weight: bold;" align="center">Manage Images</div>
             <span style="position: absolute; top: 0; right: 0;">
                <?php $page = $_SESSION['page']; ?>
                <a href="index.php?action=manage_photos_disable&page=<?=$page;?>">
                    <img src="images/redx1.jpg">
                </a>
             </span>
             
            <div style="background-color: white; border: px solid black; width: 200; position: absolute; left: 1em; top: 2em;" >  
                <form style="margin-top: 0px; margin-bottom: 0px;" action="mypage_finish.php?action=image_upload" method="post" enctype="multipart/form-data"> 
                  <input type="hidden" class="hidden" name="max_file_size" value="9000000">
                  <img src="spacer.gif" width="20">Select image to upload<br> 
                  <input type="hidden" name="id" value="<?=$id;?>" > 
                  <img src="spacer.gif" width="20">
                  <input type="file" size="10" name="image"><br>
                  <img src="spacer.gif" width="5">
                  <select name="dimensions">
                      <option value="160x120">160x120(small)</option>
                      <option value="320x240" selected>320x240(medium)</option>
                      <option value="640x480">640x480(large)</option>
                  </select>
                  <input type="submit" value="upload">&nbsp; 
                  <br>&nbsp; 
               </form>
              </div>
        
         
              <!------------- Existing Pictures listed ------------>
              <?php if ($image_type == ""){$image_type = ".";}?>
              
                
                <div style=" height: 300; width: 450; overflow: auto; background-color: white; border: 2px solid black; position: absolute; left: 220; top: 2em;">   
                    <table>
                    <?php
                    
                    $counter = -1;
                    $dirpath = "pageimages";
                    $dh = opendir($dirpath);
                    while (false !== ($file = readdir($dh))) {
                    $filepath=$dirpath."/".$file;
                    if (!is_dir("$dirpath/$file")) {
                              $array = getimagesize($filepath);
                              if ( (stristr($filepath,"thumb_"))  AND (stristr($filepath,$image_type)) ) {
                                  $actual_filepath = $dirpath."/".substr($file,6);
                                  $counter ++;
                                  if (($counter % 4) == 0){?> <tr><?php }
                                  $array = getimagesize($actual_filepath);
                                  $width = $array[0];
                                  $height = $array[1];
                                  $existing_file = $dirpath."/".$file;
                                  ?>
                                  <td>
                                     <?="<a href='index.php?existing_picture=$existing_file&page=$page'>";?> 
                                      <img src="<?=$existing_file; ?>" width="100"><br>
                                      <div align="center" style="width: 100; background-color: lightgrey; border: 1px solid black;">
                                          <?=$width."x".$height; ?>
                                      </div>
                                      </a>
                                  </td>
                                  <?php
                                  }
                    }
                    }
                    closedir($dh); 
                    ?> 
                    </table>
                 </div>
            
                 <br>
                 <?php if ($existing_picture){?>
                     <div style="background-color: white; border: 2px solid black; width: 230; position: absolute; top: 2em;;right: 1em;">
                        <br>
                        <img src="<?=$existing_picture;?>" width="180">
                        <div style="position: relative; top: -5; width: 180; background-color: gray;">
                          <?php
                          $find_thumb = strpos($existing_picture,"thumb_")+6;
                          $actual_filename = substr($existing_picture,$find_thumb);
                          $array = getimagesize("pageimages/".$actual_filename);
                          $width = $array[0];
                          $height = $array[1];
                          echo $actual_filename."<br>";
                          echo $width."x".$height."<br>";
                          ?>  
                        </div>
                    
                        <form  style="margin-top: 0; " name="existing_photo_form"  method="post" action="mypage_finish.php">
                            
                            Resize <img src="spacer.gif" width="10">Delete <img src="spacer.gif" width="10"><br>
                            <select name="resize">
                                <option value="160x120">160x120</option>
                                <option value="320x240">320x240</option>
                                <option value="640x480">640x480</option>
                            </select>
                            <img src="spacer.gif" width="10">
                            <select name="delete">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                            <img src="spacer.gif" width="10">
                            <input type="submit" value="Enter">
                            <input type="hidden" name="existing_picture" value="<?=$existing_picture;?>">
                            <input type="hidden" name="action" value="finish_manage_picture">
                        </form>
                    </div>
                <?php } ?>
    </div>
    </div>          
                 
                 
                 
                 
                 
                 
                 
                 <?php

} 
//--------------------------------------------- Upload_video ---------------------------------------
function upload_video($playlist){?>
	<div class="upload_video_box">
    <?php $page = $_SESSION['page'];?>
    <span style="position: absolute; right: 0;top:0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
		<br><br>
		<form action="mypage_finish.php?action=upload_video_finish" method="post" enctype="multipart/form-data">
		    <input type="file" name="videoname" id="videoname" class="inputfile" onChange="this.form.submit();">
		    <label for="videoname"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
				<input type="hidden" name="playlist" value="<?=$playlist;?>">
		    
		</form>
	</div>
	<?php
}
//--------------------------------------------- Upload_video ---------------------------------------
function upload_video_old(){
    ?>
  <div style="position: absolute; left: 250; top: 220; border: 1px solid black;">
    <?php $page = $_SESSION['page'];?>
    <span style="position: absolute; right: 0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
    <br>
    <div> 
			   	<OBJECT id="FlashFilesUpload" codeBase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
      		width="400" height="350" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" VIEWASTEXT>
      		<!-- Replace symbols " with the " at all parameters values and
      		symbols "&" with the "%26" at URL values or & at other values!
      		The same parameters values should be set for EMBED object below. -->
      	<PARAM NAME="FlashVars" VALUE="uploadUrl=mypage_finish.php?action=upload_video_finish
      &redirectUploadUrl=mypage_finish.php?action=upload_video_finish%26videoname=<?=$videoname;?>
      &clearListButtonX=295
      &filesListWidth=395
      &progressBarWidth=278">
      	<PARAM NAME="BGColor" VALUE="#F8F6E6">
      	<PARAM NAME="Movie" VALUE="ElementITMultiPowUpload1.7.swf">
      	<PARAM NAME="Src" VALUE="ElementITMultiPowUpload1.7.swf">
      	<PARAM NAME="WMode" VALUE="Window">
      	<PARAM NAME="Play" VALUE="-1">
      	<PARAM NAME="Loop" VALUE="-1">
      	<PARAM NAME="Quality" VALUE="High">
      	<PARAM NAME="SAlign" VALUE="">
      	<PARAM NAME="Menu" VALUE="-1">
      	<PARAM NAME="Base" VALUE="">
      	<PARAM NAME="AllowScriptAccess" VALUE="always">
      	<PARAM NAME="Scale" VALUE="ShowAll">
      	<PARAM NAME="DeviceFont" VALUE="0">
      	<PARAM NAME="EmbedMovie" VALUE="0">
      	<PARAM NAME="SWRemote" VALUE="">
      	<PARAM NAME="MovieData" VALUE="">
      	<PARAM NAME="SeamlessTabbing" VALUE="1">
      	<PARAM NAME="Profile" VALUE="0">
      	<PARAM NAME="ProfileAddress" VALUE="">
      	<PARAM NAME="ProfilePort" VALUE="0">
      	<!-- Embed for Netscape,Mozilla/FireFox browsers support. Flashvars parameters are the same.-->
      		<!-- Replace symbols " with the " at all parameters values and
      		symbols "&" with the "%26" at URL values or & at other values! -->
      	<embed bgcolor="#F8F6E6" id="EmbedFlashFilesUpload" src="ElementITMultiPowUpload1.7.swf" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"	type="application/x-shockwave-flash" width="400" height="350"
      	flashvars="uploadUrl=mypage_finish.php?action=upload_video_finish
      &redirectUploadUrl=mypage_finish.php?action=upload_video_finish%26videoname=<?=$videoname;?>
      &clearListButtonX=295
      &filesListWidth=395
      &progressBarWidth=278">
      	</embed>
        </OBJECT>
  	</div>
  </div>
	<?php
	}
//---------------------------- Convert video ----------------------------------------------------------------
function convert_video() {
        $db=connect_pdo();
    		$sql = "SELECT * FROM videos   where status = 'uploaded'";
    		$stmt = $db->query($sql);
	    	$rows = $stmt->fetchAll();
	    	$row = $rows[0];
    		$videoname = $row['filename'];
        ?>
      	<div  class="upload_video_box">
      	   Succesfully uploaded file<br><?=$videoname;?><br>to the server<br><br>
      	   
            <form action="mypage_finish.php?action=video_convert" " method="POST">
            
                Enter a title for your video <input type="text" style="width: 20em;" name="title">
                <input type="hidden" name="videoname" value="<?=$videoname;?>">
                <br><br>
                <input type="submit" value="Click to enter Video">
            </form>
        </div>
        <?php
}

//---------------------------- ftp video ----------------------------------------------------------------
function ftp_video($source_file, $destination_file, $title, $videoname) {
        $db=connect();
    		$sql = "SELECT * FROM videos   where status = 'converted'";
    		$result = mysql_query ($sql, $db);
    		$row = mysql_fetch_array($result, MYSQL_ASSOC);
    		$videoname = $row['videoname'];
        ?>
      	<div  style="position: absolute; left: 250; top: 220; width: 450; height: 80; padding: 1em; font-size: 1.2em; background: gray; border: 1px solid black;">
      	   Video is converted<br>The new filename is <span style="background: yellow;"><?=$source_file;?></span>
           <br>
           Title is <span style="background: yellow;"><?=$title;?></span>
           <br><br>
      	   
            <form action="mypage_finish.php?action=video_upload" " method="POST">
            
               
                <input type="hidden" name="videoname" value="<?=$videoname;?>">
                <input type="hidden" name="source_file" value="<?=$source_file;?>">
                <input type="hidden" name="destination_file" value="<?$destination_file;?>">
                <input type="hidden" name="title" value="<?=$title;?>">
                <br>
                <input type="submit" value="Click to Upload Video to www.cdfmarketing.com/videos">
            </form>
        </div>
        <?php
}


function find_extension ($filename){ 
$filename = strtolower($filename) ; 
$exts = split("[/\\.]", $filename) ; 
$n = count($exts)-1; 
$exts = $exts[$n]; 
return $exts; 
}					
function remove_extension($strName){ 
$ext = strrchr($strName, '.'); 
if($ext != false){$strName = substr($strName, 0, -strlen($ext));} 
return $strName; 
}					
    
//----------------------------------------- Edit video database ----------------------------------
function edit_video_data($id,$playlist){echo "id=".$id;
    $page=$_SESSION['page'];
    if (!$id){
    		$db=connect_pdo();
        $sql_video = "SELECT * from videos where playlist = '$playlist' order by date DESC";
        $stmt = $db->query($sql_video);
  			$rows_video = $stmt->fetchAll();
  			$row = $rows_video[0];
    		//------------ list all videos -------------------
        ?>
        <div  class="video_edit_box" style="width:730px;">
        		<span style="position: absolute; right: 0;top:0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
        		<form style="margin:0;" method="post" action="mypage_finish.php?action=playlist_title">
          		<input type="hidden" name="playlist" value="<?=$playlist;?>">
          		<input type="hidden" name="page" value="<?=$page;?>">
          		Playlist title = <input style="type="text" name="playlist_title" value="<?=$row['playlist_title'];?>">
          		<input type="submit" value="Update">
          	</form>
        		
          	<h3 style="margin:5px 0 0px 5px;">Edit one of the videos below - Playlist #<?=$playlist;?></h3>
          	
            <table class="listing" style="width: 700;">
                <th>EDIT</th>
                <th>ID</th>
                <th width="40">Filename</th>
                <th width="40">Title</th>
                <th width="40">Date</th>
                <th width="40">Status</th>
                <tr>
            <?php
            
            
            foreach($rows_video as $row_video){?>
                <td><a href="index.php?page=<?=$page;?>&action=edit_video_data&video_id=<?=$row_video['id'];?>">[EDIT]</a></td>
                <td><?=$row_video['id'];?></td>
                <td><?=$row_video['filename'];?></td>
                <td><?=$row_video['title']; ?></td>
                <td><?=$row_video['date']; ?></td>
                <td><?=$row_video['status']; ?></td>
                <tr>  
            <?php }
        ?>
            </table>
        </div>
        <?php
    }
    else {
        $db=connect_pdo();
        $sql_video = "SELECT * from videos where id = '$id'";
        $stmt = $db->query($sql_video);
	    	$rows_video = $stmt->fetchAll();
        $row_video = $rows_video[0];
        
       
        //--------- Show this video date ---------
        ?>
        <div class="video_edit_box">
        		<span style="position: absolute; right: 0;top:0;"><a href="index.php?page=<?=$page;?>&action=edit_video_data"><img src="images/redx1.jpg"></a></span>
         		Edit Video Entry ID# <?=$id;?><br><br>   
            <form action="mypage_finish.php?action=video_data_edit" method="POST">
		            <label for="filename">Filename</label><br>
		            <?=$row_video['filename'];?><br>
		            <label for="title">Title</label><br><input style="width:330px;" type="text" name="title" value="<?=$row_video['title']; ?>"><br>
		            <label for="date">Date</label><br><input style="width:330px;" type="text" name="date" value="<?=$row_video['date'];?>"><br>
		            <input type="hidden" name="id" value="<?=$row_video['id']; ?>">
		            <label for="new_playlist">Playlist</label><br>
		            <input type="text" style="width:50px;" name="new_playlist" value="<?=$row_video['playlist'];?>">
		            	
		            <br><br>
		            <input type="submit" value="Update">
            
            </form>
            <a class="red_button" style="position:absolute;right:20px;bottom:20px;" href="mypage_finish.php?action=video_delete&id=<?=$id;?>" onclick="return deletechecked();">Delete</a>
        </div>


      <?php
      }

}  
    
//--------------------------------------------- Login_page --------------------------------------    
    
    function login_page($page,$status,$password){
      ?><div id="editbox" style="height: 200;"><?php
        if($status == "entered"){
            
            if ($id == "admin" && $password == "password") { 
                $_SESSION['loggedin'] = "true"; 
                $_SESSION['Username'] = $id; 
                
                header("Location: index.php");
            } 
            else {?> <div align="center">Incorrect username and password<br>Please try again</div> <?php }
            
        }
        
        ?>
        
            <form name="login_form"  method="post" action="index.php?action=login&status=entered">
                Enter Id<br>
                <input type="text" size="30" name="id"><br>
                Enter Password<br>
                <input type="password" size="30" name="password"><br>
                <input type="submit" value="Enter">
            </form>
        </div>
    
     <?php }    
    
    
    
    
//--------------------------------------------- Login --------------------------------------    
    
    function login($status,$password){
        $page=$_SESSION['page'];
      ?><div id="editbox" style="height: 150px;">
          <a href="index.php?page=<?=$page;?>"style="position: absolute; top: 0; right: 0;"><img src="images/redx1.jpg"></a><?php
          
        if($status == "entered"){
            
            $db = connect_pdo();
            $sql_page = "SELECT * FROM blocks where  id = 1";
            $stmt = $db->query($sql_page);
	    			$rows_page = $stmt->fetchAll();
	    			$row_page = $rows_page[0];
            $page_array = unserialize($row_page['content']);
            $stored_password = $page_array['password'];
            
            //$stored_password = "password";
            if ($password == $stored_password) { 
                $_SESSION['loggedin'] = "true"; 
                $_SESSION['Username'] = $id; 
                $page = $_SESSION['page'];
                
                header("Location: index.php?page=$page");
            } 
            else {?> <div align="center">Incorrect password<br>Please try again</div> <?php }
            
        }
        
        ?>
        
            <form name="login_form"  method="post" action="index.php?page=<?=$page;?>&action=login&status=entered">
                
                Enter Editing Password<br>
                <input type="password" size="20" name="password"><br>
                <input type="submit" value="Enter">
            </form>
        </div>
    
     <?php }
 
 
 
 //----------------------------------- LOGOUT -----------------------------------------    
     function logout() {
        $page = $_SESSION['page'];
        session_start(); 
       $_SESSION['loggedin'] = '';
       //$_SESSION = array(); 
        
        //session_destroy();
        header("Location: index.php?page=$page"); 
}
//-------------------------------------------- site config -----------------------------------------------
function site_config(){
      $page=$_SESSION['page'];
      ?><div id="editbox" >
          <a href="index.php?page=<?=$page;?>"style="position: absolute; top: 0; right: 0;"><img src="images/redx1.jpg"></a>
          <?php
        
          $db = connect_pdo("checkout");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql_page = "SELECT * FROM blocks where  id = 1";
			    $stmt = $db->query($sql_page);
			    $rows_page = $stmt->fetchAll();
			    $row_page = $rows_page[0];
          $page_array = unserialize($row_page['content']);
          $stored_password = $page_array['password'];
          ?>
        
        
       
        
            <form name="form1"  method="post" action="mypage_finish.php?action=finish_site_config">
                
                Master Editing Password<br>
                <input type="text" size="20" name="password" value="<?=$stored_password;?>" autocomplete='off'><br>
                <input type="submit" value="Enter">
            </form>
            <br><br>
            <a href="new_config.php">Database setup</a>
            <br>
        </div>

<?php

}
//------------------------------------------- date_display --------------------------------------------------
function date_display($date_sql){
    if (($date_sql == "") OR ($date_sql == "0000-00-00")){$date_sql = "yyyy-mm-dd";}
    $date_display = substr($date_sql,5,2)."/".substr($date_sql,8,2)."/".substr($date_sql,0,4);
    return($date_display);}
//------------------------------------------ Get Browser ---------------------------------------


function return_browser($useragent)
{
//check for most popular browsers first
//unfortunately that's ie. We also ignore opera and netscape 8 
//because they sometimes send msie agent
if(strpos($useragent,"MSIE") !== false && strpos($useragent,"Opera") === false && strpos($useragent,"Netscape") === false)
{
//deal with IE
$found = preg_match("/MSIE ([0-9]{1}\.[0-9]{1,2})/",$useragent,$mathes);
if($found)
{
return "IE";
}
}
elseif(strpos($useragent,"Gecko"))
{
//deal with Gecko based

//if firefox
$found = preg_match("/Firefox\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
if($found)
{
return "Firefox";
}

//if Netscape (based on gecko)
$found = preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
if($found)
{
return "Netscape";
}

//if Safari (based on gecko)
$found = preg_match("/Safari\/([0-9]{2,3}(\.[0-9])?)/",$useragent,$mathes);
if($found)
{
return "Safari";
}

//if Galeon (based on gecko)
$found = preg_match("/Galeon\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
if($found)
{
return "Galeon";
}

//if Konqueror (based on gecko)
$found = preg_match("/Konqueror\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
if($found)
{
return "Konqueror";
}

//no specific Gecko found
//return generic Gecko
return "Gecko based";
}
elseif(strpos($useragent,"Opera/9") !== false)
{
//deal with Opera
$found = preg_match("/Opera[\/ ]([0-9]{1}\.[0-9]{1}([0-9])?)/",$useragent,$mathes);
if($found)
{
//echo "<div style='color: white;'>useragent = " .$useragent ."</div>";
return "Opera9";
}
}
elseif(strpos($useragent,"Opera") !== false)
{
//deal with Opera
$found = preg_match("/Opera[\/ ]([0-9]{1}\.[0-9]{1}([0-9])?)/",$useragent,$mathes);
if($found)
{
return "Opera";
}
}
elseif (strpos($useragent,"Lynx") !== false)
{
//deal with Lynx
$found = preg_match("/Lynx\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
if($found)
{
return "Lynx";
}

}
elseif (strpos($useragent,"Netscape") !== false)
{
//NN8 with IE string
$found = preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
if($found)
{
return "Netscape";
}
}
else 
{
//unrecognized, this should be less than 1% of browsers (not counting bots like google etc)!
return false;
}
}
//------------------------------------------------ list_contents of an array for testing ---------------------------
function LIST_CONTENTS($arrayname,$tab="    ",$indent=0){
    // recursively displays contents of the array and sub-arrays: 
    // Free for unrestricted use, except sale - do not resell. 
    // use: echo LIST_CONTENTS(array $arrayname, string $tab, int $indent); 
    // $tab = string to use as a tab, $indent = number of tabs to indent result 
    while(list($key, $value) = each($arrayname)) 
    { 
           for($i=0; $i<$indent; $i++) $currenttab .= $tab;
           //echo "key=".$key."<br>"; 
           //echo "strpos(key,bandscenelive)=".strpos($key,'bandscenelive')."<br>";
        if (is_array($value)) 
        { 
            $retval .= "$currenttab$key : Array: <BR>$currenttab{<BR>"; 
            $retval .= LIST_CONTENTS($value,$tab,$indent+1)."$currenttab}<BR>"; 
        } 
        
           else {
               $retval .= "$currenttab$key => $value<BR>"; 
               $currenttab = NULL; 
               }
    } 
    return $retval; 
}
//----------------------------------------------- Hex Lighter -----------------------------------------------------
function hexLighter($hex,$factor)
        {
        $new_hex = '';
        
        $base['R'] = hexdec($hex{0}.$hex{1});
        $base['G'] = hexdec($hex{2}.$hex{3});
        $base['B'] = hexdec($hex{4}.$hex{5});
        
        foreach ($base as $k => $v)
                {
                $amount = 255 - $v;
                $amount = $amount / 100;
                $amount = round($amount * $factor);
                $new_decimal = $v + $amount;
        
                $new_hex_component = dechex($new_decimal);
                if(strlen($new_hex_component) < 2)
                        { $new_hex_component = "0".$new_hex_component; }
                $new_hex .= $new_hex_component;
                }
                
        return $new_hex;        
        }

//------------------------------------------------ create tables ------------------------------------------------------
function create_tables(){
    $db = connect_pdo();$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
        
    $sql = "CREATE TABLE `blocks` (
  `id` int(11) NOT NULL,
  `page` int(11) NOT NULL DEFAULT '0',
  `block` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `xpos` varchar(5) DEFAULT NULL,
  `ypos` varchar(5) DEFAULT NULL,
  `width` varchar(5) DEFAULT NULL,
  `height` varchar(5) DEFAULT NULL,
  `title` text NOT NULL,
  `style_type` varchar(10) DEFAULT NULL,
  `background` varchar(30) DEFAULT NULL,
  `border` varchar(20) DEFAULT NULL,
  `parameter1` text NOT NULL,
  `content` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1";
 $stmt = $db->exec($sql); 
 
 $sql="INSERT INTO `blocks` (`id`, `page`, `block`, `type`, `xpos`, `ypos`, `width`, `height`, `title`, `style_type`, `background`, `border`, `content`) VALUES
(1, 0, 0, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'a:1:{s:8:\"password\";s:8:\"password\";}'),
(183, 1, 1, 'mainbox', '133', '143', '890', '1006', '', NULL, '#bababa', '1px solid black', NULL),
(184, 1, 2, 'top', '1', '0', '820', '150', '', NULL, '#d25252', '1px solid black', 'pageimages/top_mountain-lake-scenery.jpg'),
(185, 1, 3, 'link', '2', '145', '130', '1006', '', 'leftside1', '#cccccc', '1px solid black;', 'a:3:{s:4:\"link\";a:1:{i:0;s:7:\"?page=1\";}s:5:\"words\";a:1:{i:0;s:4:\"Home\";}s:7:\"new_tab\";a:1:{i:0;N;}}'),
(186, 1, 4, 'general', '142', '152', '494', '239', '', '', '#cccccc', '1px solid black;', 'This is a new general block.'),
(187, 1, 5, 'general', '448', '16', '252', '80', '', '', 'none', '0;', '<font size=\"7\"><strong>\r\nPage Title\r\n</strong></font>')";

$stmt = $db->exec($sql); 



$sql = "CREATE TABLE IF NOT EXISTS `block_types` (
  `id` int(11) NOT NULL auto_increment,
  `type` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9" ;
$stmt = $db->exec($sql); 

$sql = "
INSERT INTO `block_types` (`id`, `type`) VALUES
(1, 'general'),
(2, 'picture'),
(3, 'blog'),
(4, 'link'),
(5, 'download'),
(6, 'youtube'),
(7, 'top'),
(8, 'rss'),
(9, 'video')";
$stmt = $db->exec($sql); 

$sql = "CREATE TABLE IF NOT EXISTS `smugmug` (
  `id` int(11) NOT NULL auto_increment,
  `gallery_id` text NOT NULL,
  `gallery_key` text NOT NULL,
  `folder` text NOT NULL,
  `title` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12499" ;

$stmt = $db->exec($sql); 
header("location:index.php?page=1");
exit;
}
//---------------- get highest year from blog -------------
function get_highest_year($table){
	$db = connect_pdo();$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT MAX(date) from $table ";
  $stmt = $db->query($sql);
  $rows = $stmt->fetchAll();
	$row = $rows[0];
	$h_date = $row['date'];
	$h_date_array = explode('-',$h_date);
	$h_year = $h_date_array[0];
	return $h_year;
}
//----------------- getFeed --------------------------
function getFeed($url,$qty,$force){
				
				$x = 1;
        //$rss = simplexml_load_file($url);
        $file_url = str_replace('/','_',$url);
        $file_url = str_replace(':','',$file_url);
        $file_url = str_replace('.','',$file_url);
        $cache_file = "cache/".$file_url.".xml";
        //echo "cache_file=".$cache_file."<br>";
       
  			$cache_time = 60*60; // 1 hour
				if($force == 'y'){$cache_time = 0;}
				$timedif = @(time() - filemtime($cache_file));
				if (file_exists($cache_file) && $timedif < $cache_time) {
				    $string = file_get_contents($cache_file);
				} else {
						?>
						<script type="text/javascript">
							$(document).ready(function() {
									$(<?=$file_url;?>).show();
									$(<?=$file_url;?>).delay(2000).hide(400);
							});
						</script>
						<?php
				    $string = file_get_contents($url);
				    if ($f = @fopen($cache_file, 'w')) {
				        fwrite ($f, $string, strlen($string));
				        fclose($f);
				    }
				}
				
				?>
						
						<?php
				
				$rss = simplexml_load_string($string);
  			
  			
        //$rss = simplexml_load_file($data);
        ?>
        <div id="<?=$file_url;?>" style="display:none;position:absolute;top:3px;right:30px;background:#11D92F;padding:2px;">Updated</div>
        <div class="rss_title"><?=strip_tags($rss->channel->title);?></div>
        <div style="padding:5px;">
        <?php
        
        foreach($rss->channel->item as $item) {
        		
						
            ?>
            
            <div style="border-bottom: 1px solid #E0E0E0;margin-bottom:5px;padding-bottom:5px;">
		            <div class="rss_item"><a href="<?=strip_tags($item->link);?>"><?=strip_tags($item->title);?></a></div>
		            <span style="font-size:12px;"><?=gen_string(strip_tags($item->description),250);?></span>
		            
    				
		        </div>
            <?php
            $x ++;
            if($x > $qty){break;} 
        }
      	?>
	      </div>
			  
	      <?php
       /*
       $rss = new lastRSS;

			// Set cache dir and cache time limit (1200 seconds)
			// (don't forget to chmod cahce dir to 777 to allow writing)
			$rss->cache_dir = '/cache';
			$rss->cache_time = 1200;
      $rs = $rss->get($url);
       
       
				?>
				<a href="<?=$rs['link'];?>"><?=$rs['title'];?></a><br>
				
				<?=$rs['description'];?><br>
				// Show last published articles (title, link, description)
				<?php
				foreach($rs['items'] as $item) {
					?>
					<a href="<?=$item['link'];?>"><?=$item['title'];?></a>
					<br>
					<?=$item['description'];?>
					<br>
					<?php
					}
				*/
				
        
    }
//------ gen_string (truncate string to last word and add dots) ---------
  function gen_string($string,$max=20)
{
    $tok=strtok($string,' ');
    $string='';
    while($tok!==false && mb_strlen($string)<$max)
    {
        if (mb_strlen($string)+mb_strlen($tok)<=$max)
            $string.=$tok.' ';
        else
            break;
        $tok=strtok(' ');
    }
    return trim($string).'...';
}
  

?>


