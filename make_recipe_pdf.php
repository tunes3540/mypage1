<?php
// CONVERT ALL POST AND GET DATA
foreach ($_REQUEST as $param_name => $param_val) {
    if($param_name == "PHPSESSID"){continue;}
$$param_name = $param_val;
}

//-- remove files pdf folder --
$a = glob('pdf/*.pdf',GLOB_BRACE);
//-- show pdf file if it exists
if (count($a) > 0){
    foreach($a as $b){
        unlink($b);
    }
} 

//-- get working folder --
$url = $_SERVER['REQUEST_URI'];
$parts = parse_url($url);
$pieces = explode('/',$parts['path']);
$working_folder = $pieces[1];
include ("functions_connect.php");



$script = "c:/wkhtmltopdf/bin/wkhtmltopdf --disable-smart-shrinking --image-quality 100 --dpi 600 "; 
$script .= "--header-font-name \"times new roman\" --header-right \"Page [page] of [topage]\" ";

$script .= "--zoom 1.05 --margin-top 20 --header-spacing 10";
$script = $script." ".$_SERVER['HTTP_HOST']."/".$working_folder."/make_recipe.php?type=".$type."^&id=".$id;
$script = $script." \"c:/xampp/htdocs/".$working_folder."/pdf/".$id.".pdf\" " ; 
echo $script;
error_reporting(E_ALL); 
try {

echo exec($script);

} catch (Exception $e) {
echo $e->getMessage();
}


$new_pdf_file = "pdf/".$id.".pdf";

header("Location: $new_pdf_file"); 
          exit;
