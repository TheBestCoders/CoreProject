<?php

/**
* Image Handler Class 
* Makes easiest way to handle images.
* Created by: 
* Alauddin Ansari
* alauddin_ansari@live.com
* Version: v1.0
* Date: 18-April-2013
*/

/**
Usage:
$image = new Image();
$image->load('images/abc.jpg');
$image->resize(100, 200);
$image_src = $image->imageOutput('jpg');
*/

ini_set('memory_limit', '300M'); // to set memory limit

class Image
{
	private $_file;
	private $image;
	private $original_image;
	private $image_info;
	private $image_type;
	private $image_path;
	private $image_ext;
	private $image_width;
	private $image_height;
	public $error;
	private $debug;
	
	public function __construct()
	{
		// constructer
		$this->debug = _DEBUG_;
		
		if(!extension_loaded('gd') || !function_exists('gd_info')){
			$this->exception('GD Library is not installed in your appache server. Image class will not work properly.', true);
		}
	}
	
	public function upload($files = NULL, $destination = NULL, $allowed_file_types = array(), $width = 0, $height = 0)
	{
		if($files == NULL || !isset($files) || empty($files['name']) || $files['error']!=0){
			$this->exception('Image file not found or have errors.');
		}
		
		$this->_file = $files;

		if($destination == NULL){
			$this->exception('Destination not found!');
		}
		
		$this->image_ext = pathinfo($this->_file['name'], PATHINFO_EXTENSION);
		
		if(!empty($allowed_file_types) && count($allowed_file_types)){
			if(!in_array($this->image_ext, $allowed_file_types)){
				$this->exception('File Type is now allowed');
				return false;
			}
		}
		
		$this->makeDirectory($destination);
		if(move_uploaded_file($this->_file['tmp_name'], $destination)){
			$this->image_path = $destination;
			
			if($width || $height)
			{
				if(!$width) $width = $height;
				if(!$height) $height = $width;
				
				$this->load();
				$this->resize($width, $height);
				$this->save($destination);
			}
			
			return true;
		} else {
			$this->exception('Image cant be uploaded. Check Destination path carefully.');
			return false;
		}
	}
	
	/**
	To load the image in this object for further execution
	NOTE: If you already called upload method then you don't need to call this.
	*/
	public function load($filepath = NULL)
	{
		if($filepath!=NULL){
			$this->image_path = $filepath;
		}
		if($this->image_path==''){
			$this->exception('Image file not found');
		}
		
		if(file_exists($this->image_path))
		{
			$this->image_info = getimagesize($this->image_path);
			$this->image_type = $this->image_info[2];
			$this->image_ext = pathinfo($this->image_path, PATHINFO_EXTENSION);
			
			$this->image_width = $this->image_info[0];
			$this->image_height = $this->image_info[1];
			
			if($this->image_type == IMAGETYPE_JPEG){
				$this->image = imagecreatefromjpeg($this->image_path);
			} 
			elseif($this->image_type == IMAGETYPE_GIF){
				$this->image = imagecreatefromgif($this->image_path);
			} 
			elseif($this->image_type == IMAGETYPE_PNG){
				$this->image = imagecreatefrompng($this->image_path);
			}
			$this->original_image = $this->image;
		}
		else {
			$this->exception('Image file does not exits');
		}
	}
	
