<?php
function edit_block($id, $block_id, $blogid, $blog_action, $edit_params){            
            
            $page = $_SESSION['page'];
            
            $db = connect_pdo("checkout");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				    $sql = "SELECT * FROM blocks where  id = '$id'";
				    $stmt = $db->query($sql);
				    $rows = $stmt->fetchAll();
            $row_block = $rows[0];
            
           
            $current_content = $row_block['content'];
            $background = $row_block['background'];
            $border = $row_block['border'];
            $type = $row_block['type'];
            $content = $row_block['content'];
            $style_type = $row_block['style_type'];
            
            ?>
            <script type="text/javascript" src="whizzywig.js"></script>
            <script type="text/javascript">cssFile = "wizzywig.css";buttonPath = "btn/";imageBrowse = "whizzypic.php";</script> 
            <?php
             echo "thetype=".$type;
            //------------------------------------------------------------ Edit block parameters-----------------------------------
            if ($edit_params == "yes") {
            
            ?>
                <script type="text/javascript">
                  jQuery(document).ready(function(){
                      jQuery("#background").spectrum({
                      showInput: true,
                      showAlpha: true,
                      
                      showSelectionPalette: true, // true by default
                      selectionPalette: ["rgb(0,51,102)"],
                      color: jQuery("<?=$block_id;?>").css("backgroundColor"),
                      move: function(color){
                          jQuery("#<?=$block_id;?>").css("background-color", color.toRgbString())
                      },
                      change: function(color) {
                          jQuery("#background").val(color.toRgbString());
                          this.form.submit();
                      },
                      clickoutFiresChange: true
                    });
                  });
                </script>
                
	           
                
                
                <div id="editbox" class="editbox" style="width: 380px; height: 500px; left: 45; top: 32;display: block;">
                  <!---- title box--->
                  <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin: 7px 15px 5px 5px;background:orange;border:1px solid black;">
                        Edit Block Parameters<br>Block type = <?=$type;?>
                  </div>
                  
                  <form  name="edit_block" id="edit_block" method="post" action="mypage_finish.php?action=finish_edit_block_parameters">
                      <!-- Background color -->
                      <img src="spacer.gif" width="10">Background Color<br>
                      <img src="spacer.gif" width="10"><input type="text" size="10" id="background" name="background" value="<?=$background;?>">
                      <div id="picker_box" style="height: 270;position: absolute; left: 200; top: 50; border: 1px solid black; display: none;background: white;z-index: +5;">
                      </div>
                      <div id="picker_box_button" style="display: none; position: absolute; left: 205; top: 280; background: white;z-index: +5;">
                          <input style="position: absolute; bottom: 0;left: 0;z-index: +5;" type="submit" value="Enter Selected Color">
                      </div>
                      <button type="button" onClick="document.edit_block.background.value='none'; document.edit_block.submit();">No Background</button>
                      <br>
                      <!-- border -->
                      <img src="spacer.gif" width="20">Border<br>
                      <img src="spacer.gif" width="6">
                      <select name="border" onChange="document.edit_block.submit();">
                          <option value="<?=$border;?>" selected><?=$border;?></option>
                          <option value="0;">No Border</option>
                          <option value="1px solid black;">Thin Black</option>
                          <option value="2px solid black;">Medium Black</option>
                          <option value="4px solid black;">Thick Black</option>
                          <option value="1px solid white;">Thin white</option>
                          <option value="2px solid white;">Medium white</option>
                          <option value="4px solid white;">Thick white</option>
                      </select>
                      <input type="hidden" name="block_type" value="<?=$type;?>">
                      <input type="hidden" name="id" value="<?=$id;?>"><br>
                      <?php
                      //----- links style select box --
                      if ($type=="link"){?>
                          <div style="position: absolute; right: 5px;top: 70px; border: 1px solid black; padding: 5;width: 108; height: 160;">
                              Link style<br>
                              <img style="cursor: pointer;" src="images/leftside1.jpg" onMouseOver="this.style.border= '2px solid blue';" onMouseout="this.style.border= 'none';" onClick="document.edit_block.style_type.value='leftside1'; document.edit_block.submit();"><br>
                              <br>
                              <img style="cursor: pointer;" src="images/leftside2.jpg" onMouseOver="this.style.border= '2px solid blue';" onMouseout="this.style.border= 'none';" onClick="document.edit_block.style_type.value='leftside2'; document.edit_block.submit();"><br>
                              <br>
                              <img width="100" style="cursor: pointer;" src="images/leftside3.jpg" onMouseOver="this.style.border= '2px solid blue';" onMouseout="this.style.border= 'none';" onClick="document.edit_block.style_type.value='leftside3'; document.edit_block.submit();"><br>
                              <input type="hidden" name="style_type" value="<?=$style_type;?>">
                          </div>
                          <?php }
                          if ($type=="download" || $type=="link" || stristr($type,"blog")){?>
                          <!-- block title -->
                          <div style="position: absolute; left: 20px; top: 166px;">
                              <?php
                              
                              $block_title = $row_block['title'];
                              ?>
                              Block Title<br>
                              <input autocomplete="off" type="text" name="block_title" size="16" value="<?=$block_title;?>">
                              <br>
                              <input type="submit" value="Update Block Title" style="width: 120;">
                              <input type="hidden" name="type" value="<?=$type;?>">
                          </div>
                      <?php }
                        if($type=="download"){ ?>
                        <div style="position: absolute; left: 20px; top: 227px;">
                        <a href="index.php?action=enter_download&page=<?=$page;?>&block_id=<?=$row_block['id'];?>" style="color: blue;">Upload a file for this download block</a>
                        </div>
                        <?php } ?>
			                  <!-- Fixed Height buttons -->
			                  <div style="position: absolute; left: 10px; top: 246px;">  
			                      Preset Height 
			                      <input type="submit" name="height" value="200">
			                      <input type="submit" name="height" value="300">
			                      <input type="submit" name="height" value="600">
			                      <input type="submit" name="height" value="1200">
			                      <input type="submit" name="height" value="2000">
			                      <input type="submit" name="height" value="0">
			                  </div>
			                  <br><br>
			                </form>
                  
                  <!-- Copy block -->
                  <div style="position: absolute; left: 20; top: 294;">
                    <form  name="copy_block" method="post" action="mypage_finish.php?action=finish_copy_block">
                        Copy this block to Page
                        <?php
                        
                        $option_block_pages = '';
                        $sql_pages = "SELECT * FROM blocks where type = 'page' group by page order by page";
                        $stmt = $db->query($sql_pages);
	    									$rows = $stmt->fetchAll();
	    									foreach($rows as $row_pages){
                        		$content_title = unserialize($row_pages['content']);
                            $page_title = $content_title['page_title'];
                            if ($row_pages['page']!= 0){$option_block_pages .="<option value='".$row_pages['page']."'>".$row_pages['page']."-".$page_title."</option>";}
                          }
                        ?>
                        <select name="new_page" onChange="this.form.submit();">
                        <option selected><?=$row_block['page'];?></option>
                        <?=$option_block_pages;?>
                        </select>
                        <input type="hidden" name="id" value="<?=$id;?>">
                    </form>
                </div>
                
                <!-- positon info  -->
                <div style="position:absolute;left:20px;top:315px;">
                    <form style="margin:0;" name="move_page"  method="post" action="mypage_finish.php?action=move_block"> 
                       <input type="hidden" name="page" value="<?=$page;?>">
                       <img src="images/spacer.gif" width="15" height="1">Left<img src="images/spacer.gif" width="20" height="1">Top<img src="images/spacer.gif" width="32" height="1">Width<img src="images/spacer.gif" width="20" height="1">Height<br>
                       <input type="text" size="3" name="xpos" value="<?=$row_block['xpos'];?>">
                       <input type="text" size="3" name="ypos" value="<?=$row_block['ypos'];?>"> 
                       <input type="text" size="3" name="width" value="<?=$row_block['width'];?>">
                       <input type="text" size="3" name="height" value="<?=$row_block['height'];?>">
                       <input type="submit" value="Enter">
                       
                      <input type="hidden" name="id" value="<?=$id;?>">
                  
                    </form>
                </div>
               <!-- Make this a site link box (make all links go to all the pages, with titles -->
               <?php
               if ($type=="link"){?> 
              <div style="position: absolute; bottom: 5px;left:190px; width: 150px; height: 60px; border: 2px solid black;padding: 5px;margin: 5px;text-align: center;">
              		Make this a site link box &nbsp;<a class="button" style="font-size:14px;" href="mypage_finish.php?action=make_site_link&page=<?=$page;?>&id=<?=$id;?>">YES</a>
            	</div> 
            <?php } ?>  
            
            	<!-- Copy block to another database -->
                  <div style="position: absolute; left: 20; top: 359;">
                  		
                  		<form method="post" action="mypage_finish.php?action=copy_to_database">
                  			Copy to database<br>
                  			<span style="margin-left:5px;">Database</span>
                  			<span style="margin-left:10px;">User</span>
                  			<span style="margin-left:25px;">Pass</span>
                  			<span style="margin-left:20px;">Page</span>
                  			<br>
                  			<input type="text" name="dbase_name" style="width:60px;">
                  			<input type="text" name="dbase_user" style="width:60px;">
                  			<input type="text" name="dbase_pw" style="width:60px;">
                  			<input type="text" name="page_number" style="width:30px;">
                  			
                  			<input type="hidden" name="id" value="<?=$id;?>">
                  			<input type="submit" value="Enter">	
                  		</form>
                  </div> 
                
              <!-- Delete options --> 
              <div style="position: absolute; bottom: 5; width: 150px; height: 60px; border: 2px solid black;padding: 5px;margin: 5px;text-align: center;">
                  <br>
                  <a title="Delete Block <?=$id;?>" class="red_button" href="mypage_finish.php?action=delete_block&id=<?=$id;?>&question=yes" onClick="return deletechecked();">Delete Block</a>
              </div>
              <?php
              if(stristr($row_block['type'],'blog')){
              ?> 
              <!-- Delete blog and block--> 
              <div style="position: absolute;left:170px; bottom: 5; width: 170px; height: 60px; border: 2px solid black;padding: 5px;margin: 5px;text-align: center;">
                  <form  name="delete_block"  method="post" action="mypage_finish.php" style="margin:0;">
                      Delete blog and block?<br>
                      <select name="question">
                          <option value="no">No</option>
                          <option value="yes">Yes</option>
                      </select>
                      <input type="hidden" name="id" value="<?=$id;?>">
                      <input type="hidden" name="blog_and_block" value="yes">
                      <input type="hidden" name="action" value="delete_block" >
                      <input type="submit" style="background: red;margin:0;" value="Delete">
                  </form>
              </div>
              <?php } ?> 
                
                <span class="edit_box" id="exit_box" style="top: 0; right: 0;"><a class="exit_box" href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span> 
            
            </div>
            <?php }
                
            
            
            //------------------------------------------------------------ General Block-----------------------------------
            if ($type == "general" && $edit_params !='yes') {
            ?>
            
            
               <form  class="inner" name="edit_general_form"  method="post" action="mypage_finish.php?action=finish_edit_general"  
                            style="background: #eee; padding: 1em; padding-top: 5; margin-bottom: 0;position: absolute; left: 45; top: 32; width: 760; height: 680;border: 2px solid black;">
                            <!-- title for block -->
                                <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 5;">
                                  <span style="background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black;">
                                      Edit Text for Block
                                  </span>
                                </div>
                            
                            
                            <textarea id="content" style="width:100%;height: 400;background-color:#888;" name="content"><?=$content;?></textarea>
                            
                            
                            <input type="hidden" name="page" value="<?=$page;?>">
                            <input type="hidden" name="id" value="<?=$id;?>">
                            <script type="text/javascript">imageBrowse = "whizzypic_mine.php";</script> 
                            <script type="text/javascript">
                                buttonPath="textbuttons";
                                makeWhizzyWig("content", "fontname fontsize  bold italic underline  newline left center right  number bullet indent outdent  color hilite rule  clean html image link ", gentleClean="true");
                            </script>
                            <!-- Publish button -->
                            <input type="submit" value="Enter Block" 
                              onMouseOver="this.style.background='white';" onMouseOut="this.style.background='orange';"
                              style="cursor: pointer; margin: 5; background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black; font-size: 1.2em; font-weight: bold;">
                            <!-- Red X in upper right corner -->
                            <span style="position: absolute; top: 0; right: 0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx_xp.gif"></a></span>
                            
                        </form>
                
                <?php }
          //------------------------------------------------------------ Youtube Block-----------------------------------
            if ($type == "youtube" && $edit_params !='yes') { ?>     
                <form  class="inner" name="edit_general_form"  method="post" action="mypage_finish.php"  
                    style="background: white; padding: 1em; padding-top: 5; margin-bottom: 0;position: absolute; left: 45; top: 32; width: 460; height: 200;border: 2px solid black;">
                    <!-- title for block -->
                        <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 10px;">
                          <span style="background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black;">
                              Edit Youtube Block
                          </span>
                        </div>
                    
                    
                    Enter Youtube 11 digit video code<br>
                    <input type="text" name="content" value="<?=$content;?>">
                    
                    
                    <input type="hidden" name="action" value="finish_edit_general">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    
                    <input type="submit" value="Enter Block" 
                      onMouseOver="this.style.background='white';" onMouseOut="this.style.background='orange';"
                      style="cursor: pointer; margin: 5; background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black; font-size: 1.2em; font-weight: bold;">
                    <!-- Red X in upper right corner -->
                    <span style="position: absolute; top: 0; right: 0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx_xp.gif"></a></span>
                    
                </form>
                <?php
                
            }
            
        //------------------------------------------------------------ RSS Block-----------------------------------
           
            if ($type == "rss" && $edit_params !='yes') { ?>     
                <form  class="inner" name="edit_general_form"  method="post" action="mypage_finish.php"  
                    style="background: white; padding: 1em; padding-top: 5; margin-bottom: 0;position: absolute; left: 45; top: 32; width: 460; height: 200;border: 2px solid black;">
                    <!-- title for block -->
                        <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 10px;">
                          <span style="background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black;">
                              Edit RSS Block
                          </span>
                        </div>
                    
                    
                    Enter Your RSS Feed Address(URL)<br>
                    <input type="text" style="width:420px;" name="content" value="<?=$content;?>">
                    <br><br>
                    How may items to list?<br>
                    <input type="text" name="num_items" style="width:30px;" value="<?=$row_block['style_type'];?>">
                    <br><br>
                    
                    <input type="hidden" name="action" value="finish_edit_rss">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    
                    <input type="submit" value="Enter" style="font-size:20px;"  >
                      <!-- Red X in upper right corner -->
                    <span style="position: absolute; top: 0; right: 0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx_xp.gif"></a></span>
                    
                </form>
                <?php
                
            }
            
            //------------------------------------------------------------ Include-----------------------------------
           
            if ($type == "include" && $edit_params !='yes') { ?>     
                <form  class="inner" name="edit_general_form"  method="post" action="mypage_finish.php"  
                    style="background: white; padding: 1em; padding-top: 5; margin-bottom: 0;position: absolute; left: 45; top: 32; width: 460; height: 200;border: 2px solid black;">
                    <!-- title for block -->
                        <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 10px;">
                          <span style="background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black;">
                              Edit Include Block
                          </span>
                        </div>
                    
                    
                    Enter the address of your include<br>
                    <input type="text" style="width:420px;" name="content" value="<?=$content;?>">
                    <br><br>
                    
                    
                    <input type="hidden" name="action" value="finish_edit_include">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    
                    <input type="submit" value="Enter" style="font-size:20px;"  >
                      <!-- Red X in upper right corner -->
                    <span style="position: absolute; top: 0; right: 0;"><a href="index.php?page=<?=$page;?>"><img src="images/redx_xp.gif"></a></span>
                    
                </form>
                <?php
                
            }
                
         //----------------------------------------  type = Blog --------------------------------------------------       
            if (stristr ($type,"blog")) {?>
                <?php
                //--Selected blog edit box --
                if ($blog_action == "edit_blog"){
                    $sql_blog = "SELECT * FROM $type where id = '$blogid' ";
                    $result_blog = mysql_query ($sql_blog, $db);
                    $row_blog = mysql_fetch_array($result_blog, MYSQL_ASSOC);
                    ?>
                        <div style="background: white; padding: 1em; padding-top: 5; margin-bottom: 0;position: absolute; left: 0; top: 0px; width: 760px;border: 2px solid black;">
                        <form  class="inner" name="edit_block_blog"  method="post" action="mypage_finish.php">
                            <!-- title for block (Enter/Edit Blog Post) -->
                            <?php if ($blogid){?>
                                <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 5;">
                                  <span style="background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black;">
                                      Edit Blog Post
                                  </span>
                                </div>
                            <?php }
                            else {?>
                                <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 5;">
                                  <span style="background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black;">
                                      Enter New Blog Post
                                  </span>
                                </div>
                            <?php } ?>
                            <!-- blog date if editing a post -->
                            <?php if ($blogid){?><div style="margin-bottom: 5;">Blog Date: <input type="text" name="date" value="<?=$row_blog[date];?>"></div><?php } ?>
                            <!-- Blog title -->
                            <div style="margin-bottom: 5;">Title for this entry&nbsp;&nbsp;&nbsp; <input type="text" size="40" name="blog_title" value="<?=$row_blog['blog_title'];?>"></div>
                            <!-- Blog content -->
                            <textarea id="content" style="width:100%;height: 500px;" name="content"><?=$row_blog['content'];?></textarea>
                            <input type="hidden" name="blog_id" value="<?=$blogid;?>">
                            <input type="hidden" name="type" value="<?=$type;?>">
                            <input type="hidden" name="action" value="finish_blog_edit">
                            <script type="text/javascript">imageBrowse = "whizzypic_mine.php";</script> 
                            <script type="text/javascript">
                                makeWhizzyWig("content", "fontname fontsize  bold italic underline  newline left center right  number bullet indent outdent  color hilite rule  clean html image link ", gentleClean="true");
                            </script>
                            <!-- Publish button -->
                            <input type="submit" value="Publish Post" 
                              onMouseOver="this.style.background='white';" onMouseOut="this.style.background='orange';"
                              style="cursor: pointer; margin: 5; background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black; font-size: 1.2em; font-weight: bold;">
                            <!-- Red X in upper right corner -->
                            <?php if ($row_blog[date]){
                                    $temp_date = strtotime($row_blog[date]);
                                    $selected_year = date('Y', $temp_date);
                                    }
                                else {
                                    $temp_date = strtotime($nowdate);
                                    $selected_year = date('Y', $temp_date);
                                  }
                            ?>
                            <span style="position: absolute; top: 0; right: 0;"><a href="index.php?page=<?=$page;?>&selected_year=<?=$selected_year;?>"><img src="images/redx_xp.gif"></a></span>
                            <!-- Delete post options -->
                            <div style="position: absolute; bottom: 1px; right: 5px;">
                                <select name="question" style="font-size: 1.2em;position:relative; bottom: 4;">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                                <input type="submit" value="Delete Post" 
                                  onMouseOver="this.style.background='white';" onMouseOut="this.style.background='red';"
                                  style="cursor: pointer; margin: 5; background: red; padding-left: 1em; padding-right: 1em; border: 1px solid black; font-size: 1.2em; font-weight: bold;">
                            </div>
                        </form>
                        </div>
                        <div style="width:270px;background: white;position:absolute;left:760px;top:0;border:2px solid black;">
                          <?php include_once("smugmug_show_images.php");?>
                        </div>
                 <?php }
           }
//---------------------------------------- Style type = Picture --------------------------------------------------       
            if ($type == "picture") {
            $array = getimagesize($content);
            $width = $array[0];
            $height = $array[1];
            ?>
              <div id="editbox" style="width: 600px; height: 500; left: 20; top: 25;">
                  <div style="position: absolute; left: 5px; top: 5px; width: <?=$width;?>;height:<?=$height;?>;">
                      <h3>Currently selected Photo</h3>
                      <img src="<?=$content;?>" width="200" style="border:1px solid black;">
                  </div>
                  <!--------------- Page Block properties ------------->
                  <div style="position: absolute; top: 5px; left: 220px; height: 80;  width: 350;background-color: #ddd; border: 1px solid black; padding-top: 5px; ">
                       
                      <form  name="edit_picture_parameters"  method="post"  action="mypage_finish.php?action=finish_edit_block_parameters">
                       <div align="center" class="large_bold">Page Block Properties</div>
                        
                        
                        
                        <div align="center" style="position: absolute; left: 20; top: 25;">
                            Block Type<br>
                            <select name="block_type">
                                <?=get_block_types($type);?>
                                
                            </select>
                        </div>
                        <div align="center" style="position: absolute; left: 120; top: 5;">
                            <br>
                            Border<br>
                            
                           
                            <select name="border">
                            <option value="<?=$border;?>" selected><?=$border;?></option>
                            <option value="0;">No Border</option>
                            <option value="1px solid black;">Thin Black</option>
                            <option value="2px solid black;">Medium Black</option>
                            <option value="4px solid black;">Thick Black</option>
                            <option value="1px solid white;">Thin white</option>
                            <option value="2px solid white;">Medium white</option>
                            <option value="4px solid white;">Thick white</option>
                            </select>
                            <input type="hidden" name="type" value="<?=$type;?>">
                            <input type="hidden" name="id" value="<?=$id;?>"><br>
                        </div>
                        <div align="center" style="position: absolute; left: 255; top: 42;">
                            <input type="submit" value="UPDATE" >
                        </div>
                        
               
             
                      </form>
                  </div>
                  
                  <!------------- Existing Pictures listed ------------>
                
                
                <div style="position: absolute; left: 220; bottom: 390; font-weight: bold;">Choose a Picture for this block</div>
                <div style="position: absolute; left: 220; bottom: 5px; height: 380; width: 350; overflow: auto; background-color: #ddd; border: 1px solid black;">   
                    <table>
                    <?php
                    $counter = -1;
                    $dir = "pageimages/*.*";
                    $dirpath = "pageimages";
                    foreach(glob($dir) as $file){  
                    //$dh = opendir($dirpath);
                    //while (false !== ($file = readdir($dh))) {
                    //$filepath=$dirpath."/".$file;
                    //if (!is_dir("$dirpath/$file")) {
                              
                              if ((strpos($file,"thumb_")) && (! strpos($file,"thumb_top")) ) {
                                  $counter ++;
                                  if (($counter % 3) == 0){?> <tr><?php }
                                  $actual_filepath = str_replace('thumb_','',$file);
                                  //echo "actual_filepath=".$actual_filepath;
                                  $array = getimagesize($file);
                                  $width = $array[0];
                                  $height = $array[1];
                                  $thumb_file = $file;
                                  
                                  ?>
                                  <td>
                                      
                                      <a href="mypage_finish.php?action=existing_picture&existing_file=<?=$actual_filepath;?>&id=<?=$id;?>"> 
                                      
                                          <img src="<?=$thumb_file;?>" width="100" style="border:1px solid black;"><br>
                                      
                                      </a>
                                  </td>
                                  <?php
                                  }
                    }
                    //}
                    
                    ?> 
                    </table>
                 </div> 
                 
                 
                 <div align="center" style="position: absolute; left: 12px; bottom: 24; width: 110;">
                  <a title="Delete Block <?=$id;?>" class="red_button" href="mypage_finish.php?action=delete_block&id=<?=$id;?>&question=yes" onClick="return deletechecked();">Delete Block</a>
                </div>
              
                  <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
              </div>
              <?php
            }
            
//---------------------------------------- Style type = download (just background,border, delete) --------------------------------------------------       
            
            if ($type == "download1"){
                
                ?>
                <script type="text/javascript">
                  jQuery(document).ready(function(){
                      jQuery("#background").spectrum({
                      color: jQuery("<?=$block_id;?>").css("backgroundColor"),
                      move: function(color){
                          jQuery("#<?=$block_id;?>").css("background-color", color.toHexString())
                      },
                      change: function(color) {
                          jQuery("#background").val(color.toHexString());
                      },
                      clickoutFiresChange: true
                    });
                  });
                </script>
                <?php
            $links_array = unserialize($row_block[content]);
            $current_block_title = $links_array['block_title'][0];
            ?>
                 <!--------------- Page Block properties ------------->
                 <form  name="edit_download_parameters"  method="post" action="mypage_finish.php?action=finish_download_parameters">
                    <div style="position: absolute; top: 60; left: 80; height: 220;  width: 440;background-color: #ddd; border: 3px solid blue; padding-top: 5px; ">
                      
                      <div align="center" class="large_bold">Page Block<br>Properties</div>
                      <img src="spacer.gif" width="10">Background Color<img src="spacer.gif" width="10">
                      Block Type<br>
                      
                      <img src="spacer.gif" width="25">
                      <input type="text" size="10" id="background" name="background" value="<?=$background;?>">
                      <img src="spacer.gif" width="30">
                      <select name="block_type">
                          <option value="<?=$type;?>"><?=$type;?></option>
                          <option value="general">General</option>
                          <option value="picture">Picture</option>
                          <option value="blog">Blog</option>
                          <option value="download">Download</option>
                      </select>
                      <br>
                      <img src="spacer.gif" width="20">Border<br>
                     <img src="spacer.gif" width="6">
                      <select name="border">
                      <option value="<?=$border;?>" selected><?=$border;?></option>
                      <option value="0;">No Border</option>
                      <option value="1px solid black;">Thin Black</option>
                      <option value="2px solid black;">Medium Black</option>
                      <option value="4px solid black;">Thick Black</option>
                      <option value="1px solid white;">Thin white</option>
                      <option value="2px solid white;">Medium white</option>
                      <option value="4px solid white;">Thick white</option>
                      </select>
                      <br>
                      <img src="spacer.gif" width="20">Block title<br>
                      <img src="spacer.gif" width="6"><input type="text" name="block_title" value="<?=$current_block_title;?>">
                      <input type="hidden" name="id" value="<?=$id;?>"><br>
                      <input style="position: relative; top: 10; left: 14;" type="submit" value="UPDATE" >
                    </form>
                  <span class="edit_box" style="top: 5; right: 5;"><a href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span>
                  <?php show_delete_box($id); ?>
                  </div>
                      
                 </form>
                 <?php
            }
            

 //------------------------------------------------------------ Video Block-----------------------------------
            if ($type == "video1") {
            ?>
                <div>
                
                
                <!--------------- Page Block properties ------------->
                <div style="position: absolute; top: 0; left: 435; height: 180;  width: 240;background-color: lightblue; border: 3px solid blue; padding-top: 5px; ">
                  <form  name="edit_block" id="edit_block" method="post" action="" onsubmit="back_to_form1();">
                  <input type="hidden" name="content">
                  <div align="center" class="large_bold">Page Block<br>Properties</div>
                  
                  <img src="spacer.gif" width="10">Background Color<img src="spacer.gif" width="10">
                  Block Type<br>
                  
                  
                  <img src="spacer.gif" width="10"><input type="text" size="10" name="background" value="<?=$background;?>">
                  <img onclick="popup_colorpicker()" src="images/colorbox.gif" width="15">
                  <select name="block_type">
                      <option value="<?=$type;?>"><?=$type;?></option>
                      <option value="general">Regular</option>
                      <option value="picture">Picture</option>
                      <option value="blog">Blog</option>
                      <option value="video">Video</option>
                  </select>
                  <br>
                  <img src="spacer.gif" width="20">Border<br>
                  
                 <img src="spacer.gif" width="6">
                  <select name="border">
                  <option value="<?=$border;?>" selected><?=$border;?></option>
                  <option value="0;">No Border</option>
                  <option value="1px solid black;">Thin Black</option>
                  <option value="2px solid black;">Medium Black</option>
                  <option value="4px solid black;">Thick Black</option>
                  <option value="1px solid white;">Thin white</option>
                  <option value="2px solid white;">Medium white</option>
                  <option value="4px solid white;">Thick white</option>
                  </select>
                  
                  <input type="hidden" name="id" value="<?=$id;?>"><br>
                  
                  <input style="position: relative; top: 10; left: 14;" type="image" align=ABSBOTTOM src="images/update.gif" >
                  
                 
                </form>
                
                
                </div>
                <?php show_delete_box($id); ?>            
<?php }

}

//------------------------------------------------------------ Edit block parameters-----------------------------------
            function edit_block_params($id, $block_id) {
            ?>
            <script type="text/javascript">
            	
            	$(document).ready(function(){
              
              
              $("a").filter(".edit_params").click(function(){jQuery("#editbox").slideDown("slow");})

             $("a").filter(".exit_box").click(function(){jQuery("#editbox").slideUp("2500");})

             $('#<?=$block_id;?>').farbtastic('#background');
              
              });	
	           </script>
	           
                
                <div id="editbox" style="width: 270; height: 400; left: 45; top: 32;display: none;">
                  <!---- title box--->
                  <div style="text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 5;">
                    <span style="background: orange; padding-left: 1em; padding-right: 1em; border: 1px solid black;">
                        Edit Block Parameters
                    </span>
                  </div>
                  <form  name="edit_block" id="edit_block" method="post" action="mypage_finish.php?action=finish_edit_block_parameters">
                  
                      <img src="spacer.gif" width="10">Background Color<br>
                      <img src="spacer.gif" width="10"><input type="text" size="10" id="background" name="background" value="<?=$background;?>">
                      <img id="myRainbow" src="images/rainbow.png" alt="[r]" width="16" height="16">
                      <br>
                      <img src="spacer.gif" width="20">Border<br>
                      <img src="spacer.gif" width="6">
                      <select name="border" onChange="document.edit_block.submit();">
                          <option value="<?=$border;?>" selected><?=$border;?></option>
                          <option value="0;">No Border</option>
                          <option value="1px solid black;">Thin Black</option>
                          <option value="2px solid black;">Medium Black</option>
                          <option value="4px solid black;">Thick Black</option>
                          <option value="1px solid white;">Thin white</option>
                          <option value="2px solid white;">Medium white</option>
                          <option value="4px solid white;">Thick white</option>
                      </select>
                      <input type="hidden" name="id" value="<?=$id;?>"><br>
                      
                      <?php
                      //----- links style select box --
                      if ($type=="link"){?>
                          <div style="position: absolute; right: 10;top: 40; border: 1px solid black; padding: 5;width: 100; height: 160;">
                              Link style<br>
                              <img style="cursor: pointer;" src="images/leftside1.jpg" onMouseOver="this.style.border= '2px solid blue';" onMouseout="this.style.border= 'none';" onClick="document.edit_block.style_type.value='leftside1'; document.edit_block.submit();"><br>
                              <br>
                              <img style="cursor: pointer;" src="images/leftside2.jpg" onMouseOver="this.style.border= '2px solid blue';" onMouseout="this.style.border= 'none';" onClick="document.edit_block.style_type.value='leftside2'; document.edit_block.submit();"><br>
                              <br>
                              <img width="100" style="cursor: pointer;" src="images/leftside3.jpg" onMouseOver="this.style.border= '2px solid blue';" onMouseout="this.style.border= 'none';" onClick="document.edit_block.style_type.value='leftside3'; document.edit_block.submit();"><br>
                              
                              <input type="hidden" name="style_type" value="<?=$style_type;?>">
                          </div>
                          <div style="position: absolute; left: 5; top: 152;">
                              <?php
                              $links_array = unserialize($content);
                              $block_title = $links_array['block_title'][0];
                              ?>
                              <img src="spacer.gif" width="20" height="1">Block Title<br>
                              <img src="spacer.gif" width="10" height="1"><input type="text" name="block_title" size="16" value="<?=$block_title;?>">
                              <br>
                              <img src="spacer.gif" width="10" height="1"><input type="submit" value="Update Block Title" style="width: 120;">
                          </div>
                          <div id="editbox" style="width: 270; left: 545; top: 32;">
                              <?=LIST_CONTENTS($links_array);?>
                          </div>
                      <?php } ?>
                </form>
                <br><br>
                
              <!-- Delete options --> 
              <div style="position: absolute; bottom: 5; width: 180; height: 80; border: 2px solid black;padding: 5;margin: 5;text-align: center;">
                  <form  name="delete_block"  method="post" action="mypage_finish.php">
                      Delete this block?&nbsp;
                      <?="id=".$id."<br>";?>
                      <select name="question">
                          <option value="no">No</option>
                          <option value="yes">Yes</option>
                      </select>
                      
                      <input type="hidden" name="id" value="<?=$id;?>">
                      <input type="hidden" name="action" value="delete_block" >
                      <input type="submit" style="background: red;" value="Delete">
                  </form>
              </div>
               
                
                <span class="edit_box" id="exit_box" style="top: 0; right: 0;"><a class="exit_box" href="index.php?page=<?=$page;?>"><img src="images/redx1.jpg"></a></span> 
            
            </div>
            <?php }






//----------------------------- Show Delete Box -------------------------------------------------------------------------------------
          function show_delete_box($id){?>
                
                
                <div align="center" style="position: absolute; right: 1; top: 24; width: 110; background-color: lightyellow; border: 1px solid black;">
                  <form  name="delete_block"  method="post" action="mypage_finish.php">
                      Delete this block?<br>
                      <?="id=".$id."<br>";?>
                      <select name="question">
                          <option value="no">No</option>
                          <option value="yes">Yes</option>
                      </select>
                      <br>
                      <input type="hidden" name="id" value="<?=$id;?>">
                      <input type="hidden" name="action" value="delete_block" >
                      <input type="submit" value="Delete">
                  </form>
                </div> 
              <?php }      
//--------------------------------get block types ------------------------------------------------------------------------------------
function get_block_types($selected_type){
    $str = "";
    $db = connect_pdo();
    $sql_block = "SELECT * FROM block_types";
    $stmt = $db->query($sql_block);
	  $rows = $stmt->fetchAll();
	  foreach($rows as $row_block){
        $type = $row_block['type'];
        if($type == $selected_type){$str .="<option selected='selected' value='$type'>$type</option>";}
        else{$str .="<option value='$type'>$type</option>";}   
    }
    return $str;
}   

//--------------------------------get block types without blog in list ------------------------------------------------------------------------------------
function get_block_types_noblog($selected_type){
    $str = "";
    $db = connect_pdo();
    $sql_block = "SELECT * FROM block_types";
    $stmt = $db->query($sql_block);
	  $rows = $stmt->fetchAll();
	  foreach($rows as $row_block){
        $type = $row_block['type'];
        if($type == 'blog'){continue;}
        if($type == $selected_type){$str .="<option selected='selected' value='$type'>$type</option>";}
        else{$str .="<option value='$type'>$type</option>";}   
    }
    return $str;
}         
           
            

      
