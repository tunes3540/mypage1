<?php  ob_start(); session_start(); 
		$folder = '';
		$gallery_id = '';
		$nowdate = '';
		$no_whizzywig = '';
		$blogid = '';
		$force_update = '';
		foreach ($_REQUEST as $param_name => $param_val) {
		if($param_name == "PHPSESSID"){continue;}
    $$param_name = $param_val;
		} 
    $page = $_SESSION['page'];
    
    require_once '../vendor/autoload.php';

    // Optional, but definitely nice to have, options
    $options = [
        'AppName' => 'mypage1/1.0 (http://claytonfelt.net)',
    ];
    $client = new phpSmug\Client('RkKSbdrQWjFRgbjvhgpssjgg44cq9Ngz', $options);
//test1
    //$repositories = $client->get('folder/user/claytonfelt!folderlist');
    /*
    print '<pre>';
    print_r($repositories);
    print '</pre>';
    */
    /*
    $repositories = $client->get('folder/user/claytonfelt/Family-Photos-From-Dad-11!albums');
    print '<pre>';
    print_r($repositories);
    print '</pre>';

    exit;
    */
		?>
    
    <link href="default.css" rel="stylesheet" type="text/css" />
    <!--<link href="mooRainbow.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="farbtastic.css" type="text/css" />
    -->
    <link rel="stylesheet" type="text/css" href="spectrum.css">
    <script src="js/jquery-2.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="clayton_java_move.js"></script>
    <!--<script type="text/javascript" src="html/swfobject.js"></script>
    -->
    <script type="text/javascript" src="spectrum.js"></script>
    <?php
    if($no_whizzywig == ''){?>
    		<script type="text/javascript" src="whizzywig.js"></script>
    <?php
  	}
  	?>
    <script type="text/javascript">cssFile = "wizzywig.css";buttonPath = "btn/";imageBrowse = "whizzypic.php";</script> 
   
    <!--<script src="js/jquery-ui-1.10.4.custom.min.js" type="text/javascript" charset="utf-8"></script>
    <link href="js/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
      .ui-datepicker-div, .ui-datepicker-inline, #ui-datepicker-div {font-size:0.8em}
      .ui-widget {font-size:12px;border-radius:0;}
    </style>
  
	
	<script type="text/javascript" src="js/farbtastic.js"></script>
	-->
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
      jQuery(document).ready(function() {
          
         
      
          jQuery('#smugmug_list').delegate('a', 'click', function(event) {
                thisShownImg = jQuery(this).attr('alt');
                thisThumbSmall = jQuery(this).children('img').attr('src');
                thisSmugLink = jQuery(this).attr('href');
                //var testSelector = "$('#whizzycontent').contents().find('ul a[href=afile.php]')";
                
                
                //var testAlready = jQuery(testSelector);
                //console.log(testSelector);
                
                //if(testAlready.length == 0){
                  insHTML('<a style="display:block;padding-bottom:10px;" href="'+thisSmugLink+'" alt="Smugmug link" target="_blank"><img width="1000" class="sel_image" src="'+thisShownImg+'"></a><br><br>');
                  //$("#content",$("#whizzycontent").contents()).append('<li style="display:inline;float:left;margin-right:3px;position:relative;"><a href="'+thisHref+'" alt="'+thisThumbLarge+'"><span title="Remove from list of selected images" class="remove_select" ></span><span title="Show a large preview of this image" class="magglass"></span><span title="Use this as the featured thumbnail for this post" class="thumbnail_select"></span><img width="100" class="sel_image" src="'+thisThumbSmall+'"></a></li>');
                  //jQuery('#content').append('<li style="display:inline;float:left;margin-right:3px;position:relative;"><a href="'+thisHref+'" alt="'+thisThumbLarge+'"><span title="Remove from list of selected images" class="remove_select" ></span><span title="Show a large preview of this image" class="magglass"></span><span title="Use this as the featured thumbnail for this post" class="thumbnail_select"></span><img width="100" class="sel_image" src="'+thisThumbSmall+'"></a></li>');
                //}
                //else {
                  //jQuery(testSelector).children('.magglass').fadeIn();
                  //jQuery(testSelector).children('.magglass').fadeOut();
                 // }
                event.preventDefault();
            });
          /*
          $(".datepicker").datepicker({ 
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
    			changeYear: true,
          yearRange: "-20:+20" });
         */
        });
    </script>
