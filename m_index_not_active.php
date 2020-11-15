<?php  ob_start(); session_start(); 
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');
 ?>

    <head>
    	<meta name="viewport" content="width=1000">
    <meta http-equiv="Content-Type" content="text/html; charset="utf-8">
    <link href="m.default.css" rel="stylesheet" type="text/css" />
    
    
    <link rel="stylesheet" type="text/css" href="spectrum.css">
    <script src="js/jquery-2.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="clayton_java_move.js"></script>
   
    
    <script type="text/javascript" src="spectrum.js"></script>
    
    
    
  
	
	
  <script language="javascript" type="text/javascript">
  function deletechecked(){
        var answer = confirm("Really Delete")
        if (answer){
            document.messages.submit();
        }
        return false;  
    }
    </script>
		<script type="text/javascript">
              
      $(document).ready(function() {	
       
       $( ".hidden" ).hover(
          function() {
            $(this).fadeTo(1,1);
          }, function() {
            $(this).fadeTo(1,0);
          }
        );
        
        $('#page_select_trigger, #page_select_trigger1').click(
			    function() {
			      $('#page_select_box').toggle(100);
			    }
			  );
			  
			  $(window).scroll(function(){
            if ($(this).scrollTop() > 50) {
                $('#backToTop').fadeIn('slow');
            } else {
                $('#backToTop').fadeOut('slow');
            }
        });
        $('#backToTop').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 500);
            return false;
        });
        
    });
    
   
			
		
    </script>
    <?php
    if(isset($_SESSION['page'])){$page = $_SESSION['page'];}
    else{$page = 1;}
    $action = '';
    $blog_id = '';$blogid='';$blog_action = '';
    $status = '';
    $id = '';
    $image_type = '';
    $existing_picture = '';
    $selected_year = '';
    $video = '';
    $title = '';
    $password = '';
    $edit_params = '';
    $video_id = '';
    $playlist = '';
    $force = '';
    if(!isset($_SESSION['loggedin'])){$_SESSION['loggedin'] = 'false';}
    if(!isset($_SESSION['picture_edit'])){$_SESSION['picture_edit'] = 'false';}
    
    foreach ($_REQUEST as $param_name => $param_val) {
		if($param_name == "PHPSESSID"){continue;}
    $$param_name = $param_val;
		} 
 
     
     
     if (!$page || $page == ""){
        if (isset($_SESSION['page'])){
        	if($_SESSION['page'] != ''){$page = $_SESSION['page'];}
        	}
        else {$page = "1"; $_SESSION['page'] = $page;}
        }
     
      else {$_SESSION['page'] = $page;  $_GET['page'] = '';}
    
    	
     
    ?>
    </head>
    <a href="#" id="backToTop"></a>
    <?php 
   
    include_once("functions.php");
    include_once("functions_edit_block.php");
    include_once("functions_connect.php");
   
    // test if mysql user is setup 
    if($sql_user == ""){
        header("location:new_config.php");
        exit;
    } 
     
    $db = connect_pdo();
    if(!$db){
    	echo "database does not exist";
        header("location:new_config.php");
        exit;
    }
    
    $db = connect_pdo();
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
	    $sql = "SELECT * FROM blocks where  page = '$page' AND type ='page' ";
	   
	    $stmt = $db->query($sql);
	    $rows = $stmt->fetchAll();
	    if(!$rows){
	    	//create_tables();
        exit;
      }
     
      $row = $rows[0];
		   
    
    $page_array = unserialize($row['content']);
    $background = $page_array['background'][1];
    $mainbox_background = $page_array['mainbox_background'][1];
    $page_title = $page_array['page_title'];
    $page_password = $page_array['password'];
    
    
    ?>
    <body id="outer" style="background-color: <?=$background;?>;">
    
    <title><?=$page_title;?></title>
    
   
    
    

