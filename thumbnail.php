<?php

if(isset($_GET['thumb']) AND $_GET['thumb']!='')
	$filename = $_GET['thumb'];
	
if (!isset($filename)) {
	echo "ERROR:could not get image";
	exit(0);
}

$_width=0;
$_height=0;

if(isset($_GET['width']))
	$_width = (int)$_GET['width'];

if(isset($_GET['height']))
	$_height = (int)$_GET['height'];

$ext = substr($filename, strrpos($filename, '.')+1);
$ext = strtolower($ext);

// Get the image and create a thumbnail
if($ext=='jpg' || $ext=='jpeg'){
	$img = imagecreatefromjpeg($filename);
} else if($ext=='png'){
	$img = imagecreatefrompng($filename);
} else if($ext=='gif'){
	$img = imagecreatefromgif($filename);
}

if (!$img) {
	echo "ERROR:could not create image handle ". $filename;
	exit(0);
}


$width = imagesx($img);
$height = imagesy($img);

if (!$width || !$height) {
	echo "ERROR:Invalid width or height";
	exit(0);
}

$target_width = ($_width!=0) ? $_width : 100;
$target_height = ($_height!=0) ? $_height : 100;
$target_ratio = $target_width / $target_height;

$r = $width / $height;
if($width > $height){
	if($width>$target_width){
		$newWidth = $target_width;
	} else {
		$newWidth = $width;
	}
	$newHeight = round($newWidth / $r);
} else {
	if($height>$target_height){
		$newHeight = $target_height;
	} else {
		$newHeight = $height;
	}
	$newWidth = round($newHeight * $r);
}

$new_img = imagecreatetruecolor($newWidth, $newHeight);
$trans_layer_overlay = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
imagefill($new_img, 0, 0, $trans_layer_overlay);
	

if (!@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)) {
	echo "ERROR:Could not resize image";
	exit(0);
}

header("Content-Type: image/jpeg");


imagejpeg($new_img);
imagedestroy($new_img);

?>