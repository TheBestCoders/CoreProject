<?php

require(dirname(__FILE__).'/settings/load.php');
Controller::load("");
exit;

echo '<pre>';
print_r($_GET);

$query_segments = trim($_GET['query_segments'], '/');
$query_string = $_GET['query_string'];

$segments = explode('/', $query_segments);

print_r($segments);

$controller = getController($segments);

if(!$controller){
	$session = new Session();
	$session->error = "Controller not found!";
	Core::redirect("404.php");
}

print_this($controller);
echo $query_string;



function getController($segments)
{
	$controller = false;
	if(count($segments)>0)
	{
		for($s=1; $s <= count($segments); $s++)
		{
			$cnt = implode('/', array_slice($segments, 0, $s))."Controller.php";
				if(Core::fileExists("controllers/".$cnt))
				{
					$cnt_path = $cnt;
					$cnt = array_slice($segments, ($s - 1), 1);
					$cnt_name = array_pop($cnt)."Controller";
					$controller = array('name' => $cnt_name, 'path' => $cnt_path);
				}
		}
	}
	return $controller;
}


?>