<table width="1024"  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000" bgcolor"#000000">
    <td <?php if ($_SESSION['loggedin'] == "true"){?>style="border:1px solid black;" <?php } ?>>
    
    <?php 
    //- check if page needs password -------
    $session_page_name = "loggedin_page".$page;
    if (($action == "login_page")AND($status!="entered")){
        ?>
        <div id="editbox" style="height: 200;">
          <a href="index.php"style="position: absolute; top: 0; right: 0;"><img src="images/redx1.jpg"></a>
          <form name="login_page_form"  method="post" action="index.php?action=login_page&status=entered"> 
              Enter Password for that Page<br>
              <input type="password" name="password">
              <input type="submit" value="Enter">
              <input type="hidden"" name="page" value="<?=$page;?>">
          </form>
          <br>
          <a href="?page=1">Back to Home Page</a>
      </div>
        <?php exit;}
    if (($action == "login_page")AND($status=="entered")){
        //echo "password=xx".$password."xx page_password=xx".$page_password."xx";
        //if ($password == $page_password){echo "match";exit;}
        if ($password == $page_password){$_SESSION[$session_page_name]="true";header("Location: index.php?page=$page");exit;}
            else {?>
                    <div id="editbox" style="height: 200;">
                      <a href="index.php"style="position: absolute; top: 0; right: 0;"><img src="images/redx1.jpg"></a>
                      <form name="login_page_form"  method="post" action="index.php?action=login_page&status=entered"> 
                          Password incorrect, try again<br>
                          <input type="password" name="password">
                          <input type="submit" value="Enter">
                          <input type="hidden"" name="page" value="<?=$page;?>">
                      </form>
                      <br>
                      <a href="?page=1">Back to Home Page</a>
                    </div>
                    <?php exit;}
      } 
      
    if (($page_password !="") AND (!isset($_SESSION[$session_page_name])) AND ($_SESSION['loggedin'] != "true")) {header("Location: index.php?action=login_page&page=$page");}
    
    
      
    
    
    //-- Main loop start ------
    //$page = mysql_real_escape_string($page);
    $sql = "SELECT * FROM blocks where  page = '$page' order by block";
    $stmt = $db->query($sql);
	  $rows = $stmt->fetchAll();
	  foreach($rows as $row){ 
          $xpos = $row['xpos'];
          $ypos = $row['ypos'];
          $width = $row['width'];
          $height = $row['height'];
          $current_browser = return_browser($_SERVER['HTTP_USER_AGENT']);
          if ((strpos($current_browser,"Opera")=="true") OR ($current_browser == "Firefox")){
              $current_browser = "found opera";
              $width = $width - (1* (2 * substr($row[border],0,1)));
              $height = $height - (2 * substr($row[border],0,1));
              }
        
//---------------------------------- Type Top ------------------------------------        
        if ($row['type'] == "top"){
            $block = "block".$row['block'];
            if($row['content'] != ''){
		            $array = getimagesize($row['content']);
		            $width1 = $array[0];
		            $height1 = $array[1];
		        }  
		        else{$width1=1024;$height1="140";}
		            
            $pic_width = $width1 - (2 * substr($row['border'],0,1));
            $pic_height = $height1 - (2 * substr($row['border'],0,1));
		      
            
            if ($_SESSION['loggedin'] == "true"){?>
                <div id="<?=$block;?>" style='border: <?=$row['border'];?> background-color: <?=$row['background'];?>; '>
                
                <div style="position: absolute; left: 0; top: 30; cursor:move;" onmousedown="dragStart(event, '<?=$row['id'];?>', '<?=$block;?>')">
                    <img src="images/4arrows.gif">
                </div>
                
                <!-- Edit top Content -->
                <span class="edit_box" style="bottom: 5; right: 0; cursor:pointer;">
                    <a onclick="window1=window.open('edit_top.php?id=<?=$row['id'];?>','edit_top','menubar=1,resizable=1,width=1134,height=820')" 
                    style="text-decoration: none;" >[EDIT TOP CONTENT]</a>
                </span>
                <!-- Logout-->
                <span class="edit_box" style="top: 0; right: 0; cursor:pointer;">
                    <a href="index.php?page=<?=$page;?>&action=logout">[LOGOUT]</a>
                </span>
                <!-- Site Config -->
                <span class="edit_box" style="top: 1.5em; right: 0; cursor:pointer;">
                    <a href="?action=site_config&page=<?=$page;?>">[SITE CONFIG]</a>
                    <!-- <a onclick="window1=window.open('site_config.php?id=<?=$row['id'];?>','site_config','menubar=1,resizable=1,width=300,height=400')" >[SITE CONFIG]</a> -->
                </span>
                <!-- Page Edit -->
                <span class="edit_box" style="top: 0; left: 0; cursor:pointer;">
                    <a href="index.php?action=edit_page&page=<?=$page;?>" >[PAGE EDIT]</a>
                </span> 
                <!-- Manage Photos-->
                <span class="edit_box" style="bottom: 45px; left: 0; cursor:pointer;">
                    <a href="index.php?action=manage_photos_enable&page=<?=$page;?>">[MANAGE PHOTOS]</a>
                </span>
                <!-- Choose a page to edit, hidden box -->
                <div id="page_select_trigger" class="edit_box" style="top:0;left:100px;text-decoration:underline;cursor:pointer;display:block;">[PAGE TO EDIT]</div>
                <div id="page_select_box" class="page_select_box">
                		<span id="exit_box" class="exit_box" style="position:absolute;top:0; right:0;cursor:pointer;" title="Close"><img id="page_select_trigger1" src="images/redx1.jpg"></span> 
          					<h3 style="text-decoration:underline;">Select a page to edit</h3>
          					<?php
          					$sql_page = "SELECT MAX(page) from blocks";
                    $stmt = $db->query($sql_page);
										$rows_page = $stmt->fetchAll();
										$row_page = $rows_page[0];
                    $max_page = $row_page[0];
                                                      
                    while ($max_page > 0){
                        $sql_title = "SELECT * from blocks where page = '$max_page' and type = 'page' Limit 1";
                        $stmt = $db->query($sql_title);
                        $rows_title = $stmt->fetchAll();
                        $row_title = $rows_title[0];
                        $content_title = unserialize($row_title['content']);
                        $page_title = $content_title['page_title'];
                        if($max_page == $page){
                          ?>
                          <a href="?page=<?=$max_page;?>" style="color:green;font-weight:bold;"><?=$page_title." (".$max_page.")";?></a><br>
                          <?php
                        }
                        else{
                          ?>
                          <a href="?page=<?=$max_page;?>"><?=$page_title." (".$max_page.")";?></a><br>
                          <?php
                        }
                        $max_page = $max_page -1;
                    }
                    ?>
                                  
          			</div>
          			<!-- Edit Block Parameters button -->
                <a class="newpost" style="position: absolute; bottom: 30px; right: 0px;font-size:14px;" href="<?=$_SERVER['PHP_SELF']; ?>?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                    Edit Block Parameters
                </a>
               
                  <?php }
            
            else {
                ?>
                <div id="<?=$block;?>" style='border: <?=$row['border'];?>; background-color: <?=$row['background'];?>;'>
                <span class="edit_box hidden" style="top: 0; right: 0; z-index: 3;"><a href="index.php?page=<?=$page;?>&action=login">[LOGIN]</a></span><?php 
                	}   
           //--- top picture ---       
          if($row['content'] != ''){?>
          		<img style="positon:absolute;left:0;top:0;width:<?=$pic_width;?>;height:<?=$pic_height;?>;" src="<?=$row['content']; ?>"  >
          <?php
        	} ?>
                
          </div> 
          
          
        <?php
        }


//------------------------------- Type General ---------------------------------
        if ($row['type'] == "general"){
            $background = $row['background'];
            $block = "block".$row['block'];
            
            if ($_SESSION['loggedin'] == "true"){
                ?><div  id="<?=$block;?>"  style='padding:5px;position: absolute; left: <?=$xpos;?>px; top: <?=$ypos;?>px; height: <?=$height;?>px; width: <?=$width;?>px; background-color: <?=$background;?>; border: <?=$row['border'];?>;'>
                    <div style="position: absolute; left: 0; top: 30; cursor:move;" onmousedown="dragStart(event, '<?=$row['id'];?>', '<?=$block;?>')">
                        <img src="images/4arrows.gif">
                    </div>
                    <span style="position: absolute; right: 0px; top: 0px; font-weight: bold; background: #ffffff;"><?=$row['type']; ?></span>
                    <span class="edit_box"  style="<?php if($width<350){?>font-size:14px;<?php } ?>left: 2px; bottom: -10px; cursor:pointer;"><a href="index.php?action=edit_block&id=<?=$row['id'];?>&block_id=<?=$block;?>&page=<?=$page;?>" style="text-decoration: none;">[EDIT CONTENT]</a>
                    </span>
                     <!-- Edit Block Parameters button -->
                    <a class="newpost" style="<?php if($width<350){?>font-size:14px;<?php } ?>position: absolute; bottom: -20; right: 0;" href="<?=$_SERVER['PHP_SELF'];?>?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                        Edit Block Parameters
                    </a>
                    <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1);?>; document.move_page.ratio.value=0; resizeStart(event, '<?=$row['id'];?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>')" 
                        style="position: absolute; bottom: -8; right: -9; cursor:nw-resize;" >
                        <img src="images/right_corner.gif">
                    </div>
                    
                    <?php }
            
            else { ?>
                
                <div   id="<?=$block;?>" class="rounded" style='padding:5px;position: absolute; left: <?=$xpos;?>px; top: <?=$ypos;?>px; height: <?=$height; ?>px; width: <?=$width;?>px; background-color: <?=$background;?>; border: <?=$row['border'];?>' ><?php } ?><?=$row['content'];?></div>
        <?php }  
