<?  ob_start(); session_start();  
         ?>
         <link href="default.css" rel="stylesheet" type="text/css" />
         <script language="javascript" type="text/javascript">
				  function deletechecked(){
				        var answer = confirm("Really Delete")
				        if (answer){
				            document.messages.submit();
				        }
				        return false;  
				    }
				 </script>
         <?php
         $cropped = '';
         foreach ($_REQUEST as $param_name => $param_val) {
					if($param_name == "PHPSESSID"){continue;}
			    $$param_name = $param_val;
					}    
        include("functions.php");
        include_once("functions_connect.php"); 

        if (isset ($_POST['id'])){$id = $_POST['id'];}
        else if (isset ($_GET['id'])){$id = $_GET['id'];}

        $page = $_SESSION['page'];
        $db = connect();
        $sql_block = "SELECT * FROM blocks where  id = '$id'";
        $result_block = mysql_query ($sql_block, $db);
        $row_block = mysql_fetch_array($result_block, MYSQL_ASSOC);
        
        $background = $row_block['background'];
        $border = $row_block['border'];
        $current_image = $row_block['content'];


        ?>
            <link rel="stylesheet" type="text/css" href="spectrum.css">
            <script src="js/jquery-2.1.1.min.js" type="text/javascript" charset="utf-8"></script>
            <script type="text/javascript" src="spectrum.js"></script>
            <script language="javascript" type="text/javascript">
                function back_to_form1(){
                    window.opener.document.finish_edit_top_properties.picture_inc.value= document.edit_block.picture_inc.value;
                    window.opener.document.finish_edit_top_properties.background.value= document.edit_block.background.value;
                    window.opener.document.finish_edit_top_properties.border.value= document.edit_block.border.value;
                    window.opener.document.finish_edit_top_properties.id.value= document.edit_block.id.value;
                    window.opener.document.finish_edit_top_properties.submit();
                    self.close(); 
                    }
                jQuery(document).ready(function(){
                      jQuery("#background").spectrum({
                          showInput: true,
                          showAlpha: true,
                      change: function(color) {
                          jQuery("#background").val(color.toHexString());
                          
                      },
                      clickoutFiresChange: true
                    });
                  });
            </script>
            
        <div align="center"  id="edit_content" style="width:auto;border:none;background-color:inherit;"><?
            if($cropped == "yes"){
                $page = $_SESSION['page'];
                echo "page=".$page."<br>";
                $db = connect();
                echo "file = ".$file."<br>";
                $sql_block = "UPDATE blocks SET content = '$file' WHERE  type='top' and page = '$page'";
                $result_block = mysql_query ($sql_block, $db); 
                echo "<script type='text/javascript'> opener.location.href='index.php'; self.close();</script>";
                exit;
            }    
            
            
            if (!isset($_FILES) || empty($_FILES)) { 
           
            
            ?><br>
            <form action="" method="post" enctype="multipart/form-data" name="upload_top_pic"> 
                <input type="hidden" class="hidden" name="max_file_size" value="10000000" >
                Select your image to upload<br>  
                <input type="file" name="image" onChange="document.upload_top_pic.submit();"><br> 
                <!--  <input type="submit" value="upload" >  -->
                <br> 
            </form>
            <!------------------- Select images from images folder --------->
            <div class="large_bold" align="center">Or choose and existing photo from your images folder</div>
               
                               
                    <div style="background-color: #ddd; border: 2px solid black; height: 500; width: 1024; overflow: auto;"> 
                        <table>
                            <?
                            $files = glob('pageimages/*.*');
                            usort($files, create_function('$a,$b', 'return filemtime($a)<filemtime($b);'));
                            $counter = 2;
                            foreach($files as $filepath){
                            
                              
                            
                          
                                      
                                      if ( $x = strpos($filepath, 'thumb_top') ) {
                                          $thumbpath = $filepath;
                                          $filepath = "pageimages/top_".substr($filepath,$x+10);
                                          $counter ++;
                                          if (($counter % 3) == 0){?> <tr><?}
                                          //echo "filepath=".$filepath." current_image=".$current_image."<br>";
                                          ?>
                                          <td style="position:relative;<? if($filepath == $current_image){?>border:5px dashed red;<?}?>" >
                                                
                                          
                                              <img src="<? echo $thumbpath; ?>" width="330">
                                              <a style="display:block;font-size:12px;color:red;background:#eee;position:absolute;left:5px;bottom:0px;" onclick="javascript:opener.location.href='mypage_finish.php?action=add_existing_top_picture&existing_file=<?=$filepath;?>&id=<?=$id;?>'; self.close();" href=''>
                                                [USE AS TOP PIC]
                                              </a>
                                              <a style="display:block;font-size:12px;color:red;background:#eee;position:absolute;right:5px;bottom:0px;" href="" onclick="javascript:opener.location.href='mypage_finish.php?action=delete_top_pic&existing_file=<?=$filepath;?>&id=<?=$id;?>'; self.close();">
                                                  [DELETE]
                                              </a>
                                          
                                          </td>
                                          <?
                                          }
                           
                            }
                            
                            ?> 
                        </table>
                    
                
                </div>
                <br>
                <div style="width: 550; height: 100; background-color: #ddd; border: 2px solid black;position:relative;">
                     <form  name="edit_block"  method="post" action="" onsubmit="back_to_form1();"> 
                        Use a Photo in top block?<img src="spacer.gif" style="width: 2em; height: 1px;">
                        Background Color<img src="spacer.gif" style="width: 5em; height: 1px;">
                        Border<img src="spacer.gif" style="width: 3em; height: 1px;">
                        <br>
                        <select name="picture_inc">
                            <option value="yes">YES</option>
                            <option value="no">NO</option>
                        </select>
                        <img src="spacer.gif" style="width: 6em; height: 1px;">
                        
                        <input type="text" size="10" name="background" id="background" value="<? echo $background; ?>">
                        <button type="button" onClick="document.edit_block.background.value='none';">No Background</button>
                        
                        <img src="spacer.gif" style="width: 2em; height: 1px;">
                  
                       
                        <select name="border">
                        <option value="<? echo $border; ?>" selected><? echo $border; ?></option>
                        <option value="<? echo $border; ?>" selected><? echo $border; ?></option>
                        <option value="none;">No Border</option>
                        <option value="1px solid black;">Thin Black</option>
                        <option value="2px solid black;">Medium Black</option>
                        <option value="4px solid black;">Thick Black</option>
                        <option value="1px solid white;">Thin white</option>
                        <option value="2px solid white;">Medium white</option>
                        <option value="4px solid white;">Thick white</option>
                        </select>
                        <br>
                        
                        <input type="hidden" name="id" value="<? echo $id; ?>">
                        <input style="position: relative; top: 10; left: 14;" type="image" align=ABSBOTTOM src="images/update.gif" >
                  
                    </form>
                    <div style="position:absolute;left:10px;bottom:10px;">
                    	<a title="Delete Block <?=$id;?>" class="red_button" href="mypage_finish.php?action=delete_block&id=<?=$id;?>&question=yes" onClick="return deletechecked();">Delete Block</a>
                		</div>
                </div>
        </div> 
        <?} 
        
        else {
            //----------- Picture is uploaded, go crop it and come back -------  
            $filename = "pageimages/top_".$_FILES['image']['name']; 
            echo "filename=".$filename."<br>";
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filename)) {
              chmod($filename, 0777); 
              
              ini_set("memory_limit","10000M");
              
              
              
              $new_width = 1024;
              $new_height = 768;
              
              list($width, $height) = getimagesize($filename);
              
              $ratio_orig = $width/$height;
              if ($new_width/$new_height > $ratio_orig) {
                 $new_width = $new_height*$ratio_orig;
              } else {
                 $new_height = $new_width/$ratio_orig;
              }
                 
              $image_p = imagecreatetruecolor($new_width, $new_height);
              $image = imagecreatefromjpeg($filename);
              $response = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
              
              imagejpeg($image_p, $filename,100);
              chmod($filename, 0644);
              imagedestroy($image_p);
              imagedestroy($image);
              
            
              //$convert_str = "convert $filename -resize 800x $filename";
               //exec($convert_str);
                header("Location: crop_new.php?file=$filename");} 
            else {echo 'There was an error uploading that file.'; print_r($_FILES);}  
        }  
            
