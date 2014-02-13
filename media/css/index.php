<?php  

require_once(dirname(__FILE__).'/../../settings/load.php');

$cssPath = _ROOT_DIR_._CSS_;
$cachePath = _CACHE_DIR_;
$cacheName = '';

if (isset($_GET['q'])){
    $files = $_GET['q'];
    foreach ($files as $key => $file){
		$files[$key] = str_replace(array('/', '\\', '.'), '', $file);
    }
	$cacheName .= implode('_', $files);
	$cacheName = $cachePath.'css_'.md5($cacheName).'.tmp';
   $cssData = '';
    
	if (_CACHE_CSS_ AND file_exists($cacheName) AND filemtime($cacheName) > (time() - 86400)) {
		$cacheHandle = fopen($cacheName, 'r');  
		$newData = fread($cacheHandle, filesize($cacheName));  
		fclose($cacheHandle);  
		$isCached = TRUE; 
	} else {
		foreach ($files as $file){
			$cssFileName = $cssPath.$file.'.css';
			//echo $cssFileName;
			if(file_exists($cssFileName)){
				$fileHandle = fopen($cssFileName, 'r');
				$cssData .= "\n" . fread($fileHandle, filesize($cssFileName));
				fclose($fileHandle);
			}
		}
		$newData = preg_replace('/\s+/', ' ', $cssData);
		$cacheHandle = fopen($cacheName, 'w+');  
		fwrite($cacheHandle, $newData);  
		fclose($cacheHandle);
		$isCached = FALSE;
	}
}

header("Content-type: text/css");
if (isset($newData)){
	echo $newData;
	if ($isCached){
		echo "\n// Retrieved from cache file. ImTheWonder";
	}
	echo "\n// Compiled on: " . date("r");
} else {
	echo "// Files not avalable or no files specified.";
}

?>