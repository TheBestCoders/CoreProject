<?php

/**
* Disclaimer
* Don't try to delete pre defined constants, methods and variables
* Otherwise this framework won't work properly or stopped permanantly
* You can just change their values only
* Created by: 
* Alauddin Ansari
* alauddin_ansari@live.com
* Version: v1.0
*/

function __autoload($className)
{
	$classFile = $className.'.php';
	
	$classDir1 = dirname(__FILE__).'/../core_factory/';
	$classDir2 = dirname(__FILE__).'/../../controllers/';
	$classDir3 = dirname(__FILE__).'/../../models/';
	
	if(file_exists($classDir1.$classFile)){
		require_once($classDir1.$classFile);
	} elseif (file_exists($classDir2.$classFile)){
		require_once($classDir2.$classFile);
	} elseif (file_exists($classDir3.$classFile)){
		require_once($classDir3.$classFile);
	}
}

?>