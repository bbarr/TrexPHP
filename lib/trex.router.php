<?php

namespace Trex;

class Router {
	
	public $request;
	
	public function __construct($request) {
		$this->request = $request;
	}
	
	public function search($location) {
		
		if (!is_dir($location)) {
			die ('Argument must be a string referencing a directory.');
		}

		// gather all the possible resource files
		$files = scandir($location);
		
		// find the resource that matches the URL
		// this could be expanded to allow more flexible URLs
		foreach ($files as $file) {
			
			// only check controller files
			if (strstr($file, '_controller.php')) {
				
				include $location . '/' . $file;
				
				$class_name = $this->file_name_to_class_name($file);
				$route = $this->extract_route($class_name);
				
				if ($this->test_route($route)) {
					return new $class_name();
				}
			}
		}
	}
	
	private function test_route($route) {
		return strstr($this->request->uri, $route);
	}
	
	private function file_name_to_class_name($file) {
		
		// turn into seperate words and capitalize them
		$class_name = ucwords(str_replace('_', ' ', $file));
		
		// turn back to one word
		$class_name = str_replace(' ', '', $class_name);
		
		// remove .php extention
		$class_name = split('\.', $class_name);
		$class_name = $class_name[0];
		
		// finally, return an instance of the found resource
		return 'Trex\\' . $class_name;
	}
	
	private function extract_route($class_name) {
		
		$reflection = new \ReflectionClass($class_name);
		$doc = $reflection->getDocComment();

		$routes = array();
		preg_match('/@route\s+(.+)\n/', $doc, $routes);
	
		if (!$routes[1]) $routes[1] = '/';

		return $routes[1];
	}
}

?>