//------------------------------- Type youtube ---------------------------------
        if ($row['type'] == "youtube"){
            $background = $row['background'];
            $block = "block".$row['block'];
            $ratio = $width/$height;
            
            if ($_SESSION['loggedin'] == "true"){
                ?><div  id="<?=$block;?>"  style='position: absolute;left: <?=$xpos;?>px; top: <?=$ypos;?>px; height: <?=$height;?>px; width: <?=$width;?>px; background-color: <?=$background;?>; border: <?=$row['border']; ?>;'>
                    <div style="position: absolute; left: 0; top: 30; cursor:move;" onmousedown="dragStart(event, '<?=$row['id']; ?>', '<?=$block;?>')">
                        <img src="images/4arrows.gif">
                    </div>
                    <span style="position: absolute; right: 0px; top: 0px; font-weight: bold; background: #ffffff;"><?=$row['type'];?></span>
                    <span class="edit_box"  style="left: 2px; bottom: -10px; cursor:pointer;"><a href="index.php?action=edit_block&id=<?=$row['id'];?>&block_id=<?=$block;?>&page=<?=$page; ?>" 
                                  style="text-decoration: none;" >[EDIT CONTENT]</a>
                    </span>
                     <!-- Edit Block Parameters button -->
                    <a class="newpost" style="position: absolute; bottom: -20; right: 0;" href="<?=$_SERVER['PHP_SELF'];?>?action=edit_block&id=<?=$row['id']; ?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                        Edit Block Parameters
                    </a>
                    <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1); ?>; document.move_page.ratio.value=<?=$ratio;?>; resizeStart(event, '<?=$row['id']; ?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>')" 
                        style="position: absolute; bottom: -8; right: -9; cursor:nw-resize;" >
                        <img src="images/right_corner.gif">
                    </div>
                    
                    <?php }
            
            else {?>
                
                <div   id="<?=$block;?>" class="rounded" style='position: absolute; left: <?=$xpos;?>px; top: <?=$ypos;?>px; height: <?=$height;?>px; width: <?=$width;?>px; background-color: <?=$background;?>; border: <?=$row['border']; ?>' ><?php } ?><iframe width="<?=$width-(2*$row['border']);?>" height="<?=$height-(2*$row['border']);?>" src="//www.youtube.com/embed/<?=$row['content']; ?>" frameborder="0" allowfullscreen></iframe></div>
        <?php } 
//------------------------------- Type rss ---------------------------------
        if ($row['type'] == "rss"){
            ?>
            <script type="text/javascript">
                  jQuery(document).ready(function(){
                      jQuery('.rss_box a')
                        .click( function() {
                        window.open( jQuery(this).attr('href') );
                        return false;
                      });
                   });
                </script>
            <?php
            $background = $row['background'];
            $block = "block".$row['block'];
            if($force == 'y'){getFeed($row['content'],$row['style_type'],'y');}
            $force = 'n';
            ?>
            <div   id="<?=$block;?>"  style='position: absolute; left: <?=$xpos;?>px; top: <?=$ypos;?>px;<?php if($height != 0){echo "height:". $height."px;";} ?>   width: <?=$width;?>px; background-color: <?=$background;?>; border: <?=$row['border'];?>;'>
            <div style="position:absolute;right:10px;top:3px;">
            		<a href="?page=<?=$page;?>&force=y">Refresh</a>
            </div>
            <?php   
            if ($_SESSION['loggedin'] == "true"){?>
                   <div style="position: absolute; left: 0; top: 30; cursor:move;" onmousedown="dragStart(event, '<?=$row['id'];?>', '<?=$block;?>')">
                        <img src="images/4arrows.gif">
                    </div>
                    <span style="position: absolute; right: 0px; top: 0px; font-weight: bold; background: #ffffff;"><?=$row['type'];?></span>
                    <span class="edit_box"  style="left: 2px; bottom: -10px; cursor:pointer;"><a href="index.php?action=edit_block&id=<?=$row['id'];?>&block_id=<?=$block;?>&page=<?=$page;?>" 
                                  style="text-decoration: none;" >[EDIT CONTENT]</a>
                    </span>
                     <!-- Edit Block Parameters button -->
                    <a class="newpost" style="position: absolute; bottom: -20px; left: 140px;" href="<?=$_SERVER['PHP_SELF']; ?>?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                        Edit Block Parameters
                    </a>
                    <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1); ?>; document.move_page.ratio.value=0; resizeStart(event, '<?=$row['id'];?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>');" 
                        style="position: absolute; bottom: -8; right: -9; cursor:nw-resize;" >
                        <img src="images/right_corner.gif">
                    </div>
                    <?php
                    if(isset($row['content']) && $row['content'] != ''){
		                		?><div class="rss_box"><?php echo getFeed($row['content'],$row['style_type'],$force);?></div>
		                <?php
		                }
		                
                 }
            
            else {
            			if(isset($row['content']) && $row['content'] != ''){
                		?><div class="rss_box"><?php echo getFeed($row['content'],$row['style_type'],$force);?></div>
	                <?php
	                }
               }
                ?>
                </div>
        <?php } 
        
