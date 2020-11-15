          <?
          include_once ("functions.php");
          //$existing_picture = $_REQUEST['existing_picture'];
          $db = connect();
          ?>
                
                
                
                
              
        
        
             
         <div class="manage_photos_box">  
            <div align="center" style="font-weight: bold; font-size: 1.6em;">Manage Photos</div>  
            <span style="position: absolute; top: 0; right: 0;">
                <? $page = $_SESSION['page']; ?>
                <a href="index.php?action=manage_photos_disable&page=<? echo $page; ?>">
                    <img src="images/redx1.jpg">
                </a>
             </span> 
            <div class="picture_upload_box" >  
                <form style="margin-top: 0px; margin-bottom: 0px;" action="mypage_finish.php?action=finish_whizzypic_mine_upload" method="post" enctype="multipart/form-data"> 
                  <input type="hidden" class="hidden" name="max_file_size" value="15000000">
                  
                  <input type="hidden" name="id" value="<? echo $id; ?>" > 
                  
                  <input type="file" size="10" name="image" id="image" class="inputfile" onChange="this.form.submit();">
                  <label for="image">
                  	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                  		<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                  	</svg>
                  	<span style="font-size:12px;">Select an image to upload&hellip;</span>
                  </label>
				
                  <br>
                  <img src="spacer.gif" width="5">
                  <input type="hidden" name="dimensions" value="640x480">
                  <!--
                  <select name="dimensions">
                      <option value="160x120">160x120(small)</option>
                      <option value="320x240">320x240(medium)</option>
                      <option value="640x480" selected="selected">640x480(large)</option>
                  </select>
                  
                  <input type="submit" value="upload">&nbsp; 
                  -->
                  <br>&nbsp; 
               </form>
              </div>
        
         
              <!------------- Existing Pictures listed ------------>
              <? if ($image_type == ""){$image_type = ".";}?>
              
                
                <div class="existing_photos_box">   
                    <table>
                    <?
                    
                    $counter = -1;
                    $dirpath = "pageimages";
                    $dh = opendir($dirpath);
                    while (false !== ($file = readdir($dh))) {
                    $filepath=$dirpath."/".$file;
                    if (!is_dir("$dirpath/$file")) {
                              $array = getimagesize($filepath);
                              if ( (stristr($filepath,"thumb_"))  AND (stristr($filepath,$image_type)) AND (!stristr($filepath,"top")) ) {
                                  $actual_filepath = $dirpath."/".substr($file,6);
                                  $counter ++;
                                  if (($counter % 4) == 0){?> <tr><?}
                                  $array = getimagesize($actual_filepath);
                                  $width = $array[0];
                                  $height = $array[1];
                                  $existing_file = $dirpath."/".$file;
                                  ?>
                                  <td>
                                     <? echo "<a href='".$_SERVER['PHP_SELF']."?existing_picture=$existing_file'>";?> 
                                      <img src="<? echo $existing_file; ?>" width="100"><br>
                                      <div align="center" style="width: 100; background-color: lightgrey; border: 1px solid black;">
                                          <? echo $width."x".$height; ?>
                                      </div>
                                      </a>
                                  </td>
                                  <?
                                  }
                    }
                    }
                    closedir($dh); 
                    ?> 
                    </table>
                 </div>
            
                 <br>
                 <!-- Selected picture shown -->
                 <? if ($existing_picture){?>
                     <div style="border: 2px solid black;text-align: center; width: 230; position: absolute; top: 2em;;right: 5;">
                        <br>
                        <img src="<? echo $existing_picture; ?>" width="180">
                        <div style="position: relative; top: -5; width: 100%; background-color: gray;">
                          <?
                          $find_thumb = strpos($existing_picture,"thumb_")+6;
                          $actual_filename = substr($existing_picture,$find_thumb);
                          $array = getimagesize("pageimages/".$actual_filename);
                          $actual_image_address = "pageimages/".$actual_filename;
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
                            <input type="hidden" name="existing_picture" value="<? echo $existing_picture; ?>">
                            <input type="hidden" name="action" value="finish_whizzypic_mine_manage">
                        </form>
                         </div>
                </div>
                <?}?>
   
               
             
            
            
                                                  
         
           
           <?
           
