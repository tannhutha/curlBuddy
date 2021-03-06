<?php
	//	Define the root path to the curlBuddy PHP file
	define('__CURL_BUDDY_DIR__', realpath(dirname(__FILE__)));

	//	Includes
	require_once(__CURL_BUDDY_DIR__ . '/commons/curl.class.php');

	/**
	 *	Simple PHP cURL wrapper 
	**/
	final class curlBuddy{

		//	Creates a new instance of curlBuddy
		public function __construct(){}

		//	Magic call function to direct non existing methods to a
		//	method file within the methods directory. 
		//	format: /path/to/methods/methodName.method.php
		//			$obj = new methodName($args)
		public function __call($name, $arguments){
			$obj = false;
			$methods_path = __CURL_BUDDY_DIR__ . '/methods';
			if(is_dir($methods_path)){
				$method_file = $methods_path . "/{$name}.method.php";
				if(file_exists($method_file)){
					require_once($method_file);
					$method = "{$name}Method";
					if(class_exists($method)){
						$r = new ReflectionClass($method);
						$obj = $r->newInstanceArgs($arguments);
					}
				}
			}
			if($obj == false){
				// trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
				throw new Exception('Call to undefined method ' . __CLASS__ . '::' . $name . '()', 500);
			}
			return $obj;
		}

		//	Returns an instance of the curlBuddy object
		public function newCurl(){
			return $this;
		}
	}