//------------------------------- Type blog ---------------------------------
        if (stristr ($row['type'],"blog")){
          if($row['style_type']!="index"){
            $background = $row['background'];
            //$side_background = hexLighter(substr($background,1,6),20);  
            $side_background = $background;
            $sidebar_width=190;
            
            $block = "block".$row['block'];
            if ($_SESSION['loggedin'] == "true"){
                ?>
                  <!-- Blog div with resize and move icons -->
                <div id="<?=$block;?>"  style='background-color: <?=$background;?>; border: <?=$row['border'];?>;'>
                   
                    <!-- New post button -->
                    <a class="newpost" style="position: absolute; top: 5;" href="blog_edit.php?type=<?=$row['type'];?>">
                        <img src="images/icon_new_post.gif"> New Post
                    </a>
                    <!-- Edit Block Parameters button -->
                    <a class="edit_params" style="position: absolute; top: 5; left: 140; font-size: .9em;" href="<?=$_SERVER['PHP_SELF'];?>?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                        Edit Block Parameters
                    </a>
                    <!-- Move block arrows -->
                    <div style="position: absolute; top: 5; left: 310;font-size: .9em;width: 180; cursor:move; "
                        onmousedown="dragStart(event, '<?=$row['id']; ?>', '<?=$block;?>')">
                        Move<img src="images/4arrows.gif" alt="Move this block">Block
                    </div>
                    
                    <br>
                    <br>
                    <!-- resize image/button -->
                    <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1); ?>; document.move_page.ratio.value=0; resizeStart(event, '<?=$row['id'];?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>')" 
                        style="position: absolute; bottom: 7px; right: 12px; cursor:nw-resize; z-index: +3;" >
                        <img src="images/right_corner.gif">
                    </div><?php }
            else {?>
                <!-- Blog div without resize and move icons -->
                <div   id="<?=$block;?>" style='background-color: <?=$background;?>; border: <?=$row['border'];?>' >
                    <?php } ?>
                    <div style="padding-left: 1em; padding-right: .5; width: <?=$width;?>;"><?php
                        
                        //edit_block_params($id, $block);
                        $type = $row['type'];
                        
                        if ($selected_year == ''){$selected_year=$max_year;}
                       
                        if (!$blog_id){$blog_id = '';}
                        
                        $sql = "SELECT * FROM $type where year(date) between '$selected_year' and '$selected_year' and id like '%$blog_id%' order by date DESC";
                        $stmt = $db->query($sql);
	    									$rows_blog = $stmt->fetchAll();
                        
                        foreach($rows_blog as $row_blog){
                        //-- Blog loop --    
                              ?>
                              <br>
                              <!-- Blog title -->
                              <h1 style="margin:5px 0 5px 5px;"><?=$row_blog['blog_title'];?></h1>
                              
                              <?php
                              //-- Edit post button (wrenches) ---
                              if ($_SESSION['loggedin'] == "true"){?>
                                  <a title="Edit Post" href="blog_edit.php?type=<?=$row['type'];?>&blogid=<?=$row_blog['id']; ?>&page=<?=$row['page'];?>">
                                      <img src="images/wrench.png">
                                  </a>
                              <?php } ?>
                             
                              <!-- Blog content -->
                              <?=$row_blog['content'];?>
                              
                              <!-- published date -->
                              <br><br><span style="font-size: .7em;">This entry was published on <?=date_display($row_blog['date']);?></span><br><br>
                              
                              <!-- Disqus code here -->
                              <div id="disqus_thread"></div>
															<script>
															
															/**
															*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
															*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
															/*
															var disqus_config = function () {
															this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
															this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
															};
															*/
															(function() { // DON'T EDIT BELOW THIS LINE
															var d = document, s = d.createElement('script');
															s.src = 'https://claytons-page.disqus.com/embed.js';
															s.setAttribute('data-timestamp', +new Date());
															(d.head || d.body).appendChild(s);
															})();
															</script>
															<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
															                            
                              
                        <?php } ?>
                        
                    </div>
                    
                </div>
        <?php } 
        //-------------- blog index block ------- 
        else{
        	
        		
            $background = $row['background'];
            $block = "block".$row['block'];
            
            if ($_SESSION['loggedin'] == "true"){
                ?><div  id="<?=$block;?>"  style='padding:5px;position: absolute; right:0;top:0; width: <?=$width;?>px; background: <?=$background;?>; border: <?=$row['border'];?>;'>
                   
                    
                    
                     <!-- Edit Block Parameters button -->
                    <a class="newpost" style="<?php if($width<350){?>font-size:14px;<?php } ?>position: absolute; bottom: -20; right: 0;" href="<?=$_SERVER['PHP_SELF'];?>?action=edit_block&id=<?=$row['id']; ?>&edit_params=yes&block_id=<?=$block;?>">
                        Edit Block Parameters
                    </a>
                   
                    
                    <?php }
            
            else { ?>
                
                <div   id="<?=$block;?>"  style='font-size:28px;padding:5px;position: absolute; left:0;top:0;width:600px; background-color: <?=$background;?>; border: <?=$row['border'];?>' >
                <?php } ?>
                <div align="center" style="font-weight:bold;font-size: 1.2em;margin:10px 0;"><?=$row['title'];?></div>
                <?php
                $table = $row['type'];
                
                //-- get the min and max years ---
                        $sql_date = "SELECT max(date) from `$table`";
                        $stmt_date = $db->query($sql_date);
	    									$rows_date = $stmt_date->fetchAll();
	    									$max_date = $rows_date[0][0];
	    									                        
                        $max_date = strtotime($max_date);
                        $max_year = date('Y', $max_date); 
                        
                        
                        
                        $sql_date = "SELECT min(date) from `$table`";
                        $stmt_date = $db->query($sql_date);
	    									$rows_date = $stmt_date->fetchAll();
	    									$min_date = $rows_date[0][0];
                        $min_date = strtotime($min_date);
                        $min_year = date('Y', $min_date); 
                        if($selected_year == ''){$max_year;}
                        
                        for ($current_year=$max_year; $current_year >= $min_year; $current_year--){ 
                            $sql_blog = "SELECT * FROM $table where year(date) between '$current_year' and '$current_year' order by date DESC";
                            $stmt_blog = $db->query($sql_blog);
	    											$rows_blog = $stmt_blog->fetchAll();
                            $sql = "SELECT * from $table where year(date) = '$current_year'";
                            $stmt = $db->query($sql);
                            $rows = $stmt->fetchAll();
                            if(!$rows){continue;}
                            $num_rows = count($rows_blog);
                            
                                $sql_this_year = "SELECT * FROM $table where year(date) between '$current_year' and '$current_year' order by date DESC";
                                $stmt_this_year = $db->query($sql_this_year);
	    													$rows_this_year = $stmt_this_year->fetchAll();
                                
                                
                                ?><div><?php
                                    foreach($rows_this_year as $row_this_year){
                                        ?>
                                        <div style="cursor: pointer;margin:5px;" onClick="window.location='<?=$_SERVER['PHP_SELF'];?>?page=<?=$page;?>&blog_id=<?=$row_this_year['id'];?>&selected_year=<?=$current_year;?>'"; onMouseOver="this.style.backgroundColor='white'"; onMouseOut="this.style.backgroundColor='<?=$background;?>'";>
                                            <span style="font-weight: bold;font-size: .8em;"><?=$row_this_year['date'];?></span><br>
                                            <div style="position: relative; left: 10; width: <?=$sidebar_width-20;?>;">
                                              <span style="font-size: .8em;"><?=$row_this_year['blog_title'];?></span>
                                            </div>
                                                
                                        </div>
                                        <?php
                                    }
                                ?>
                                </div><?php
                            
                            
                          
                        } ?>
                
                <!-- RSS Button -->
                <div style="text-align: center;margin:10px 0;">
                    <?php $this_dir = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] ;
                        $this_dir = str_replace('index.php','',$this_dir);?>
                    
                    
                    <!--  <a href="<?=$this_dir.'my_feed_'.$row['type'].'.xml';?>"><img src="images/rss.png" border="0" alt="RSS Feed"></a> -->
                </div>
                </div>
        <?php }
        
        
       
        }
