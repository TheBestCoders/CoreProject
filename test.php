<?php

/*require(dirname(__FILE__).'/include/init.php');
Controller::load('Test');*/

$data = array();

$data[2] = 'test';
$data[0] = 'hello';
$data[1] = 'world';

print_r($data);



exit;


$languages = array(
				1 => array(
					'id' => 1,
					'name' => 'en',
					'file' => 'english.php',
					'data' => array(
						'country' => 'IN'
					)
				),
				2 => array(
					'id' => 2,
					'name' => 'fr',
					'file' => 'french.php',
					'data' => array(
						'country' => 'US'
					)
				),
			);

var_dump($languages);

$test = array('IN', 'US');

echo array_search_multi('IN', $test, true);


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