	public function save($filename = NULL, $compression = 75, $permissions = NULL)
	{
		if($filename == NULL){
			$this->exception('Image filename destination not found!');
			return false;
		}
		
		$image_type = pathinfo($filename, PATHINFO_EXTENSION);
		$image_type = $this->getImageType($image_type);
		
		$this->makeDirectory($filename); // It auto create directories in given path

		if($image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image, $filename, $compression);
		} 
		elseif($image_type == IMAGETYPE_GIF){
			imagegif($this->image, $filename);
		} 
		elseif($image_type == IMAGETYPE_PNG){
			imagepng($this->image, $filename);
		}
		if($permissions != NULL){
			chmod($filename,$permissions);
		}
	}
	
	public function output($image_type = 'jpg')
	{
		if($this->image && !empty($this->image)){
			$image_type = $this->getImageType($image_type);
				
			if($image_type == IMAGETYPE_JPEG){
				header("Content-Type: image/jpeg");
				imagejpeg($this->image);
			} 
			elseif($image_type == IMAGETYPE_GIF){
				header("Content-Type: image/gif");
				imagegif($this->image);
			} 
			elseif($image_type == IMAGETYPE_PNG){
				header("Content-Type: image/png");
				imagepng($this->image);
			}
			imagedestroy($this->image);
		}
	}
	
	public function imageOutput($image_type = 'jpg', $quality = '90')
	{
		if($this->image && !empty($this->image)){
			$img_type = $this->getImageType($image_type);
			ob_start();				
			if($img_type == IMAGETYPE_JPEG){
				imagejpeg($this->image, NULL, $quality);
			} 
			elseif($img_type == IMAGETYPE_GIF){
				imagegif($this->image);
			} 
			elseif($img_type == IMAGETYPE_PNG){
				imagepng($this->image);
			}
			$imgbinary = ob_get_contents(); // read from buffer
			ob_end_clean(); // delete buffer
			imagedestroy($this->image);
			return 'data:image/'.$image_type.';base64,'.base64_encode($imgbinary);
		}
	}

	public function getWidth(){
		return imagesx($this->image);
	}
	
	public function getHeight(){
		return imagesy($this->image);
	}
	
	public function resizeToHeight($height){
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
	
	public function resizeToWidth($width){
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width,$height);
	}
	
	public function scale($scale){
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}
	
	public function resize($width = NULL, $height = NULL){
		$target_width = ($width!=NULL) ? $width : $this->image_width;
		$target_height = ($height!=NULL) ? $height : $this->image_height;
		$target_ratio = $target_width / $target_height;
		
		$actual_width = $this->getWidth();
		$actual_height = $this->getHeight();
		$actual_ratio = $actual_width / $actual_height;
		
		$new_width = 0;
		$new_height = 0;
		
		if($actual_width > $actual_height){
			if($actual_width > $target_width){
				$new_width = $target_width;
			} else {
				$new_width = $actual_width;
			} // this use to create actual smaller image
			
			$new_width = $target_width; // to enlarge image even if resource is smaller.
			$new_height = round($new_width / $actual_ratio);
		} else {
			if($actual_height > $target_height){
				$new_height = $target_height;
			} else {
				$new_height = $actual_height;
			} // this use to create actual smaller image
			
			$new_height = $target_height; // to enlarge image even if resource is smaller.
			$new_width = round($new_height * $actual_ratio);
		}

		$new_image = imagecreatetruecolor($new_width, $new_height);
		$trans_layer_overlay = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
		imagefill($new_image, 0, 0, $trans_layer_overlay);
		
		imagecopyresampled($new_image, $this->original_image, 0, 0, 0, 0, $new_width, $new_height, $actual_width, $actual_height);
		$this->image = $new_image;
	}
	
	private function getImageType($filetype = NULL)
	{
		if($filetype == NULL)
			return IMAGETYPE_JPEG;
		
		if(is_numeric($filetype))
			return $filetype;
		
		$filetype = strtolower($filetype);
		$imagetype = IMAGETYPE_JPEG; // Default JPG
		
		switch($filetype)
		{
			case 'jpg':
			case 'jpeg':
				$imagetype = IMAGETYPE_JPEG;
				break;
			case 'png':
				$imagetype = IMAGETYPE_PNG;
				break;
			case 'gif':
				$imagetype = IMAGETYPE_GIF;
				break;
		}
		
		return $imagetype;
	}
	
	private function makeDirectory($destination = '')
	{
		if(strpos($destination, '/')){
			$dir_structure = explode('/', $destination);
			array_pop($dir_structure);
			$dir_trail = '';
			
			if(count($dir_structure)){
				foreach($dir_structure as $dir){
					$dir_trail .= $dir.'/';
					if(!file_exists($dir_trail) || !is_dir($dir_trail)){
						mkdir($dir_trail);
					}
				}
			}
		}
	}
	
	private function exception($data = '', $throwNow = false)
	{
		$this->error .= $data;
		
		if($this->debug || $throwNow){
			$str = '<div style="background:#FF9999; border:1px solid #ccc; padding:10px;">';
			$str .= $data;
			$str .= '</div>';
			
			die($str);
		}
	}
}

?>