//------------------------------- Type picture ---------------------------------
        if ($row['type'] == "picture"){
            $array = getimagesize($row['content']);
            $width1 = $array[0];
            $height1 = $array[1];
            $ratio = $width1/$height1;
            $border_add = 2 * substr($row['border'],0,1);
            $block = "block".$row['block'];
            
            if ($_SESSION['loggedin'] == "true"){?>
                <div id="<?=$block;?>"   style='position: absolute; left: <?=$xpos;?>; top: <?=$ypos;?>; height: <?=$height;?>; width: <?=$width;?>; background-color: <?=$background;?>; border: <?=$row['border'];?>;' >
                  <div style="position: absolute; left: 0; top: 30; cursor:move;" onmousedown="dragStart(event, '<?=$row['id'];?>', '<?=$block;?>')">
                      <img src="images/4arrows.gif">
                  </div>
                  <span style="position: absolute; right: 0; top: 0; font-weight: bold; background: white;"><?=$row['type'];?></span>
                  <span class="edit_box"  style="left: 2; bottom: -10; cursor:pointer;">
                      <a href="index.php?action=edit_block&id=<?=$row['id']; ?>&block_id=<?=$block;?>&page=<?=$page;?>" 
                          style="text-decoration: none;" >[EDIT CONTENT]</a>
                  </span>
                  <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1);?>; document.move_page.ratio.value=<?=$ratio;?>; resizeStart(event, '<?=$row['id'];?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>')" 
                      style="position: absolute; bottom: -8; right: -9; cursor:nw-resize;" >
                        <img src="images/right_corner.gif">
                  </div>
                  
                    <?php }
            else {?>
                <div   id="<?=$block;?>" style='position: absolute; left: <?=$xpos;?>; top: <?=$ypos;?>; height: <?=$height;?>; width: <?=$width;?>; background-color: <?=$background;?>; border:<?=$row['border'];?>' ><?php } ?>
             
             <img src="<?=$row['content'];?>" width="<?=$width-$border_add;?>" height="<?=$height - $border_add;?>"><?php
             
             
             
              ?></div>
        <?php }  
        
