<?php
require "vendor/autoload.php";
use Classes\File;
use Classes\Filetables;
if(strtolower($_SERVER['REQUEST_METHOD']) == "post"){
	$a = new File($_POST);
}else{
	die('Not Found');
}
?>