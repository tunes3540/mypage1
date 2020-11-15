<?php

$link = $_GET['link'];

require_once '../vendor/autoload.php';

// Optional, but definitely nice to have, options
$options = [
    'AppName' => 'mypage1/1.0 (http://claytonfelt.net)',
];
$client = new phpSmug\Client('RkKSbdrQWjFRgbjvhgpssjgg44cq9Ngz', $options);

$images = $client->get($link);



foreach ($images->AlbumImage as $image){
    $img_link = "https://api.smugmug.com".$image->Uris->Image->Uri;
    $this_image = $client->get($img_link, array('count' => 25));
    
    ?>
    <a href="<?=$this_image->Image->ArchivedUri;?>"><img src="<?=$image->ThumbnailUrl;?>"></a><br>
    
    <?php
}
