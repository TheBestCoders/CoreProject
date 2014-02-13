<?php

/* core system functions */

function print_this($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function getSortType($type)
{
	return ((strtolower($type)=='asc') ? 'DESC' : 'ASC');
}

// language functions
function l($str){
	return htmlentities($str);
}
function _l($str){
	return htmlentities($str, ENT_COMPAT, "UTF-8");
}
// language function ends

function get_rand_color()
{
	$color = strtoupper(dechex(rand(0,10000000)));
	while(strlen($color) < 6){
		$color .= strtoupper(dechex(rand(0,15)));
	}
	return $color; // like: F9AC2B
}

function nice_str($string, $need_str_len='')
{
	$stringlength = '';
	$refreshstring = trim( preg_replace("/\s+/"," ",html_entity_decode(str_replace(array("\r", "\n","&nbsp;","&amp;"), " ", strval(strip_tags(trim($string)))))));
	
	if(empty($need_str_len))
	{
		$stringlength = strlen($refreshstring);			
	}
	else
	{
		$stringlength = $need_str_len;
	}
	$trail = '';
	if(strlen($refreshstring) > $stringlength)
		$trail = '...';
	$refreshstring = wordwrap($refreshstring, $stringlength);
	$refreshstring = explode("\n", $refreshstring);
	$refreshstring = $refreshstring[0];
	$refreshstring = trim(preg_replace("/(\/)$/", "", $refreshstring));
	return $refreshstring.$trail;
	// return htmlentities($refreshstring,ENT_COMPAT, "ISO-8859-15");
	//return substr($refreshstring,0,$stringlength);
}

function str_insert($insert_string, $into_string, $offset) {
	$part1 = substr($into_string, 0, $offset);
	$part2 = substr($into_string, $offset);
	$whole = $part1.' '.$insert_string.' '.$part2;
	return $whole;
}

/* ONLINE PDF GENERATOR */
function phptopdf_url($source_url, $save_directory, $save_filename)
{		
	$API_KEY = 'mmnzerr8b22rjf2px';
	$url = 'http://phptopdf.com/urltopdf.php?key='.$API_KEY.'&url='.urlencode($source_url);
	$resultsXml = file_get_contents(($url)); 		
	file_put_contents($save_directory.$save_filename, $resultsXml);
}

function phptopdf_html($html, $save_directory, $save_filename)
{		
	$API_KEY = 'mmnzerr8b22rjf2px';
	$postdata = http_build_query(
		array(
			'html' => $html,
			'key' => $API_KEY
		)
	);
	
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',				
			'content' => $postdata
		)
	);
	
	$context  = stream_context_create($opts);
	
	$resultsXml = file_get_contents('http://phptopdf.com/htmltopdf.php', false, $context);
	file_put_contents($save_directory.$save_filename, $resultsXml);
}

// USES
function createPDF($pdfdata, $file_name)
{
	$url = _PDF_URL_.$file_name;
	phptopdf_html($pdfdata, _PDF_DIR_, $file_name);
	header("Location: $url");
}

/* ONLINE PDF GENERATOR ENDS */

function msort($array, $key, $sort_flags = SORT_REGULAR){
	if (is_array($array) && count($array) > 0) {
		if (!empty($key)) {
			$mapping = array();
			foreach ($array as $k => $v) {
				$sort_key = '';
				if (!is_array($key)) {
					$sort_key = $v[$key];
				} else {
					// @TODO This should be fixed, now it will be sorted as string
					foreach ($key as $key_key) {
						$sort_key .= $v[$key_key];
					}
					$sort_flags = SORT_STRING;
				}
				$mapping[$k] = $sort_key;
			}
			asort($mapping, $sort_flags);
			$sorted = array();
			foreach ($mapping as $k => $v) {
				$sorted[] = $array[$k];
			}
			return $sorted;
		}
	}
	return $array;
}

function sort_2d_asc($array, $key) {
	usort($array, function($a, $b) use ($key) {
		return strnatcasecmp($a[$key], $b[$key]);
	});
	return $array;
}

function sort_2d_desc($array, $key) {
	usort($array, function($a, $b) use ($key) {
		return strnatcasecmp($b[$key], $a[$key]);
	});
	return $array;
}

/** 
 ** Search for needle in multi-dimension array.
 ** Returns 1st dimension's key, If not found returns false
**/
function array_search_multi($needle, $haystack, $ignorecase = false)
{
	if(!is_array($needle) && is_array($haystack))
	{
		foreach($haystack as $key => $value)
		{
			if(is_array($value))
			{
				if(array_search_multi($needle, $value, $ignorecase))
					return $key;
			}
			else
			{
				if($ignorecase)
				{
					$needle = strtolower($needle);
					$value = strtolower($value);
				}
				if($needle == $value)
					return $key;
			}
		}
	}
	return false;
}

?>