//------------------------------ Type Link -------------------------------------
        if ($row['type'] == "link" && $row['parameter1'] != 'site_nav'){
            $current_links = $row['content'];
            $links_array = unserialize($current_links);
            $total_links = count($links_array['link']);
            $current_type = $row['style_type'];
            $background = $row['background'];
            $block = "block".$row['block'];
            
            if($row['style_type']=="leftside3"){$height=20;}
            ?><div id="<?=$block;?>" 
                class="<?=$current_type;?>"  
                style="position: absolute; left: <?=$xpos;?>px; top:<?=$ypos;?>px;<?php if($height != 0){?> height: <?=$height;?>px;<?php } ?> width: <?=$width;?>px; border:<?=$row['border'];?>;background:<?=$row['background'];?>; " >
                <div align="center" width="<?=$width;?>"<span style="font-weight: bold; font-size: 1.2em; "><?=$row['title'];?></span></div>
                <?php
                if ($_SESSION['loggedin'] == "true" && $row['style_type']=='leftside3'){ ?>
                    <div style="position: absolute;top:5px; right:-120px; width: 110px;background: #ccc;">
                        <a style="margin-top: 5;" href="index.php?action=enter_links&block_id=<?=$row['id'];?>&page=<?=$page;?>">Add a link</a>
                        <a style="background: orange;"  href="?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                            Edit Block
                        </a>
                        &nbsp;<span style="cursor:move;" onmousedown="dragStart(event, '<?=$row['id']; ?>', '<?=$block;?>')" ><img style="vertical-align: top;" width="20" src="images/4arrows.gif"></span>
                  </div>
                  <?php }
                  //print_r($links_array);
                  if (isset($links_array['block_title'][0])){
                      ?>
                      <div id="link_title" style="border-bottom: <?=$row['border'];?>">
                          <?=$links_array['block_title'][0];?>
                      </div>
                      <?php
                  }
                 
                  if ($_SESSION['loggedin'] == "true"){?><table width="100%" style="border-collapse: collapse;border-spacing: 0;" cellpadding="0" cellspacing="0"><?php }
                  
                  
                   ?><ul><?php
                   //-- start loop
                   for($x=0; $x<$total_links; $x++) {
                        if ($_SESSION['loggedin'] == "true"){
                            //-- logged in horizontal view of links --
                            if ($row['style_type']=='leftside3'){?><li><a href="index.php?action=edit_links&id=<?=$x;?>&page=<?=$_SESSION['page'];?>&block_id=<?=$row['id'];?>"><?=$links_array['words'][$x];?></a></li><?php }
                            //-- logged in vertical view if links --
                            else {                            
                                ?>
                                
                                  <tr>
                                  <td align="center" style="vertical-align: middle; cell-padding: 0;">
                                    <img style="cursor: pointer;" src="images/up_arrow.gif"  onmouseover="this.src='images/up_arrow_red.gif';" onmouseout="this.src='images/up_arrow.gif';" onclick="window.location='mypage_finish.php?action=move_link_up&&id=<?=$x;?>&page=<?=$_SESSION['page'];?>&block_id=<?=$row['id'];?>';">
                                  </td>
                                  <td align="center" style="cell-padding: 0;" > 
                                      <a href="index.php?action=edit_links&id=<?=$x;?>&page=<?=$_SESSION['page'];?>&block_id=<?=$row['id'];?>">
                                           <?=$links_array['words'][$x];?>
                                      </a>
                                  </td>
                                  <td align="center" style="vertical-align: middle; cell-padding: 0;">      
                                    <img style="cursor: pointer;" src="images/dn_arrow.gif"  onmouseover="this.src='images/dn_arrow.red.gif';" onmouseout="this.src='images/dn_arrow.gif';" onclick="window.location='mypage_finish.php?action=move_link_down&&id=<?=$x;?>&page=<?=$_SESSION['page'];?>&block_id=<?=$row['id'];?>';">
                                  </td>
                                  </tr> 
                              <?php
                          }     
                              
                           
                             
                            }
                            //-- not looged in view of links --
                            else {?><li><a  href="<?=$links_array['link'][$x];?>" <?php if($links_array['new_tab'][$x] == 'on'){?>target='_blank' <?php } ?>><?=$links_array['words'][$x];?></a></li><?php }
                    //-- end loop
                    }
                    if ($_SESSION['loggedin'] == "true" && $row['style_type']!="leftside3" ){?>
                        <tr>
                            <td></td><td align="center"><li><a href="index.php?action=enter_links&block_id=<?=$row['id'];?>&page=<?=$page;?>">Add a link</a></li></td><td></td>
                        </tr>
                        <tr>
                            <td></td><td align="center"><li><a style="background: orange;"  href="?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">Edit Block</a></li></td><td></td>
                        </tr>
                        <tr>
                            <td></td><td align="center"><li style="cursor:move;" onmousedown="dragStart(event, '<?=$row['id']; ?>', '<?=$block;?>')" ><img style="vertical-align: top;" width="20" src="images/4arrows.gif"></li></td><td></td>
                        </tr>
                        <?php
                        }
                        
                        
                    ?></ul><?php
                    
                   
                    
                    if ($_SESSION['loggedin'] == "true"){
                        ?>
                        </table>
                        
                         <!-- bottom right corner -->
                        <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1); ?>; resizeStart(event, '<?=$row['id'];?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>')" style="position: absolute; bottom: -8; right: -9; cursor: nw-resize;" ><img src="images/right_corner.gif"></div>
                        
                        <?php }
              ?>  
                
            </div><?php
            
          }
//------------------------------ Type site_nav Link -------------------------------------
        if ($row['type'] == "link" && $row['parameter1'] == 'site_nav'){
            $current_links = $row['content'];
            $links_array = unserialize($current_links);
            $total_links = count($links_array['link']);
            $current_type = $row['style_type'];
            $background = $row['background'];
            $block = "block".$row['block'];
               
           ?>
           <div id="<?=$block;?>" class="site_nav" style="position:absolute;left:0;top:120px;">
		           <ul><?php
		           //-- start loop
		           for($x=0; $x<$total_links; $x++) {
		                ?>
		                <li>
		                	
			                	<a  href="<?=$links_array['link'][$x];?>">
			                		<?=$links_array['words'][$x];?>
			                	</a>
		                	
		                </li>
		                
		      			<?php
		            }
		            
		                
		            ?></ul>
		            
                
            </div>
            <?php
            
          }
//------------------------------ Type download -------------------------------------
        if ($row['type'] == "download"){
            $current_links = $row['content'];
            $links_array = unserialize($current_links);
            $total_links = count($links_array['title']);
            $current_type = $row['style_type'];
            $background = $row['background'];
            $block = "block".$row['block'];
            
            ?><div id="<?=$block;?>" 
                class="<?=$current_type;?>"  
                style="position: absolute; left: <?=$xpos;?>;top:<?=$ypos;?>;height:<?=$height;?>;width:<?=$width;?>;border:<?=$row['border'];?>; background: <?=$row['background'];?>;" >
                <div>
                   <h1 style="text-align:center;font-weight: bold; font-size: 22px;padding:3px 0 0 0;margin:0; "><?=$row['title'];?></h1>
                  
                   <?php
                      
                   
               
                   for($x=0; $x<$total_links; $x++) {
                        if ($_SESSION['loggedin'] == "true"){?>
                        <div style="margin:10px;">
                            <span style="text-decoration: underline; font-weight: bold; text-align: center;"><?=$links_array['title'][$x];?></span><br>
                            <?=$links_array['desc'][$x];?><br>
                            <a  href="index.php?action=edit_download&id=<?=$x;?>&page=<?=$page;?>&block_id=<?=$row['id'];?>">[EDIT]</a><br><br>
                        </div>    
                        <?php }
                        else { ?>
                        <div style="margin:10px;">
                            <span style="text-decoration: underline; font-weight: bold; text-align: center;"><?=$links_array['title'][$x];?></span><br>
                            <?=$links_array['desc'][$x];?><br>
                            <a  href="<?=$links_array['link'][$x];?>">Download</a>
                        </div>
                        <?php }
                    }
                    
          
                    if ($_SESSION['loggedin'] == "true"){
                        ?>
                        
                        <!-- Move -->
                        <div style="cursor:move; position: absolute; left: 2px; top: 2px;" onmousedown="dragStart(event, '<?=$row['id'];?>', '<?=$block;?>')" ><img src="images/4arrows.gif"></div>
                        
                        <!-- Edit Block Parameters button -->
                        <a class="newpost" style="position: absolute; bottom: -15px; left: 50px;font-size:14px;" href="<?=$_SERVER['PHP_SELF']; ?>?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                            Edit Block Parameters
                        </a>
                        <!-- Resize -->
                        <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1);?>; resizeStart(event, '<?=$row['id'];?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>')" style="position: absolute; bottom: -8; right: -9; cursor: nw-resize;" ><img src="images/right_corner.gif"></div>
                        <?php }
                
            
            ?>
                    
                </div>
            </div><?php
            
          }

