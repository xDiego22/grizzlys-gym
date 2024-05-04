<?php 

	namespace config\components;
	use Dotenv\Dotenv;

	$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
	$dotenv->load();



	define("_URL_", $_ENV['_URL_']);
	define("_BD_", $_ENV['_BD_']);
	define("_PASS_", $_ENV['_PASS_']);
	define("_USER_", $_ENV['_USER_']);
	define("_LOCAL_", $_ENV['_LOCAL_']);
	define("_TOKEN_", $_ENV['_LOCAL_']);
	define("DIRECTORY_CONTROLLER", $_ENV['DIRECTORY_CONTROLLER']);
	define("DIRECTORY_MODEL", $_ENV['DIRECTORY_MODEL']);
	define("DIRECTORY_VIEW", $_ENV['DIRECTORY_VIEW']);
	define("MODEL", $_ENV['MODEL']);
	define("CONTROLLER", $_ENV['CONTROLLER']);
	define("VIEW", $_ENV['VIEW']);


	class configSystem {
		public function _int(){
			if(!file_exists("bin/controller/frontController.php")){
				return "Error configSystem";
			}
		}

		public function _URL_(){
			return _URL_;
		}
		public function _BD_(){
			return _BD_;
		}
		public function _PASS_(){
			return _PASS_;
		}
		public function _USER_(){
			return _USER_;
		}
		public function _LOCAL_(){
			return _LOCAL_;
		}
		public function _Dir_Control_(){
			return DIRECTORY_CONTROLLER; 
		}
		public function _Dir_Model_(){
			return DIRECTORY_MODEL; 
		}
		public function _Dir_View_(){
			return DIRECTORY_VIEW; 
		}
		public function _MODEL_(){
			return MODEL;
		}
		public function _Control_(){
			return CONTROLLER;
		}
		public function _VIEW_(){
			return VIEW;
		}
	}

 ?>