<?php
// This file is generated by Composer
require_once '../vendor/autoload.php';

// Optional, but definitely nice to have, options
$options = [
    'AppName' => 'mypage1/1.0 (http://claytonfelt.net)',
];
$client = new phpSmug\Client('RkKSbdrQWjFRgbjvhgpssjgg44cq9Ngz', $options);
$repositories = $client->get('user/claytonfelt!albums');

foreach($repositories->Album as $repo){
     ?>
    <a href="show_album1.php?link=https://api.smugmug.com<?=$repo->Uris->AlbumImages->Uri;?>"><?=$repo->NiceName;?></a><br>
    <?php

}