<?php
include_once("functions_connect.php");
//require_once( "phpsmug/phpSmug.php" );
										
                    
      
                    $db = connect_pdo("mypage1");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								    $sql_blog = "SELECT * FROM $type where id = '$blogid' ";
								    $stmt = $db->query($sql_blog);
								    $rows = $stmt->fetchAll();
                    
                    
                    $row_blog = $rows[0];
                    ?>
                        <div style="background: white; padding: 0; padding-top: 5; margin-bottom: 0;position: fixed; left: 0; top: 0px; width: 1050px;border: 1px solid black;">
                        
                        <form  class="inner" name="edit_block_blog"  method="post" action="mypage_finish.php">
                            <div style="margin-bottom:5px;">
                                <!-- blog date if editing a post -->
                                <?php if ($blogid != ''){?>Blog Date: <input style="width:100px;" class="datepicker" type="text" name="date" value="<?=$row_blog['date'] ?>"><?php } ?>
                                <!-- Blog title -->
                                Title: <input type="text" size="40" name="blog_title" value="<?=$row_blog['blog_title'];?>">
                                &nbsp;&nbsp;<a class="button" href="?type=<?=$type;?>&blogid=<?=$blogid;?>&page=<?=$page;?>&no_whizzywig=no">No Whizzywig</a>
                                &nbsp;&nbsp;<a class="button" href="?type=<?=$type;?>&blogid=<?=$blogid;?>&page=<?=$page;?>&no_whizzywig=">Whizzywig</a>
                            </div>
                            
                            <!-- Blog content -->
                            <textarea id="content" style="width:100%;height: 700px;" name="content"><?=$row_blog['content'];?></textarea>
                            <input type="hidden" name="blog_id" value="<?=$blogid;?>">
                            <input type="hidden" name="type" value="<?=$type;?>">
                            <input type="hidden" name="action" value="finish_blog_edit">
                            <script type="text/javascript">imageBrowse = "whizzypic_mine.php";</script> 
                            <script type="text/javascript">
                                makeWhizzyWig("content", "fontname fontsize  bold italic underline  newline left center right  number bullet indent outdent  color hilite rule  clean html image link ", gentleClean="true");
                            </script>
                            <!-- Red X in upper right corner -->
                            <?php if ($row_blog['date']){
                                    $temp_date = strtotime($row_blog['date']);
                                    $selected_year = date('Y', $temp_date);
                                    }
                                else {
                                    $temp_date = strtotime($nowdate);
                                    $selected_year = date('Y', $temp_date);
                                  }
                            ?>
                            <span style="position: absolute; top: 0; right: 0;"><a href="index.php?page=<?=$page;?>&selected_year=<?=$selected_year;?>"><img src="images/redx_xp.gif"></a></span>
                            <!-- Delete post options -->
                            <div style="margin:5px 0 0 5px;float:left;">
                                <select name="question" style="font-size: 1.2em;position:relative; bottom: 4;">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                                <input type="submit" value="Delete Post" 
                                  onMouseOver="this.style.background='white';" onMouseOut="this.style.background='red';"
                                  style="cursor: pointer; margin: 5; background: red; padding-left: 1em; padding-right: 1em; border: 1px solid black; font-size: 1.2em; font-weight: bold;">
                            </div>
                            <!-- Publish button -->
                            <div style="margin:5px 5px 0 0;float:right;">
                              <input type="submit" value="Publish Post" style="cursor:pointer;background: #70d175; padding-left: 1em; padding-right: 1em; border: 1px solid black; font-size: 1.2em; font-weight: bold;">
                            </div>
                            <div style="clear:both;margin:0;"></div>
                        </form>
                        </div>
                        <?php
                        //-- smugmug show images --
                        ?>
                        <div style="background: white;position:absolute;left:1050px;top:0;border:1px solid black;padding:5px;margin-left:2px;">
                          <?php
                        
                          	
                          	$sql = "SELECT * from smugmug where id = 1";
                          	$stmt = $db->query($sql);
								    				$rows = $stmt->fetchAll();
								    				$row = $rows[0];
                            $last_update = $row['gallery_id'];
                            $today = date("Y-m-d");
                            $smugmug_username = $row['gallery_key'];
                            $nickname="NickName=".$smugmug_username;
                            
                            //-- update the listing in the database
                            if ($today != $last_update or $force_update == 'y'){
                                
                              
                        
                                $repositories = $client->get('folder/user/claytonfelt!folderlist');
                            
                                //$f = new phpSmug( "APIKey=hLIFIsmrKd7lITN7j22SNggj4ITyFl1s", "AppName=SmugMug Embed" );
                              	//$f->login();	
                              	//$albums = $f->albums_get( $nickname );	
                              	?><pre><?php //print_r($albums);?></pre>
                              	<?php

                                //-- change the date to today
                                $sql = "DELETE from smugmug";
                                $stmt = $db->prepare($sql);
      													$stmt->execute();
                                $sql = "INSERT into smugmug SET gallery_id = '$today', id = '1'";
                                $first_id = 1;
                                $sql = "INSERT INTO smugmug (gallery_id,id,gallery_key) VALUES (:gallery_id,:id,:gallery_key)";
															  $stmt = $db->prepare($sql) ;
																$stmt->execute(array(
																				'gallery_id' => $today,
																        'id' => $first_id,
																        'gallery_key' => $smugmug_username
																		));
                                
                                //-- enter smugmug gallerys into table smugmug
                                //foreach($albums as $album){
                                  foreach($repositories->FolderList as $repo){
                                  
                                  //foreach($repositories->Album as $repo){
                                    
                                
                                  //$gallery_id = $album['id'];
                                  //$gallery_key = $album['Key'];
                                  //$folder = $album['Category']['Name'];
                                  //$title = $album['Title'];
                                  /*
                                  $sql = "INSERT INTO smugmug (gallery_id,gallery_key,folder,title) VALUES (:gallery_id, :gallery_key, :folder, :title)";
																  $stmt = $db->prepare($sql) ;
																	$stmt->execute(array(
																					'gallery_id' => $repo->AlbumKey,
																	        'gallery_key' => $repo->AlbumKey,
																	        'folder' => $repo->Uris->AlbumImages->Uri,
																	        'title' => $repo->NiceName
																			));
                                  */
                                  if(substr_count($repo->Uri, '/') < 7){
                                    $sql = "INSERT INTO smugmug (folder,title) VALUES (:folder, :title)";
                                    $stmt = $db->prepare($sql) ;
                                    $stmt->execute(array(
                                            'folder' => $repo->Uri,
                                            'title' => $repo->Name
                                        ));
                                    }
                              	}
                              	$folder = ''; $gallery_id = '';
                            }
                            //-- End update database table
                          	?>
                          	<form method="post" action="mypage_finish.php?action=smugmug_username">
                          		Smugmug Username<br>
                          		<input type="text" name="smugmug_username" value="<?=$smugmug_username;?>">
                          		<input type="submit" value="Enter">
                          		<input type="hidden" name="type" value="<?=$type;?>">
                          		<input type="hidden" name="blogid" value="<?=$blogid;?>">
                          		<input type="hidden" name="page" value="<?=$page;?>">
                          		
                          	</form>
                          	
                          	<a href="blog_edit.php?blogid=<?=$blogid;?>&type=<?=$type;?>">Back to top</a>&nbsp;&nbsp;
                          	<a href="?type=<?=$type;?>&blogid=<?=$blogid;?>&page=<?=$page;?>&force_update=y">Force Update</a>
                          	<?php
                            //-- list folders
                          
                            if($folder == '' && $gallery_id == '') {
                                ?><h2>Folders</h2><?php
                                $sql = "SELECT * from smugmug order by title";
                                $stmt = $db->query($sql);
								    						$rows = $stmt->fetchAll();
								    						foreach($rows as $row){
                                    ?>
                                    <a href="?blogid=<?=$blogid;?>&type=<?=$type;?>&folder=<?=$row['folder'];?>&page=<?=$page;?>&title=<?=$row['title'];?>"><?=$row['title'];?></a><br>
                                    <?php
                                }
                            }
                           
                            //-- list gallerys/folders
                            if($folder != ''){
                                
                                
                                ?><h1><?=$title;?></h1><?php
                                /*
                                $sql = "SELECT * from smugmug  order by title";
                                $stmt = $db->query($sql);
								    						$rows = $stmt->fetchAll();
								    						foreach($rows as $row){
                                    ?>
                                    <a href="?blogid=<?=$blogid;?>&type=<?=$type;?>&gallery_id=<?=$row['gallery_id'];?>&gallery_key=<?=$row['gallery_key'];?>&folder=<?=$folder;?>&page=<?=$page;?>"><?=$row['title'];?></a><br>
                                    <?php
                                }
                                */
                               
                                $repositories = $client->get($folder.'!folderlist');
                                ?>
                                  <h2>Folders</h2>
                                <?php
                               
                                if(isset($repositories->FolderList)){
                                  foreach($repositories->FolderList as $repo){
                                    ?>
                                    <a href="?blogid=<?=$blogid;?>&type=<?=$type;?>&folder=<?=$repo->Uri;?>&title=<?=$repo->Name;?>"><?=$repo->Name;?></a><br>
                                    <?php
                                  }
                                }

                                $repositories = $client->get($folder.'!albums');
                               ?>
                                <h2>Albums</h2>
                               <?php
                                if(isset($repositories->Album)){
                                  foreach($repositories->Album as $repo){
                                    ?>
                                    <a href="?blogid=<?=$blogid;?>&type=<?=$type;?>&gallery_id=<?=$repo->Uris->AlbumImages->Uri;?>&title=<?=$repo->NiceName;?>"><?=$repo->NiceName;?></a><br>
                                    <?php
                                  }
                                }
                            }
                          
                          	
                          	?><pre><?php //print_r($unique_arr);?></pre>
                          
                          	<?php
                          	if ($gallery_id != ''){
                          	   /*
                          	   $sql = "SELECT * from smugmug where gallery_key = '$gallery_key'";
                               $stmt = $db->query($sql);
								    					 $rows = $stmt->fetchAll();
                               $row = $rows[0];
                               $link = "https://api.smugmug.com".$row['folder'];
                               */
                          	   ?><h2><?=$title;?></h2><?
                              	?><pre><?php //print_r($_GET);?></pre>
                              	<?php
                              	// Get list of public images and other useful information
                              
                              	
                              	?><pre><?php //print_r($image);?></pre>
                              	<?php
                              
                              
                              $images = $client->get($gallery_id);
                              
                              	// Display the thumbnails and link to the medium image for each image
                              	?>
                                <div id="smugmug_list"><?php
                                foreach ( $images->AlbumImage as $image ) {
                                    
                                  $actual_image = $image->Uris->ImageSizes->Uri;
                                  
                                  
                                  
                                  $image_choices = $client->get($actual_image);
                                  
                                  $img_url = $image_choices->ImageSizes->XLargeImageUrl;
                                  //echo "img_url=".$img_url;
                                  
                                  echo '<a href="'.$image->WebUri.'" alt="'.$img_url.'"><img style="margin:2px 2px;" src="'.$image->ThumbnailUrl.'" title="'.$image->Caption.'" alt="'.$image->ThumbnailUrl.'" /></a>';
                              		
                              	}
                          	    ?>
                          	    </div>
                          	    <?php
                          	
                          	}
                          	
                          	
                          	
                         
                          ?>
                        </div>

