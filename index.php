<?php 


	if (file_exists('vendor/autoload.php')) {
		require 'vendor/autoload.php';
	}else{
		return "Error: no se encontró el autoload.";
	}

	use config\components\configSystem as configSystem;

	$GlobalConfig = new configSystem();
	$GlobalConfig->_int();

	use bin\controller\frontController as frontController;

	$IndexSystem = new frontController($_REQUEST); 

 ?>