//-------------------------------- Type video --------------------------------------------
if ($row['type'] == "video"){
            $background = $row['background'];
            $block = "block".$row['block'];
            
            if ($_SESSION['loggedin'] == "true"){
                ?><div id="<?=$block;?>" style='position: absolute; left: <?=$xpos;?>; top:<?=$ypos;?>;height:<?=$height;?>;width:<?=$width;?>; background-color:<?=$background;?>; border:<?=$row['border'];?>;'>
                    <!-- Move -->
                    <div style="position: absolute; left: 10px; bottom: 10px; cursor:move;" onmousedown="dragStart(event, '<?=$row['id']; ?>', '<?=$block;?>')">
                        <img src="images/4arrows.gif">
                    </div>
                    <!-- Upload button -->
                    <span style="position: absolute; top: 10px; left: 250px;">
                      <a  class="green_button" href="index.php?page=<?=$page;?>&action=upload_video">Upload Video</a>
                    </span>
                    <!-- video width and playlist selection -->
                    <span style="position: absolute; top: 10px; left: 380px;">
                      <form method="post" action="mypage_finish.php?action=video_width_enter&id=<?=$row['id'];?>&page=<?=$page;?>" style="margin:0;">
                      	width <input type="text" style="width:60px;" name="video_width" value="<?=$row['style_type'];?>">
                      	playlist <input type="text" style="width:40px;" name="playlist" value="<?=$row['parameter1'];?>">
                      	<input type="submit" value="E" style="cursor:pointer;" title="Enter">
                      </form>
                    </span>
                    <!-- Edit video data -->
                    <span style="position: absolute; right: 2px; top: 10px;" onmouseover="this.style.background='white';" onmouseout="this.style.background='lightskyblue';" >
                              <a class="yellow_button" href="index.php?page=<?=$_SESSION['page']; ?>&action=edit_video_data&playlist=<?=$row['parameter1'];?>">Edit Video Data</a>
                    </span>
                    <!-- Edit Block Paramters -->
                    <a class="newpost" style="<?php if($width<350){?>font-size:14px;<?php } ?>position: absolute; bottom: -20; right: 0;" href="<?=$_SERVER['PHP_SELF'];?>?action=edit_block&id=<?=$row['id'];?>&edit_params=yes&block_id=<?=$block;?>&page=<?=$page;?>">
                        Edit Block Parameters
                    </a>
                    <!-- Resize -->
                    <div onmousedown="document.move_page.border.value=<?=substr($row['border'],0,1); ?>; document.move_page.ratio.value=0; resizeStart(event, '<?=$row['id']; ?>', '<?=$block;?>','<?=$width;?>','<?=$height;?>')" 
                        style="position: absolute; bottom: -8; right: -9; cursor:nw-resize;" >
                        <img src="images/right_corner.gif">
                    </div>
                    
                    <?php }
            
            else { ?>
                <div   id="<?=$block;?>" style='position: absolute; left: <?=$xpos;?>; top: <?=$ypos;?>; height: <?=$height;?>; width: <?=$width;?>; background-color: <?=$background;?>; border:<?=$row['border'];?>'><?php } ?>
             
                
    
             
               <?php if ($action != "upload_video" && $action != "convert_video" && $action != "ftp_video" && $action != "edit_video_data"){?>
                    <!---- Video title, if no title show the latest title from database-->
                    
                    <!-- list all titles to choose from -->
                    <div style="position: absolute; left: 10; top: 0;">
                        <span style="font-size: 1.3em; font-weight: 800; text-align: left;">
                            <u>Choose a title to play</u>
                        </span>
                        
                        <br><br>
                        <?php
                        $playlist = $row['parameter1'];
                        $db = connect_pdo();
                        $sql_video = "SELECT * FROM videos where playlist = '$playlist'   order by date DESC ";
                    		$stmt = $db->query($sql_video);
	    									$rows_video = $stmt->fetchAll();
                    		foreach ($rows_video as $row_video) {?> 		
                            <a style="color: black;" href="?page=<?=$page;?>&video=<?=$row_video['filename']."&title=".$row_video['title'];?>&selected_playlist=<?=$playlist;?>"><?=$row_video['title'];?></a>
                            <br>
                        <?php }
                        ?>
                    </div>
                    <!-- store the page in table temp because the session page gets lost after flash player -->
                    <?php
                    $the_id = '1';
                    $page = $_SESSION['page'];
                    $sql_page = "Update temp SET page = :page where id =:id";
                    $stmt = $db->prepare($sql_page); 
										$result = $stmt->execute( 
										    array( 
										        ':page'   => $page, 
										        ':id'    => $the_id
										    ) 
										);
                    
                    
                    ?>
                    <!-- show the video in flowplayer -->
                
                    <div style="position: absolute; right: 10; top: 40; border: 4px solid white; width: 480px;display:none;">
                       <script type="text/javascript" src="html/swfobject.js"></script><div id="fp1" class="flowplayer">Basic demo</div>
                      <script type="text/javascript" defer="defer">
                      		var fo = new SWFObject("FlowPlayerLP.swf", "FlowPlayer", "480", "320", "9", "#000000", true);
                      		fo.addParam("AllowScriptAccess", "always");
                      		fo.addParam("allowFullScreen", "true");
                      		fo.addVariable("config", "{  playList: [ { overlayId: 'play' },{ url: 'http://claytonfelt.net/mypage/videos/<?=$video;?>' } ], loop: false, initialScale: 'scale',controlsOverVideo: 'locked', autoBuffering: true, useNativeFullScreen: true, autoPlay: false }");
                          fo.write("fp1");
                      	</script>
                        
                                  	
                    </div>
                    <?php
                    $video_width = $row['style_type'];
                    if($video_width == ''){$video_width = 480;}
                    if ($_SESSION['loggedin'] != "true"){?>
                    	<div style="position:absolute;top:5px;right:10px;text-align:center;width:<?=$video_width;?>;">
                    		<h1 style="margin:0;"><?=$row_video['playlist_title'];?></h1>
                    	</div>
                  	<?php } ?>
                    <video width="<?=$video_width;?>" height="<?=$video_width*.567;?>" controls autoplay style="position:absolute;top:40px;right:10px;border:4px solid white;">
										  <?php 
										  if($selected_playlist == $playlist){?>
										  	<source src="http://claytonfelt.net/mypage/videos/<?=$video;?>" type="video/mp4">
										  <?php 
											} ?>
										  <source src="movie.ogg" type="video/ogg">
										Your browser does not support the video tag.
										</video>
                <?php } ?>
                <!-- get the page back from the table temp -->
                <?php
                $db = connect_pdo();
                $sql_page = "SELECT page from temp where id='1'";
                $stmt = $db->query($sql_page);
	    					$rows_page = $stmt->fetchAll();
	    					$row_page = $rows_page[0];
                $_SESSION['page']=$row_page['page'];
                $page = $_SESSION['page'];
                
                ?> 
                
            
            
        </div>
   
  <?php }          

   
        
      
     }
     
     
    //------------------------------- Type picture_edit_box ---------------------------------
      if ($_SESSION['picture_edit']=="on"){include("manage_photos.php"); }  
  //------------------------------------------------------------------------------- 
    
    switch ($action){
      case ("site_config");  site_config();  break;
      case ("enter_links");  enter_links($block_id);  break;
      case ("edit_page"); edit_page($page); break;
      case ("edit_links");  edit_links($id,$block_id);  break;
      case ("enter_download");  enter_download($block_id);  break;
      case ("edit_download");  edit_download($id,$block_id);  break;
      
      case ("edit_block"); edit_block($id, $block_id, $blogid, $blog_action, $edit_params); break;
      
      case ("manage_photos_enable"); $_SESSION['picture_edit']="on"; header("Location: index.php?page=$page"); break;
      case ("manage_photos_disable"); $_SESSION['picture_edit']="off"; header("Location: index.php?page=$page"); break;
      
      case ("upload_video"); upload_video($row['title']); break;
      case("convert_video");  convert_video(); break;
			case("ftp_video");  ftp_video($source_file, $destination_file, $title, $videoname); break;
      case("edit_video_data"); edit_video_data($video_id,$playlist); break;
      
      case ("login"); login($status,$password); break;
      case ("logout"); logout(); break;
      case ("login_page"); login_page($status,$id,$password,$page); break;
      }
    ?>
              
              <div id="move_info" style="position:absolute;left:200px;top:200px;display:none;z-index:100;border:1px solid black;background:#aaaaaa;padding:5px;">
                </div>  
              <div style="position: absolute; left: 100; top: 200; z-index: -1;display:none;" >
               <form name="move_page"  method="post" action="mypage_finish.php?action=move_block"> 
                   <input type="hidden" name="page" value="<?=$page;?>">
                   <input type="hidden" name="xpos" value="">
                   <input type="hidden" name="ypos" value=""> 
                   <input type="hidden" name="width" value="">
                   <input type="hidden" name="height" value="">
                   <input type="hidden" name="ratio" value=""> 
                   <input type="hidden" name="border" value=""> 
                   <input type="hidden" name="video_on_page" value="">      
                  <input type="hidden" name="id" value="">
                  
                </form>
                
                <form  name="finish_edit_block"  method="post" action="mypage_finish.php?action=finish_edit_general">
                  <input type="hidden" name="content" value="">
                  <input type="hidden" name="background" value=""> 
                  <input type="hidden" name="border" value=""> 
                  <input type="hidden" name="block_type" value="">    
                  <input type="hidden" name="id" value="">
                  
                </form>
                
                <form  name="finish_add_old_photo"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="existing_file" >
                                  <input type="hidden" name="action" value="finish_edit_general" >
                                  <input type="hidden" name="id" >
                </form>
                
                <form  name="finish_edit_mainbox"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="border" >
                                  <input type="hidden" name="background" >
                                  <input type="hidden" name="action" value="finish_mainbox" >
                                  <input type="hidden" name="id" >
                                  <input type="submit" style="width:0px;height:0px" value=""> 


                </form>
                
                <form  name="finish_edit_page"  method="post"  action="mypage_finish.php">
                                 
                                  <input type="hidden" name="page_title" >
                                  <input type="hidden" name="background" >
                                  <input type="hidden" name="password" >
                                  <input type="hidden" name="action" value="finish_page" >
                                  <input type="hidden" name="id" >
                </form>
                
                <form  name="finish_edit_top_properties"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="border" >
                                  <input type="hidden" name="picture_inc" >
                                  <input type="hidden" name="background" >
                                  <input type="hidden" name="action" value="finish_top_properties" >
                                  <input type="hidden" name="id" >
                </form> 
                
                <form  name="finish_edit_blog"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="content" >
                                  <input type="hidden" name="background" value=""> 
                                  <input type="hidden" name="border" value="">
                                  <input type="hidden" name="date" >
                                  <input type="hidden" name="blog_id" >
                                  <input type="hidden" name="id" >
                                  <input type="hidden" name="type" >
                                  <input type="hidden" name="action" value="finish_blog_edit" >
                </form> 
                
                <form  name="finish_enter_blog"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="content" >
                                  <input type="hidden" name="type" >
                                  <input type="hidden" name="action" value="finish_enter_blog" >
                </form> 
                
                <form  name="finish_edit_block_parameters"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="block_type" >
                                  <input type="hidden" name="background" value=""> 
                                  <input type="hidden" name="border" value="">
                                  <input type="hidden" name="blog_id" >
                                  <input type="hidden" name="id" >
                                  <input type="hidden" name="action" value="finish_edit_block_parameters" >
                </form>
                
                <form  name="finish_add_block"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="block_type" >
                                  <input type="hidden" name="action" value="add_block" >
                </form>
                <form  name="finish_delete_block"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="id">
                                  <input type="hidden" name="question">
                                  <input type="hidden" name="action" value="delete_block" >
                </form>
                <form  name="finish_download_parameters"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="block_type" value="" >
                                  <input type="hidden" name="background" value=""> 
                                  <input type="hidden" name="border" value="">
                                  <input type="hidden" name="block_title" value="">
                                  <input type="hidden" name="id" >
                                  <input type="hidden" name="action" value="finish_download_parameters" >
                </form>
                <form  name="finish_site_config"  method="post"  action="mypage_finish.php">
                                  <input type="hidden" name="password" value="" >
                                  <input type="hidden" name="action" value="finish_site_config" >
                </form>
                
                
              </div> 
                    
    </div>
    
    </div>
    
    </td>




    </table>
      



   </body>
   </html>
