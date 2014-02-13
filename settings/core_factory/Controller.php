<?php

class Controller
{
	private static $controller_dir = "controllers/";
	
	public static function load($controller = NULL)
	{
		if(!empty($controller))
		{
			$controller_segments = str_replace(array('Controller', 'Controller.php'), '', $controller);
		}
		else
		{
			$controller_segments = Core::getValue('query_segments');
		}
		$query_segments = trim($controller_segments, '/');
		$segments = explode('/', $query_segments);
		$controller = self::getController($segments);
		
		if(!$controller){
			$session = new Session();
			$session->error = "Controller not found!";
			Core::redirect("NotFound");
		}
		//print_this($controller);
		self::loadController($controller);
	}
	
	private static function loadController($controller)
	{
		require_once(Core::getRootPath(self::$controller_dir.$controller['path']));
		
		//try{
			$core = new $controller['name'];
			$core->load($controller['method']);
		//} catch(Exeption e){
			
		//}
	}
	
	private static function getController($segments)
	{
		$controller = false;
		$method = false;
		$cnt_found = false;
		if(isset($segments[0]) && !empty($segments[0]))
		{
			for($s=1; $s <= count($segments); $s++)
			{
				if($cnt_found){
					$tmp = array_slice($segments, ($s-1), 1);
					$method = array_pop($tmp);
					$controller['method'] = $method;
					$cnt_found = false;
				}
				$seg = array_slice($segments, 0, $s);
				$tmp_name = ucfirst(array_pop($seg));
				array_splice($seg, ($s), 0, $tmp_name);
				$dir = implode('/', $seg);
				$cnt = $dir."Controller.php";
				
				if(Core::fileExists(self::$controller_dir.$cnt))
				{
					$cnt_name = ucfirst(array_pop($seg))."Controller";
					$controller = array('name' => $cnt_name, 'path' => $cnt, 'method' => '');
					$cnt_found = true;
				}
			}
			if(!$method){
				$method = reset($segments);
			}
		}
		if(!$controller){
			$cnt_name = 'HomeController';
			$controller = array('name' => $cnt_name, 'path' => $cnt_name.'.php', 'method' => $method);
		}
		return $controller;
	}
}
?>