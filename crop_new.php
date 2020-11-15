<?php ob_start(); 
    ?>
    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/jquery.Jcrop.js"></script>
    <link rel="stylesheet" href="js/jquery.Jcrop.css" type="text/css" />
    <?php
    require_once('config.php'); 
    require_once('functions.php');
    $file = $_REQUEST['file'];
    if (isset($_REQUEST['file']) && isset($_REQUEST['x'])) 
    { 
        $src =  $_REQUEST['file'];
        $targ_w = $targ_h = 150;
        $targ_w = 1024;
        $targ_h = 140;
        $jpeg_quality = 100;
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
        imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
            $targ_w,$targ_h,$_POST['w'],$_POST['h']);
        imagejpeg($dst_r, $src, $jpeg_quality);
        imagedestroy($img_r);
        imagedestroy($dst_r);
        //chmod($file, 0777);
        $find_slash = strpos($file,"/")+1;
        $thumb_file = "pageimages/thumb_".substr($file,$find_slash);
        ini_set("memory_limit","10000M");
        list($width, $height) = getimagesize($file);
        $ratio_orig = $width/$height;
        $new_width = 300;
        $new_height = 230;
        if ($new_width/$new_height > $ratio_orig) {
           $new_width = $new_height*$ratio_orig;
        } else {
           $new_height = $new_width/$ratio_orig;
        }
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($file);
        $response = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $thumb_file,100);
        chmod($thumb_file, 0777);
        imagedestroy($image_p);
        imagedestroy($image);
        //$convert_str = $path_to_convert."convert $file -resize 80x60 -quality 100 $thumb_file";
        //exec($convert_str);
        //chmod($thumb_file, 0777);
        echo "thumb_file = ".$thumb_file."<br>";
        echo "file = ".$file."<br>";
        echo "convert_str = ".$convert_str."<br>";
        header("Location: edit_top.php?cropped=yes&file=$file");  
        exit; 
    } 
?> 
<html> 
<head> 
    <style type="text/css"> 
        body, td, p 
        { 
            font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; 
            font-size: 10px; 
        } 
        #submit 
        { 
            font-size: 10px; 
            font-family: "MS Sans Serif", Geneva, sans-serif; 
            height: 23px; 
            background-color: #D4D0C8; 
            border: 0px; 
            padding: 3px,5px,3px,5px; 
            width: 100%; 
        } 
    </style> 
</head> 
<body> 
    <div id="outer">
       <div class="jcExample">
          <div class="article">
              <!--Image that we Will insert -->
              <img class='imagem_artigo' src="<?=$file;?>" id="cropbox" />
              <!--Form to crop-->
              <form action="" method="post" onsubmit="return checkCoords();">
                  <input type="hidden" id="x" name="x" /> 
                  <input type="hidden" id="y" name="y" />
                  <input type="hidden" id="w" name="w" /> 
                  <input type="hidden" id="h" name="h" /> 
                  <input type="hidden" name="file" value="<?=$file;?>">
                  <input type="submit" value="Crop Image" /> 
              </form> 
          </div> 
       </div> 
    </div>
<script language="Javascript"> 
    $(function(){ $('#cropbox').Jcrop({ 
      aspectRatio: 16 / 1.7, 
      setSelect:   [ 0, 100, 1024, 242 ],
      onSelect: updateCoords });
       }); 
       function updateCoords(c) { 
          $('#x').val(c.x); 
          $('#y').val(c.y); 
          $('#w').val(c.w); 
          $('#h').val(c.h); 
          }; 
        function checkCoords() { 
          if (parseInt($('#w').val())) return true; 
          alert('Select where you want to Crop.'); 
          return false; 
          }; 
</script>
</body> 
</html>
<? 